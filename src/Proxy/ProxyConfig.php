<?php

declare(strict_types=1);

namespace Kwork\Proxy;

/**
 * Proxy configuration with SOCKS4/SOCKS5/HTTP/HTTPS support.
 *
 * Guzzle/cURL accepts proxy URLs such as:
 * - socks5://host:1080
 * - socks5h://user:pass@host:1080  (remote DNS resolution)
 * - socks4://host:1080
 * - http://user:pass@host:8080
 */
final class ProxyConfig
{
    public function __construct(
        public readonly ProxyType $type,
        public readonly string $host,
        public readonly int $port,
        public readonly ?string $username = null,
        public readonly ?string $password = null,
        public readonly bool $remoteDns = true,
    ) {
        if ($host === '') {
            throw new \InvalidArgumentException('Proxy host must not be empty');
        }

        if ($port < 1 || $port > 65535) {
            throw new \InvalidArgumentException('Proxy port must be between 1 and 65535');
        }
    }

    public function toUrl(): string
    {
        $scheme = match ($this->type) {
            ProxyType::Socks5 => $this->remoteDns ? 'socks5h' : 'socks5',
            default => $this->type->value,
        };

        $auth = '';
        if ($this->username !== null && $this->username !== '') {
            $user = rawurlencode($this->username);
            $pass = rawurlencode($this->password ?? '');
            $auth = $user . ($pass !== '' ? ':' . $pass : '') . '@';
        }

        return sprintf('%s://%s%s:%d', $scheme, $auth, $this->host, $this->port);
    }

    public function toGuzzleOption(): string
    {
        return $this->toUrl();
    }

    public static function fromString(string $proxy): self
    {
        $proxy = trim($proxy);
        if ($proxy === '') {
            throw new \InvalidArgumentException('Proxy string must not be empty');
        }

        if (!str_contains($proxy, '://')) {
            $proxy = 'http://' . $proxy;
        }

        $parts = parse_url($proxy);
        if ($parts === false || !isset($parts['host'])) {
            throw new \InvalidArgumentException('Invalid proxy URL: ' . $proxy);
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? 'http'));
        $type = match ($scheme) {
            'socks4', 'socks4a' => ProxyType::Socks4,
            'socks5', 'socks5h' => ProxyType::Socks5,
            'https' => ProxyType::Https,
            default => ProxyType::Http,
        };

        $remoteDns = $scheme === 'socks5h';

        return new self(
            type: $type,
            host: (string) $parts['host'],
            port: (int) ($parts['port'] ?? ($type->isSocks() ? 1080 : 8080)),
            username: isset($parts['user']) ? rawurldecode((string) $parts['user']) : null,
            password: isset($parts['pass']) ? rawurldecode((string) $parts['pass']) : null,
            remoteDns: $remoteDns,
        );
    }

    public static function socks5(
        string $host,
        int $port = 1080,
        ?string $username = null,
        ?string $password = null,
        bool $remoteDns = true,
    ): self {
        return new self(ProxyType::Socks5, $host, $port, $username, $password, $remoteDns);
    }

    public static function socks4(
        string $host,
        int $port = 1080,
        ?string $username = null,
        ?string $password = null,
    ): self {
        return new self(ProxyType::Socks4, $host, $port, $username, $password, false);
    }

    public static function http(
        string $host,
        int $port = 8080,
        ?string $username = null,
        ?string $password = null,
    ): self {
        return new self(ProxyType::Http, $host, $port, $username, $password, false);
    }

    /**
     * @param string|self|null $proxy
     */
    public static function resolve(mixed $proxy): ?string
    {
        if ($proxy === null) {
            return null;
        }

        if ($proxy instanceof self) {
            return $proxy->toUrl();
        }

        if (is_string($proxy)) {
            return self::fromString($proxy)->toUrl();
        }

        throw new \InvalidArgumentException('Proxy must be a string or ProxyConfig instance');
    }
}

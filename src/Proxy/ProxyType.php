<?php

declare(strict_types=1);

namespace Kwork\Proxy;

enum ProxyType: string
{
    case Http = 'http';
    case Https = 'https';
    case Socks4 = 'socks4';
    case Socks5 = 'socks5';

    public function isSocks(): bool
    {
        return $this === self::Socks4 || $this === self::Socks5;
    }
}

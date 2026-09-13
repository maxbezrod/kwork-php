<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\Proxy\ProxyConfig;
use Kwork\Proxy\ProxyType;
use PHPUnit\Framework\TestCase;

final class ProxyConfigTest extends TestCase
{
    public function testSocks5UrlWithAuth(): void
    {
        $proxy = ProxyConfig::socks5('proxy.example.com', 1080, 'user', 'secret');

        self::assertSame(ProxyType::Socks5, $proxy->type);
        self::assertSame('socks5h://user:secret@proxy.example.com:1080', $proxy->toUrl());
    }

    public function testSocks4Url(): void
    {
        $proxy = ProxyConfig::socks4('127.0.0.1', 9050);

        self::assertSame('socks4://127.0.0.1:9050', $proxy->toUrl());
    }

    public function testFromStringParsesSocks5h(): void
    {
        $proxy = ProxyConfig::fromString('socks5h://10.0.0.1:1080');

        self::assertSame(ProxyType::Socks5, $proxy->type);
        self::assertTrue($proxy->remoteDns);
        self::assertSame('socks5h://10.0.0.1:1080', $proxy->toUrl());
    }

    public function testResolveAcceptsString(): void
    {
        self::assertSame(
            'socks5://127.0.0.1:1080',
            ProxyConfig::resolve('socks5://127.0.0.1:1080'),
        );
    }

    public function testHttpProxy(): void
    {
        $proxy = ProxyConfig::http('gateway.local', 3128, 'admin', 'pass');

        self::assertSame('http://admin:pass@gateway.local:3128', $proxy->toUrl());
    }
}

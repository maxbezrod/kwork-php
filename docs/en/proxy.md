# Proxy Guide

kwork-php supports HTTP, HTTPS, SOCKS4, and SOCKS5 proxies via Guzzle/cURL.

## Quick usage

```php
use Kwork\KworkClient;
use Kwork\Proxy\ProxyConfig;

// SOCKS5 with authentication
$client = new KworkClient(
    'login',
    'password',
    ProxyConfig::socks5('proxy.example.com', 1080, 'user', 'pass'),
);

// SOCKS4
$client = new KworkClient('login', 'password', ProxyConfig::socks4('127.0.0.1', 9050));

// URL string
$client = new KworkClient('login', 'password', 'socks5h://user:pass@host:1080');
```

## ProxyConfig API

| Factory | Description |
|---------|-------------|
| `ProxyConfig::socks5($host, $port, $user, $pass)` | SOCKS5 with remote DNS (`socks5h://`) |
| `ProxyConfig::socks4($host, $port)` | SOCKS4 |
| `ProxyConfig::http($host, $port, $user, $pass)` | HTTP proxy |
| `ProxyConfig::fromString($url)` | Parse any proxy URL |
| `ProxyConfig::resolve($proxy)` | Normalize string or config to URL |

## DNS resolution

- `socks5://` — local DNS resolution
- `socks5h://` — remote DNS via proxy (default for `ProxyConfig::socks5()`)

## Async client

Proxies work identically with `AsyncKworkClient`:

```php
$client = new AsyncKworkClient('login', 'password', ProxyConfig::socks5('host', 1080));
$me = $client->getMeAsync()->wait();
```

## Requirements

- PHP ext-curl with SOCKS support (most builds include this)
- Proxy must allow HTTPS connections to `api.kwork.ru` and `wss://notice.kwork.ru`

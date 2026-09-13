# kwork-php

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Typed **sync** and **async** PHP client for [kwork.ru](https://kwork.ru/) mobile API (`api.kwork.ru`) with WebSocket bot, SOCKS proxy support, and web-session helpers.

> **Disclaimer:** This is an unofficial community library and is not affiliated with kwork.ru.

## Features

- **Sync client** — `KworkClient` / `Kwork` for straightforward blocking usage
- **Async client** — `AsyncKworkClient` / `AsyncKwork` with Guzzle promises for concurrent requests
- **WebSocket bot** — `KworkBot` and `AsyncKworkBot` for real-time inbox automation
- **SOCKS4 / SOCKS5 / HTTP proxy** — via `ProxyConfig` or proxy URL strings
- **256 OpenAPI endpoints** — auto-generated typed wrappers
- **Web flows** — cookie bridge and exchange-offer submission via `KworkWebClient`
- **Retries** — exponential backoff, jitter, `Retry-After` support
- **Typed schemas** — `Actor`, `User`, `DialogMessage`, `WantWorker`, and more

## Requirements

- PHP 8.1+
- Composer
- ext-json, ext-curl (recommended for SOCKS proxy)

## Installation

```bash
composer require maxbezrod/kwork-php
```

## Quick start (sync)

```php
<?php

require 'vendor/autoload.php';

use Kwork\KworkClient;
use Kwork\Proxy\ProxyConfig;

$client = new KworkClient(
    login: 'your_login',
    password: 'your_password',
    proxy: ProxyConfig::socks5('127.0.0.1', 1080),
    retryMaxAttempts: 3,
);

$me = $client->getMe();
echo "{$me->username} | {$me->freeAmount} {$me->currency}" . PHP_EOL;

$dialogs = $client->getDialogsPage(1);
$client->sendMessage($userId, 'Hello!');

$client->close();
```

`Kwork` is an alias for `KworkClient`.

## Quick start (async)

```php
<?php

use Kwork\Async\AsyncKworkClient;

$client = new AsyncKworkClient('login', 'password', retryMaxAttempts: 3);

$me = $client->getMeAsync()->wait();

// Concurrent requests
[$categories, $connects] = AsyncKworkClient::all([
    $client->getCategoriesAsync(),
    $client->getConnectsAsync(),
]);

$client->close();
```

`AsyncKwork` is an alias for `AsyncKworkClient`.

## WebSocket bot

```php
use Kwork\KworkBot;
use Kwork\Schema\Message;

$bot = new KworkBot('login', 'password');
$bot->messageHandler(function (Message $message): void {
    $message->fastAnswer('Hi!');
}, text: '/start');

$bot->run();
```

Async variant:

```php
use Kwork\Async\AsyncKworkBot;

$bot = new AsyncKworkBot('login', 'password');
$bot->messageHandler(fn ($m) => $m->fastAnswer('Hello!'));
$bot->run();
```

## Proxy configuration

```php
use Kwork\Proxy\ProxyConfig;

// SOCKS5 with remote DNS (recommended)
$proxy = ProxyConfig::socks5('proxy.host', 1080, 'user', 'pass');

// SOCKS4
$proxy = ProxyConfig::socks4('127.0.0.1', 9050);

// Or pass a URL string
$client = new KworkClient('login', 'password', proxy: 'socks5h://user:pass@host:1080');
```

## Web session & exchange offers

```php
$login = $client->webLogin('/');
$client->web()->submitExchangeOffer(
    projectId: 12345,
    description: 'I can help with your project',
    kworkDuration: 3,
    kworkPrice: 1500,
    kworkName: 'Custom offer',
);
```

## Documentation

- [English guide](docs/en/guide.md)
- [Русская документация](docs/ru/guide.md)
- [API reference](docs/en/api.md)
- [Proxy guide](docs/en/proxy.md)

## Development

```bash
composer install
composer test
composer run generate   # regenerate OpenAPI traits
```

## License

MIT — see [LICENSE](LICENSE).

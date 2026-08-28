# kwork-php

PHP client for [kwork.ru](https://kwork.ru/) mobile API (`api.kwork.ru`) with WebSocket bot and web-session helpers.

## Requirements

- PHP 8.1+
- Composer

## Installation

```bash
composer require kesha1225/kwork-php
```

## Quick start

```php
<?php

require 'vendor/autoload.php';

use Kwork\KworkClient;

$client = new KworkClient('login', 'password', retryMaxAttempts: 3);

$me = $client->getMe();
$dialogs = $client->getDialogsPage(1);
$client->sendMessage($userId, 'Hello');

$client->close();
```

`Kwork` is an alias for `KworkClient`.

## Bot example

```php
use Kwork\KworkBot;
use Kwork\Schema\Message;

$bot = new KworkBot('login', 'password');
$bot->messageHandler(function (Message $message): void {
    $message->fastAnswer('Hi!');
}, text: '/start');
$bot->run();
```

## Features

- HTTP transport with token auth, retries, multipart uploads, sensitive-field redaction
- Typed helpers for dialogs, messages, categories, projects, orders
- 256 auto-generated OpenAPI mobile endpoints
- WebSocket bot (`KworkBot`) for inbox automation
- Web cookie bridge (`KworkWebClient`) for exchange offer flows

## Development

```bash
composer install
composer test
```

Regenerate OpenAPI trait after updating `docs/openapi.json`:

```bash
php tools/generate_openapi_trait.php
```

## License

MIT — see [LICENSE](LICENSE).

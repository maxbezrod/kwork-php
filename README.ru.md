# kwork-php

Типизированный **синхронный** и **асинхронный** PHP-клиент для мобильного API [kwork.ru](https://kwork.ru/) (`api.kwork.ru`) с WebSocket-ботом, поддержкой SOCKS-прокси и веб-хелперами.

> **Важно:** библиотека не является официальным SDK kwork.ru и не связана с площадкой.

## Возможности

- **Синхронный клиент** — `KworkClient` / `Kwork`
- **Асинхронный клиент** — `AsyncKworkClient` / `AsyncKwork` на Guzzle Promises
- **WebSocket-бот** — `KworkBot` и `AsyncKworkBot`
- **SOCKS4 / SOCKS5 / HTTP прокси** — через `ProxyConfig` или URL-строку
- **256 OpenAPI-эндпоинтов** — автогенерируемые обёртки
- **Веб-сессия** — авторизация через `getWebAuthToken` и отправка откликов
- **Повторы запросов** — backoff, jitter, `Retry-After`
- **Схемы данных** — `Actor`, `User`, `DialogMessage`, `WantWorker` и др.

## Установка

```bash
composer require maxbezrod/kwork-php
```

## Быстрый старт (синхронно)

```php
<?php

require 'vendor/autoload.php';

use Kwork\KworkClient;
use Kwork\Proxy\ProxyConfig;

$client = new KworkClient(
    login: 'логин',
    password: 'пароль',
    proxy: ProxyConfig::socks5('127.0.0.1', 1080),
    retryMaxAttempts: 3,
);

$me = $client->getMe();
echo "{$me->username} | {$me->freeAmount} {$me->currency}\n";

$client->sendMessage($userId, 'Привет!');
$client->close();
```

## Быстрый старт (асинхронно)

```php
use Kwork\Async\AsyncKworkClient;

$client = new AsyncKworkClient('логин', 'пароль', retryMaxAttempts: 3);

$me = $client->getMeAsync()->wait();

[$categories, $orders] = AsyncKworkClient::all([
    $client->getCategoriesAsync(),
    $client->getWorkerOrdersAsync(),
]);
```

## WebSocket-бот

```php
use Kwork\KworkBot;
use Kwork\Schema\Message;

$bot = new KworkBot('логин', 'пароль');
$bot->messageHandler(function (Message $message): void {
    $message->fastAnswer('Привет!');
}, text: '/start');

$bot->run();
```

## Прокси

```php
use Kwork\Proxy\ProxyConfig;

$proxy = ProxyConfig::socks5('proxy.host', 1080, 'user', 'pass');
$proxy = ProxyConfig::socks4('127.0.0.1', 9050);

$client = new KworkClient('логин', 'пароль', proxy: 'socks5h://user:pass@host:1080');
```

## Документация

- [Гайд (EN)](docs/en/guide.md)
- [Гайд (RU)](docs/ru/guide.md)
- [Справка API](docs/ru/api.md)
- [Прокси](docs/ru/proxy.md)

## Разработка

```bash
composer install
composer test
```

## Лицензия

MIT — см. [LICENSE](LICENSE).

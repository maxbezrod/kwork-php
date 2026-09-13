# Работа с прокси

Библиотека поддерживает HTTP, HTTPS, SOCKS4 и SOCKS5 через Guzzle/cURL.

## Быстрый старт

```php
use Kwork\KworkClient;
use Kwork\Proxy\ProxyConfig;

$client = new KworkClient(
    'логин',
    'пароль',
    ProxyConfig::socks5('proxy.example.com', 1080, 'user', 'pass'),
);

$client = new KworkClient('логин', 'пароль', 'socks5h://user:pass@host:1080');
```

## ProxyConfig

| Метод | Описание |
|-------|----------|
| `ProxyConfig::socks5(...)` | SOCKS5 с удалённым DNS (`socks5h://`) |
| `ProxyConfig::socks4(...)` | SOCKS4 |
| `ProxyConfig::http(...)` | HTTP-прокси |
| `ProxyConfig::fromString($url)` | Парсинг URL |
| `ProxyConfig::resolve($proxy)` | Нормализация |

## DNS

- `socks5://` — DNS резолвится локально
- `socks5h://` — DNS через прокси (по умолчанию в `socks5()`)

## Асинхронный клиент

```php
$client = new AsyncKworkClient('логин', 'пароль', ProxyConfig::socks5('host', 1080));
$me = $client->getMeAsync()->wait();
```

## Требования

- PHP ext-curl с поддержкой SOCKS
- Прокси должен пропускать HTTPS к `api.kwork.ru` и WSS к `notice.kwork.ru`

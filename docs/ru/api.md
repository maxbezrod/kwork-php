# Справка API

## Основные классы

| Класс | Назначение |
|-------|------------|
| `Kwork\KworkAPI` | Низкоуровневый sync HTTP |
| `Kwork\KworkClient` | Высокоуровневый sync клиент |
| `Kwork\Async\AsyncKworkAPI` | Низкоуровневый async HTTP |
| `Kwork\Async\AsyncKworkClient` | Высокоуровневый async клиент |
| `Kwork\KworkBot` | WebSocket-бот (sync) |
| `Kwork\Async\AsyncKworkBot` | WebSocket-бот (async API) |
| `Kwork\KworkWebClient` | Веб-сессия kwork.ru |
| `Kwork\Proxy\ProxyConfig` | Конфигурация прокси |

## Трейты

- `OpenAPIMethodsTrait` — 256 sync OpenAPI-методов
- `AsyncOpenAPIMethodsTrait` — 256 async OpenAPI-методов
- `APKExtraMethodsTrait` — эндпоинты из APK

## Схемы

`Actor`, `User`, `DialogMessage`, `InboxMessage`, `Message`, `WantWorker`,
`Connects`, `KworkObject`, `Project`, `Category`, `Achievement`, `Review`

## Исключения

| Класс | Когда |
|-------|-------|
| `KworkException` | API вернул ошибку |
| `KworkHTTPException` | HTTP-ошибка |
| `KworkRetryExceeded` | Исчерпаны повторы |
| `KworkBotException` | Ошибка конфигурации бота |

Подробнее — [guide.md](guide.md).

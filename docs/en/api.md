# API Reference

## Core classes

### `Kwork\KworkAPI`

Low-level sync HTTP transport.

### `Kwork\KworkClient`

High-level sync client. Extends `KworkAPI`, implements `DialogLookupInterface`.

### `Kwork\Async\AsyncKworkAPI`

Low-level async HTTP transport (Guzzle promises).

### `Kwork\Async\AsyncKworkClient`

High-level async client with `*Async()` methods.

### `Kwork\KworkBot` / `Kwork\Async\AsyncKworkBot`

WebSocket bots extending respective clients.

### `Kwork\KworkWebClient`

Web session bridge for `kwork.ru` cookie flows.

### `Kwork\Proxy\ProxyConfig`

Proxy configuration with SOCKS4/5 support.

## Traits

- `OpenAPIMethodsTrait` — 256 sync OpenAPI methods
- `AsyncOpenAPIMethodsTrait` — 256 async OpenAPI methods
- `APKExtraMethodsTrait` / `AsyncAPKExtraMethodsTrait` — APK-discovered endpoints

## Schema models

`Actor`, `User`, `Category`, `ParentCategory`, `SubCategory`, `DialogMessage`,
`InboxMessage`, `Message`, `WantWorker`, `Connects`, `KworkObject`, `Project`,
`Achievement`, `PortfolioItem`, `Review`, `BaseEvent`, `EventType`, `Notify`

## Exceptions

| Class | When |
|-------|------|
| `KworkException` | API returned `success: false` |
| `KworkHTTPException` | Non-2xx HTTP response |
| `KworkRetryExceeded` | All retry attempts failed |
| `KworkBotException` | Bot configuration error |

See [guide.md](guide.md) for usage examples.

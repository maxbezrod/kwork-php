# kwork-php Guide (English)

## Clients overview

| Class | Alias | Mode |
|-------|-------|------|
| `Kwork\KworkClient` | `Kwork\Kwork` | Sync (blocking) |
| `Kwork\Async\AsyncKworkClient` | `Kwork\AsyncKwork` | Async (promises) |
| `Kwork\KworkBot` | — | Sync WebSocket bot |
| `Kwork\Async\AsyncKworkBot` | — | Async API + WebSocket bot |

## Authentication

Both clients authenticate via `signIn` and cache the token automatically.

```php
$client = new KworkClient('login', 'password', phoneLast: '1234');
$token = $client->getToken();
```

For accounts with 2FA phone confirmation, pass the last digits via `phoneLast`.

## High-level API methods

| Method | Description |
|--------|-------------|
| `getMe()` | Current user (`Actor`) |
| `getUser($id)` | User profile |
| `getDialogsPage($page)` | Dialog list page |
| `getAllDialogs()` | All dialogs (paginated) |
| `getDialogWithUser($username)` | Full conversation |
| `sendMessage($userId, $text)` | Send inbox message |
| `deleteMessage($messageId)` | Delete message |
| `getCategories()` | Project categories |
| `getProjects(...)` | Search projects |
| `getAllProjects(...)` | All project pages |
| `getConnects()` | Available connects |
| `getWorkerOrders()` / `getPayerOrders()` | Orders |
| `getNotifications()` | Notifications |
| `markNotificationsRead()` | Mark notifications read |
| `findDialogByUsername($name)` | Find dialog by username |
| `setTyping($recipientId)` | Typing indicator |
| `setOffline()` | Set offline status |
| `getChannel()` | WebSocket channel |
| `webLogin()` | Establish web session |
| `web()->submitExchangeOffer(...)` | Submit project offer |

Async variants append `Async` suffix and return `PromiseInterface`.

## OpenAPI methods

All 256 mobile API endpoints are available as generated methods on both clients:

```php
$client->portfolio(true, ['user_id' => 123]);
$client->portfolioAsync(true, ['user_id' => 123])->wait();
```

Regenerate after updating `docs/openapi.json`:

```bash
composer run generate
```

## Error handling

```php
use Kwork\Exception\KworkException;
use Kwork\Exception\KworkHTTPException;
use Kwork\Exception\KworkRetryExceeded;

try {
    $client->getMe();
} catch (KworkHTTPException $e) {
    echo $e->status; // HTTP status code
} catch (KworkException $e) {
    echo $e->getMessage(); // API-level error
} catch (KworkRetryExceeded $e) {
    echo $e->attempts; // retry count
}
```

## Bot handlers

```php
$bot->messageHandler($fn);                          // all messages
$bot->messageHandler($fn, text: '/start');          // exact match
$bot->messageHandler($fn, textContains: 'price');   // word match
$bot->messageHandler($fn, onStart: true);           // first message only
```

Message helpers:

```php
$message->fastAnswer('Quick reply');
$message->answerSimulation('With typing delay');
```

## Trust & limitations

- Not an official kwork.ru SDK
- Web endpoints (`kwork.ru`) may change without notice
- Use responsibly and respect platform ToS

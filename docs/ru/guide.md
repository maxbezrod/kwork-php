# kwork-php — руководство

## Клиенты

| Класс | Псевдоним | Режим |
|-------|-----------|-------|
| `Kwork\KworkClient` | `Kwork\Kwork` | Синхронный |
| `Kwork\Async\AsyncKworkClient` | `Kwork\AsyncKwork` | Асинхронный |
| `Kwork\KworkBot` | — | WebSocket-бот |
| `Kwork\Async\AsyncKworkBot` | — | Бот с async API |

## Авторизация

```php
$client = new KworkClient('логин', 'пароль', phoneLast: '1234');
$token = $client->getToken();
```

Параметр `phoneLast` — последние цифры телефона при двухфакторной проверке.

## Основные методы

| Метод | Описание |
|-------|----------|
| `getMe()` | Текущий пользователь |
| `getUser($id)` | Профиль пользователя |
| `getDialogsPage($page)` | Страница диалогов |
| `getAllDialogs()` | Все диалоги |
| `getDialogWithUser($username)` | Переписка с пользователем |
| `sendMessage($userId, $text)` | Отправить сообщение |
| `getCategories()` | Категории проектов |
| `getProjects(...)` | Поиск проектов |
| `getAllProjects(...)` | Все страницы проектов |
| `getConnects()` | Доступные коннекты |
| `getWorkerOrders()` / `getPayerOrders()` | Заказы |
| `getNotifications()` | Уведомления |
| `findDialogByUsername($name)` | Найти диалог |
| `web()->submitExchangeOffer(...)` | Отправить отклик |

Асинхронные версии: суффикс `Async`, возвращают `PromiseInterface`.

## OpenAPI

256 эндпоинтов мобильного API доступны как сгенерированные методы:

```php
$client->portfolio(true, ['user_id' => 123]);
```

Перегенерация:

```bash
composer run generate
```

## Обработка ошибок

- `KworkException` — ошибка API
- `KworkHTTPException` — HTTP-ошибка (есть `status`)
- `KworkRetryExceeded` — исчерпаны попытки повтора

## Бот

```php
$bot->messageHandler($fn);                          // все сообщения
$bot->messageHandler($fn, text: '/start');          // точное совпадение
$bot->messageHandler($fn, textContains: 'цена');   // по слову
$bot->messageHandler($fn, onStart: true);           // первое сообщение
```

## Ограничения

- Неофициальная библиотека
- Веб-эндпоинты могут меняться без предупреждения
- Соблюдайте правила площадки

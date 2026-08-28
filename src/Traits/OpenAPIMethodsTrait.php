<?php

declare(strict_types=1);

namespace Kwork\Traits;

/**
 * Auto-generated wrappers for every endpoint from docs/openapi.json.
 *
 * @phpstan-type ApiResponse array<string, mixed>
 */
trait OpenAPIMethodsTrait
{

    /**
     * POST /acceptExtras - Принятие предложенных опции в треке покупателем - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function acceptExtras(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'acceptExtras', $useToken, $params);
    }

    /**
     * POST /acceptStageSuggestion - Принятие встречного предложения этапов - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function acceptStageSuggestion(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'acceptStageSuggestion', $useToken, $params);
    }

    /**
     * POST /actor - Данные авторизованного пользователя - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function actor(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('actor', $useToken, $body, $params);
    }

    /**
     * POST /addFavoriteCategories - Изменение списка любимых категорий пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function addFavoriteCategories(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'addFavoriteCategories', $useToken, $params);
    }

    /**
     * POST /addNewPhoneNumber - Проверка кода активации для добавления нового номера телефона на замену старому - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function addNewPhoneNumber(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'addNewPhoneNumber', $useToken, $params);
    }

    /**
     * POST /addPhoneNumber - Запрос кода для добавления номера телефона - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function addPhoneNumber(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'addPhoneNumber', $useToken, $params);
    }

    /**
     * POST /addStage - Создание и резервирование этапа в заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function addStage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'addStage', $useToken, $params);
    }

    /**
     * POST /allowInboxRequest - Разрешить/не разрешить переписку с пользователем - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function allowInboxRequest(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'allowInboxRequest', $useToken, $params);
    }

    /**
     * POST /allowMobilePush - Устанавливает/снимает флаг разрешения отправки пуша в мобильное приложение - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function allowMobilePush(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'allowMobilePush', $useToken, $params);
    }

    /**
     * POST /allowOrderPortfolioUpload - Разрешить продавцу публикацию работы в портфолио - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function allowOrderPortfolioUpload(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'allowOrderPortfolioUpload', $useToken, $params);
    }

    /**
     * POST /allowPushNotificationsSound - Устанавливать / снимать флаг разрешения воспроизведения звука при отображении пуша в мобильном приложении. - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function allowPushNotificationsSound(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'allowPushNotificationsSound', $useToken, $params);
    }

    /**
     * POST /appleSignIn - Аутентификация пользователя через Apple - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function appleSignIn(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('appleSignIn', $useToken, $body, $params);
    }

    /**
     * POST /applyFilters - Установить выбранные фильтры продавца на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function applyFilters(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'applyFilters', $useToken, $params);
    }

    /**
     * POST /approveOrder - Принятие заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function approveOrder(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'approveOrder', $useToken, $params);
    }

    /**
     * POST /approveOrderStage - Принятие этапов заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function approveOrderStage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'approveOrderStage', $useToken, $params);
    }

    /**
     * POST /archiveDialog - Отправить диалог в архив - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function archiveDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'archiveDialog', $useToken, $params);
    }

    /**
     * POST /blockDialog - Заблокировать диалог - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function blockDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'blockDialog', $useToken, $params);
    }

    /**
     * POST /blockedDialogList - Список заблокированных для диалога юзеров - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function blockedDialogList(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'blockedDialogList', $useToken, $params);
    }

    /**
     * POST /cancelOrderAwaitingPayment - Удалить неоплаченный заказ - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function cancelOrderAwaitingPayment(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'cancelOrderAwaitingPayment', $useToken, $params);
    }

    /**
     * POST /cancelOrderByPayer - Покупатель отменяет заказ - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function cancelOrderByPayer(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('cancelOrderByPayer', $useToken, $body, $params);
    }

    /**
     * POST /cancelOrderByWorker - Продавец отменяет заказ - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function cancelOrderByWorker(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('cancelOrderByWorker', $useToken, $body, $params);
    }

    /**
     * POST /catalogCategories - Получение списка подкатегорий - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function catalogCategories(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'catalogCategories', $useToken, $params);
    }

    /**
     * POST /catalogFilters - Список фильтров из текущего раздела каталога - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function catalogFilters(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'catalogFilters', $useToken, $params);
    }

    /**
     * POST /catalogMain - Получение основной информации по главной странице каталога - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function catalogMain(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'catalogMain', $useToken, $params);
    }

    /**
     * POST /catalogMainv2 - Получение основной информации по главной странице нового каталога - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function catalogMainv2(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'catalogMainv2', $useToken, $params);
    }

    /**
     * POST /catalogRubrics - Рубрики меню - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function catalogRubrics(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'catalogRubrics', $useToken, $params);
    }

    /**
     * POST /categories - Список категорий - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function categories(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'categories', $useToken, $params);
    }

    /**
     * POST /category - Получить данные о заданной категории - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function category(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'category', $useToken, $params);
    }

    /**
     * POST /categoryAttributes - Получить дерево всех атрибутов для заданной категории - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function categoryAttributes(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'categoryAttributes', $useToken, $params);
    }

    /**
     * POST /changePassword - Запрос изменения пароля пользователя, с отправкой посылка кода подтверждения на почту - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function changePassword(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('changePassword', $useToken, $body, $params);
    }

    /**
     * POST /changePayerSubRole - Сменить дочернюю роль покупателя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function changePayerSubRole(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'changePayerSubRole', $useToken, $params);
    }

    /**
     * POST /changeUsername - Запрос изменения логина пользователя - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function changeUsername(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('changeUsername', $useToken, $body, $params);
    }

    /**
     * POST /checkLogin - Запрос проверки занятости логина - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function checkLogin(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('checkLogin', $useToken, $body, $params);
    }

    /**
     * POST /checkUadDuplicate - Проверить дублирование идентификатора устройства UAD - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function checkUadDuplicate(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'checkUadDuplicate', $useToken, $params);
    }

    /**
     * POST /cities - Получение списка городов страны - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function cities(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'cities', $useToken, $params);
    }

    /**
     * POST /clearFilters - Сбросить выбранных фильтров продавца на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function clearFilters(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'clearFilters', $useToken, $params);
    }

    /**
     * POST /confirmCancelOrderRequestByPayer - Покупатель соглашается на обоюдную отмену заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function confirmCancelOrderRequestByPayer(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'confirmCancelOrderRequestByPayer', $useToken, $params);
    }

    /**
     * POST /confirmCancelOrderRequestByWorker - Продавец соглашается на обоюдную отмену заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function confirmCancelOrderRequestByWorker(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'confirmCancelOrderRequestByWorker', $useToken, $params);
    }

    /**
     * POST /countries - Получение списка стран - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function countries(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'countries', $useToken, $params);
    }

    /**
     * POST /createAnswer - Создать ответ на отзыв - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function createAnswer(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('createAnswer', $useToken, $body, $params);
    }

    /**
     * POST /createKworkComplain - Создание жалобы на кворк - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function createKworkComplain(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('createKworkComplain', $useToken, $body, $params);
    }

    /**
     * POST /createPortfolio - Добавить портфолио - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function createPortfolio(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'createPortfolio', $useToken, $params);
    }

    /**
     * POST /createReview - Создать отзыв - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function createReview(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('createReview', $useToken, $body, $params);
    }

    /**
     * POST /createStage - Добавление этапа в заказ - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function createStage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'createStage', $useToken, $params);
    }

    /**
     * POST /delFavoriteCategories - Удаление любимых категорий пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function delFavoriteCategories(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'delFavoriteCategories', $useToken, $params);
    }

    /**
     * POST /deleteAccount - Удаление своего аккаунта - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteAccount(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteAccount', $useToken, $params);
    }

    /**
     * POST /deleteCancelOrderRequestByPayer - Покупатель удалил свой запрос на обоюдную отмену заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteCancelOrderRequestByPayer(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteCancelOrderRequestByPayer', $useToken, $params);
    }

    /**
     * POST /deleteCancelOrderRequestByWorker - Продавец удалил свой запрос на обоюдную отмену заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteCancelOrderRequestByWorker(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteCancelOrderRequestByWorker', $useToken, $params);
    }

    /**
     * POST /deleteCover - Удаление обложки пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteCover(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteCover', $useToken, $params);
    }

    /**
     * POST /deleteKwork - Удаление своего кворка - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteKwork(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteKwork', $useToken, $params);
    }

    /**
     * POST /deleteOffer - Удаляет предложение к запросу на услугу на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteOffer(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteOffer', $useToken, $params);
    }

    /**
     * POST /deleteOrderNote - Удалить заметку о заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteOrderNote(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteOrderNote', $useToken, $params);
    }

    /**
     * POST /deletePortfolio - Удаление портфолио - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deletePortfolio(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deletePortfolio', $useToken, $params);
    }

    /**
     * POST /deleteReview - Удаление отзыва - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteReview(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteReview', $useToken, $params);
    }

    /**
     * POST /deleteStage - Удаление этапа из заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteStage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteStage', $useToken, $params);
    }

    /**
     * POST /deleteUserNote - Удалить заметку о пользователе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteUserNote(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteUserNote', $useToken, $params);
    }

    /**
     * POST /deleteWant - Удаляет запрос на услугу на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function deleteWant(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'deleteWant', $useToken, $params);
    }

    /**
     * POST /dialogs - Список диалогов - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function dialogs(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'dialogs', $useToken, $params);
    }

    /**
     * POST /editAnswer - Редактировать отзыв - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function editAnswer(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('editAnswer', $useToken, $body, $params);
    }

    /**
     * POST /editPortfolio - Редактировать портфолио - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function editPortfolio(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'editPortfolio', $useToken, $params);
    }

    /**
     * POST /editReview - Редактировать отзыв - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function editReview(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('editReview', $useToken, $body, $params);
    }

    /**
     * POST /editStage - Редактирование этапов в заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function editStage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'editStage', $useToken, $params);
    }

    /**
     * POST /emailVerificationLetter - Запрос отправки письма подтверждения email - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function emailVerificationLetter(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'emailVerificationLetter', $useToken, $params);
    }

    /**
     * POST /exchangeInfo - Ключевая информации по бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function exchangeInfo(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'exchangeInfo', $useToken, $params);
    }

    /**
     * POST /favoriteCategories - Получение списка любимых рубрик пользователя. - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function favoriteCategories(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'favoriteCategories', $useToken, $params);
    }

    /**
     * POST /favoriteKworks - Список избранных кворков - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function favoriteKworks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'favoriteKworks', $useToken, $params);
    }

    /**
     * POST /fcmNotificationsRead - Пометка сообщений FCM прочитанными в МП - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function fcmNotificationsRead(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'fcmNotificationsRead', $useToken, $params);
    }

    /**
     * POST /fcmNotificationsReceived - Пометка сообщений FCM полученными в МП - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function fcmNotificationsReceived(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'fcmNotificationsReceived', $useToken, $params);
    }

    /**
     * POST /fcmTokenRequestFailed - Регистрация токена Firebase Cloud Messaging - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function fcmTokenRequestFailed(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'fcmTokenRequestFailed', $useToken, $params);
    }

    /**
     * POST /fileDelete - Удаление загруженного файла - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function fileDelete(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'fileDelete', $useToken, $params);
    }

    /**
     * POST /fileUpload - Загрузка файла из FILES["upload_files"] - content: multipart - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function fileUpload(bool $useToken = false, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('fileUpload', $useToken, $fields, $files, $params);
    }

    /**
     * POST /getActorInfo - Информация о текущем залогиненном пользователе - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function getActorInfo(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('getActorInfo', $useToken, $body, $params);
    }

    /**
     * POST /getArbitrationReasons - Получить список причин перевода в арбитраж - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getArbitrationReasons(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getArbitrationReasons', $useToken, $params);
    }

    /**
     * POST /getAvailableFeatures - Информация о доступных функциях - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getAvailableFeatures(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getAvailableFeatures', $useToken, $params);
    }

    /**
     * POST /getBadgesInfo - Плучение информации для бейджей о количестве важных уведомлений - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function getBadgesInfo(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('getBadgesInfo', $useToken, $body, $params);
    }

    /**
     * POST /getBillRefillUrl - Получить ссылку для Yescrow платежа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getBillRefillUrl(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getBillRefillUrl', $useToken, $params);
    }

    /**
     * POST /getCaptchaStatus - Требуется ли показать капчу при запросе сброса пароля - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getCaptchaStatus(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getCaptchaStatus', $useToken, $params);
    }

    /**
     * POST /getChannel - Получить идентификатор socket-канала текущего пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getChannelApi(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getChannel', $useToken, $params);
    }

    /**
     * POST /getCompanyDetails - Получение деталей о компании по ИНН - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getCompanyDetails(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getCompanyDetails', $useToken, $params);
    }

    /**
     * POST /getComplainCategories - Получение списка жалоб - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getComplainCategories(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getComplainCategories', $useToken, $params);
    }

    /**
     * POST /getCookie - Получение куки для авторизованных по токену - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getCookie(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getCookie', $useToken, $params);
    }

    /**
     * POST /getCurrentVersions - Возвращает список текущих версий мобильных приложений - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getCurrentVersions(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getCurrentVersions', $useToken, $params);
    }

    /**
     * POST /getCustomOptionsPresets - Получить спиок цен для кастомных опций и добавляемого срока - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getCustomOptionsPresets(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getCustomOptionsPresets', $useToken, $params);
    }

    /**
     * POST /getDialog - Получить диалог по идентификатору собеседника - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getDialog', $useToken, $params);
    }

    /**
     * POST /getExtrasAvailableForOrder - Получить опции, доступные для добавления в заказ - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getExtrasAvailableForOrder(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getExtrasAvailableForOrder', $useToken, $params);
    }

    /**
     * POST /getFishingTutorialQuestions - Список вопросов для опроса о мошенниках (код 116) - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getFishingTutorialQuestions(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getFishingTutorialQuestions', $useToken, $params);
    }

    /**
     * POST /getHiddenKworks - Получить список скрытых пользователем кворков - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getHiddenKworks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getHiddenKworks', $useToken, $params);
    }

    /**
     * POST /getInAppNotification - Получение In-app уведомлений - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getInAppNotification(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getInAppNotification', $useToken, $params);
    }

    /**
     * POST /getInboxTracks - Список сообщений (c треками) в диалоге - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getInboxTracks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getInboxTracks', $useToken, $params);
    }

    /**
     * POST /getKworkAnswers - Получение FAQ по кворку - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkAnswers(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkAnswers', $useToken, $params);
    }

    /**
     * POST /getKworkDetails - Получение данных о кворке - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkDetails(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkDetails', $useToken, $params);
    }

    /**
     * POST /getKworkDetailsExtra - Получение данных о кворке - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkDetailsExtra(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkDetailsExtra', $useToken, $params);
    }

    /**
     * POST /getKworkLinksTable - Получить данные по ссылкам кворка - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkLinksTable(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkLinksTable', $useToken, $params);
    }

    /**
     * POST /getKworkLinksTablev2 - Получить данные по ссылкам кворка - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkLinksTablev2(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkLinksTablev2', $useToken, $params);
    }

    /**
     * POST /getKworkPortfolios - Получить работы портфолио для кворка - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkPortfolios(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkPortfolios', $useToken, $params);
    }

    /**
     * POST /getKworkReviews - Получить отзывы для кворка - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getKworkReviews(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getKworkReviews', $useToken, $params);
    }

    /**
     * POST /getOrderCancellationReasons - Получить список причин отмены заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getOrderCancellationReasons(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getOrderCancellationReasons', $useToken, $params);
    }

    /**
     * POST /getOrderDetails - Получение детальной информации о заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getOrderDetails(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getOrderDetails', $useToken, $params);
    }

    /**
     * POST /getOrderFiles - Получить список файлов заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getOrderFiles(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getOrderFiles', $useToken, $params);
    }

    /**
     * POST /getOrderHeader - Обновление блоков кеша шапки заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getOrderHeader(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getOrderHeader', $useToken, $params);
    }

    /**
     * POST /getOrderProvidedData - Предоставленные данные по заказу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getOrderProvidedData(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getOrderProvidedData', $useToken, $params);
    }

    /**
     * POST /getOrderedExtras - Получение дополнительных опций, которые уже добавлены в заказ - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getOrderedExtras(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getOrderedExtras', $useToken, $params);
    }

    /**
     * GET /getPayerCompanyModalUrl - Получение ссылки на страницу покупателя с открытой модалкой регистрации Компании - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getPayerCompanyModalUrl(bool $useToken = false, array $params = []): array
    {
        return $this->request('get', 'getPayerCompanyModalUrl', $useToken, $params);
    }

    /**
     * POST /getPaymentMethods - Получение способов оплаты и информации о сервисном сборе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getPaymentMethods(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getPaymentMethods', $useToken, $params);
    }

    /**
     * POST /getSecurityUserData - Получения данных для экрана "Безопасность" - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getSecurityUserData(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getSecurityUserData', $useToken, $params);
    }

    /**
     * POST /getSubscribersStatistics - Данные о динамике подписчиков на канале - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getSubscribersStatistics(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getSubscribersStatistics', $useToken, $params);
    }

    /**
     * POST /getTracks - Возвращает информацию о треках заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getTracks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getTracks', $useToken, $params);
    }

    /**
     * POST /getUserInfo - Получение информации о пользователе - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getUserInfo(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getUserInfo', $useToken, $params);
    }

    /**
     * POST /getUsersLastOrderInfo - Возвращает информацию по последнему выполненному заказу между пользователем и собеседником, в котором было хотя бы одно сообщение - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getUsersLastOrderInfo(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getUsersLastOrderInfo', $useToken, $params);
    }

    /**
     * POST /getVoiceMessageConvertStatus - Получить статус конвертации голосового сообщения - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getVoiceMessageConvertStatus(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getVoiceMessageConvertStatus', $useToken, $params);
    }

    /**
     * POST /getVoiceMessageTranscription - Получить транскрипцию голосового сообщения - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getVoiceMessageTranscription(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getVoiceMessageTranscription', $useToken, $params);
    }

    /**
     * POST /getWantsCount - Возвращает количество проектов по заданным фильтрам - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getWantsCount(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getWantsCount', $useToken, $params);
    }

    /**
     * POST /getWebAuthToken - Получить токен входа в веб версию - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function getWebAuthToken(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'getWebAuthToken', $useToken, $params);
    }

    /**
     * POST /hideDialog - Скрыть/удалить диалог - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function hideDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'hideDialog', $useToken, $params);
    }

    /**
     * POST /hideSelfEmployedNotification - Скрыть уведомление об успешной регистрации СЗ/ИП - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function hideSelfEmployedNotification(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'hideSelfEmployedNotification', $useToken, $params);
    }

    /**
     * POST /hideVoiceMessageSettingsPopup - Отправка на бэк факта показа попапа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function hideVoiceMessageSettingsPopup(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'hideVoiceMessageSettingsPopup', $useToken, $params);
    }

    /**
     * POST /inboxComplainMessage - Пожаловаться на сообщение пользователя - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function inboxComplainMessage(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('inboxComplainMessage', $useToken, $body, $params);
    }

    /**
     * POST /inboxCreate - Создание нового сообщения - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function inboxCreate(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('inboxCreate', $useToken, $body, $params);
    }

    /**
     * POST /inboxCustomRequestDecline - Отмена индивидуального запроса - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxCustomRequestDecline(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxCustomRequestDecline', $useToken, $params);
    }

    /**
     * POST /inboxDelete - Удаление сообщения - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxDelete(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxDelete', $useToken, $params);
    }

    /**
     * POST /inboxEdit - Редактирование сообщения - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function inboxEdit(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('inboxEdit', $useToken, $body, $params);
    }

    /**
     * POST /inboxForward - Пересылка сообщения - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxForward(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxForward', $useToken, $params);
    }

    /**
     * POST /inboxMessage - Получить сообщение Inbox по id - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxMessage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxMessage', $useToken, $params);
    }

    /**
     * POST /inboxPayerDecline - Отмена предложенного кворка в личке покупателем - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxPayerDecline(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxPayerDecline', $useToken, $params);
    }

    /**
     * POST /inboxRead - Пометить прочитанным сообщения или диалог - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxRead(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxRead', $useToken, $params);
    }

    /**
     * POST /inboxTrackMessage - Получить сообщение Inbox/Track по conversationId - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxTrackMessage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxTrackMessage', $useToken, $params);
    }

    /**
     * POST /inboxWorkerDecline - Отмена предложенного кворка в личке продавцом - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxWorkerDecline(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxWorkerDecline', $useToken, $params);
    }

    /**
     * POST /inboxes - Список сообщений в диалоге - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function inboxes(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'inboxes', $useToken, $params);
    }

    /**
     * POST /isDialogAllow - Разрешен ли диалог с пользователем - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function isDialogAllow(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'isDialogAllow', $useToken, $params);
    }

    /**
     * POST /kworks - Список кворков для категории - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function kworks(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'kworks', $useToken, $params);
    }

    /**
     * POST /kworksCategoriesList - Получение вкладок пользователя с категориями кворков - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function kworksCategoriesList(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'kworksCategoriesList', $useToken, $params);
    }

    /**
     * POST /kworksStatusList - Получение статусов (вкладок) кворков, и первую страницу самих кворков для каждого статуса - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function kworksStatusList(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'kworksStatusList', $useToken, $params);
    }

    /**
     * POST /logout - Выход пользователя: удаление указанного пуш токена, удаление токена авторизации, закрытие текущей сессии - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function logout(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'logout', $useToken, $params);
    }

    /**
     * POST /markInboxTracksAsRead - Пометить переписку пользователя прочитанной - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function markInboxTracksAsRead(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'markInboxTracksAsRead', $useToken, $params);
    }

    /**
     * POST /markKworkAsFavorite - Добавление кворка в избранные или удаление из избранных - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function markKworkAsFavorite(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'markKworkAsFavorite', $useToken, $params);
    }

    /**
     * POST /markKworkAsHidden - Скрытие/отображение кворка - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function markKworkAsHidden(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'markKworkAsHidden', $useToken, $params);
    }

    /**
     * POST /markKworksBlackFriday - Отмечает кворк участвующим в акции Черная пятница - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function markKworksBlackFriday(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'markKworksBlackFriday', $useToken, $params);
    }

    /**
     * POST /markVoiceMessageHeard - Отправить флаг "Пользователь прослушал голосовое сообщение" - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function markVoiceMessageHeard(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'markVoiceMessageHeard', $useToken, $params);
    }

    /**
     * POST /miniature - Получить миниатюру к файлу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function miniature(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'miniature', $useToken, $params);
    }

    /**
     * POST /myWants - Возвращает список запросов на услугу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function myWants(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'myWants', $useToken, $params);
    }

    /**
     * POST /notifications - Список уведомлений пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function notifications(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'notifications', $useToken, $params);
    }

    /**
     * POST /notificationsFetch - Получение непрочитанных Push-событий из очереди - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function notificationsFetch(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'notificationsFetch', $useToken, $params);
    }

    /**
     * POST /notificationsReceived - Пометка сквозных Push-событий прочтенными - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function notificationsReceived(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'notificationsReceived', $useToken, $params);
    }

    /**
     * POST /offer - Получает данные предложения к запросу на услугу на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function offer(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'offer', $useToken, $params);
    }

    /**
     * POST /offerOrderOptions - Добавить предложение опций к заказу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function offerOrderOptions(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'offerOrderOptions', $useToken, $params);
    }

    /**
     * POST /offers - Предложения пользователя на запросы услуг на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function offers(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'offers', $useToken, $params);
    }

    /**
     * POST /offline - Установить статус offline - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function offline(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'offline', $useToken, $params);
    }

    /**
     * POST /order - Информация о заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function order(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'order', $useToken, $params);
    }

    /**
     * POST /orderKwork - Создать заказ по кворку. - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function orderKwork(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'orderKwork', $useToken, $params);
    }

    /**
     * POST /orderStage - Зарезервировать этапы - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function orderStage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'orderStage', $useToken, $params);
    }

    /**
     * POST /ordersBetween - Список активных заказов между текущим пользователем и указанным - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function ordersBetween(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'ordersBetween', $useToken, $params);
    }

    /**
     * POST /pauseKwork - Останавливает кворк - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function pauseKwork(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'pauseKwork', $useToken, $params);
    }

    /**
     * POST /payOrderAwaitingPayment - Оплата заказа в статусе "Ожидает оплаты" - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payOrderAwaitingPayment(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payOrderAwaitingPayment', $useToken, $params);
    }

    /**
     * POST /payerBuyExtras - Добавление опции в заказ покупателем - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payerBuyExtras(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payerBuyExtras', $useToken, $params);
    }

    /**
     * POST /payerDeclineExtras - Покупатель отклоняет предложенные опции - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payerDeclineExtras(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payerDeclineExtras', $useToken, $params);
    }

    /**
     * POST /payerDeclinesExtraRemovalRequest - Покупатель отклоняет запрос на удаление опции из заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payerDeclinesExtraRemovalRequest(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payerDeclinesExtraRemovalRequest', $useToken, $params);
    }

    /**
     * POST /payerExtraDelete - Удалить опцию из заказа, для покупателя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payerExtraDelete(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payerExtraDelete', $useToken, $params);
    }

    /**
     * POST /payerOrders - Список заказов покупателя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payerOrders(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payerOrders', $useToken, $params);
    }

    /**
     * POST /payerUpgradePackage - Апгрейд пакета покупателем - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function payerUpgradePackage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'payerUpgradePackage', $useToken, $params);
    }

    /**
     * POST /portfolioCategoriesList - Получение категорий работ, и первой страницы самих работ для каждой категории - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function portfolioCategoriesList(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'portfolioCategoriesList', $useToken, $params);
    }

    /**
     * POST /portfolioList - Получить портфолио пользователя - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function portfolioList(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'portfolioList', $useToken, $params);
    }

    /**
     * POST /positiveReviewsCount - Получить массив количества отзывов по атрибуту - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function positiveReviewsCount(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'positiveReviewsCount', $useToken, $params);
    }

    /**
     * POST /privacy - Вывод политики конфиденциальности - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function privacy(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'privacy', $useToken, $params);
    }

    /**
     * POST /project - Возвращает проект по идентификатору - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function project(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'project', $useToken, $params);
    }

    /**
     * POST /projects - Возвращает список проектов по заданным фильтрам - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function projects(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'projects', $useToken, $params);
    }

    /**
     * POST /pushInAppNotificationLog - Запись лога показа in-app уведомления - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function pushInAppNotificationLog(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'pushInAppNotificationLog', $useToken, $params);
    }

    /**
     * POST /rateArbitration - Выставление оценки за арбитраж - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function rateArbitration(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'rateArbitration', $useToken, $params);
    }

    /**
     * POST /rechargeBalance - Получить ссылку для пополнения баланса в профиле и для создания заказа. - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function rechargeBalance(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'rechargeBalance', $useToken, $params);
    }

    /**
     * POST /registerCloudToken - Регистрация токена Firebase Cloud Messaging - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function registerCloudToken(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'registerCloudToken', $useToken, $params);
    }

    /**
     * POST /rejectCancelOrderRequestByPayer - Покупатель не согласился на обоюдную отмену заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function rejectCancelOrderRequestByPayer(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'rejectCancelOrderRequestByPayer', $useToken, $params);
    }

    /**
     * POST /rejectCancelOrderRequestByWorker - Продавец не согласился на обоюдную отмену заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function rejectCancelOrderRequestByWorker(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'rejectCancelOrderRequestByWorker', $useToken, $params);
    }

    /**
     * POST /rejectStageSuggestion - Отмена встречного предложения этапов - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function rejectStageSuggestion(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'rejectStageSuggestion', $useToken, $params);
    }

    /**
     * POST /repeatOrder - Создать заказ заново - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function repeatOrder(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'repeatOrder', $useToken, $params);
    }

    /**
     * POST /replaceUad - Заменить идентификатор устройства UAD - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function replaceUad(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'replaceUad', $useToken, $params);
    }

    /**
     * POST /reportAppVersion - Обновление версии мобильного приложения пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function reportAppVersion(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'reportAppVersion', $useToken, $params);
    }

    /**
     * POST /requestPhoneChanging - Запрос кода для смены номера телефона - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function requestPhoneChanging(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'requestPhoneChanging', $useToken, $params);
    }

    /**
     * POST /resetPassword - Отправка письма для сброса пароля - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function resetPassword(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('resetPassword', $useToken, $body, $params);
    }

    /**
     * POST /resolution - Вывод политики разрешения споров - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function resolution(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'resolution', $useToken, $params);
    }

    /**
     * POST /restartWant - Перезапускает запрос на услугу на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function restartWant(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'restartWant', $useToken, $params);
    }

    /**
     * POST /saveOrderNote - Создать/редактировать заметку о заказе - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function saveOrderNote(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('saveOrderNote', $useToken, $body, $params);
    }

    /**
     * POST /saveUserNote - Создать/редактировать заметку о пользователе - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function saveUserNote(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('saveUserNote', $useToken, $body, $params);
    }

    /**
     * POST /search - Поиск кворков - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function search(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'search', $useToken, $params);
    }

    /**
     * POST /searchDialogs - Поиск диалогов - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function searchDialogs(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'searchDialogs', $useToken, $params);
    }

    /**
     * POST /searchInboxes - Поиск сообщенений в диалогах пользователей - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function searchInboxes(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'searchInboxes', $useToken, $params);
    }

    /**
     * POST /searchKworksCatalogQuery - Получение ключевых слов по частичной фразе - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function searchKworksCatalogQuery(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'searchKworksCatalogQuery', $useToken, $params);
    }

    /**
     * POST /searchMessages - Поиск сообщенений в диалогах пользователей с указанием найденного сниппета - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function searchMessages(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'searchMessages', $useToken, $params);
    }

    /**
     * POST /searchOrderTracks - Поиск текстовых треков в заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function searchOrderTracks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'searchOrderTracks', $useToken, $params);
    }

    /**
     * POST /searchTracks - Поиск текстовых треков в заказе - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function searchTracks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'searchTracks', $useToken, $params);
    }

    /**
     * POST /sendBonus - Отправить бонус продавцу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendBonus(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendBonus', $useToken, $params);
    }

    /**
     * POST /sendCompanyForVerification - Отправка компании на проверку - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendCompanyForVerification(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendCompanyForVerification', $useToken, $params);
    }

    /**
     * POST /sendOrderForApproval - Отправка заказа на проверку - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendOrderForApproval(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendOrderForApproval', $useToken, $params);
    }

    /**
     * POST /sendOrderForArbitration - Отправить заказ на арбитраж - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendOrderForArbitration(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendOrderForArbitration', $useToken, $params);
    }

    /**
     * POST /sendOrderForRevision - Отправить заказ на доработку - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendOrderForRevision(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendOrderForRevision', $useToken, $params);
    }

    /**
     * POST /sendOrderReceiptLinkForVerification - Отправка ссылки на чек для проверки - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendOrderReceiptLinkForVerification(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendOrderReceiptLinkForVerification', $useToken, $params);
    }

    /**
     * POST /sendOrderRequirements - Отправка информации по заказу продавцу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendOrderRequirements(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendOrderRequirements', $useToken, $params);
    }

    /**
     * POST /sendReport - Отправить отчет по заказу (не этапному) - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendReport(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendReport', $useToken, $params);
    }

    /**
     * POST /sendSelfEmployedSurveyResult - Отправить ответ на опрос - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendSelfEmployedSurveyResult(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendSelfEmployedSurveyResult', $useToken, $params);
    }

    /**
     * POST /sendUserStatus - Отправить флаг "Статус пользователя" - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendUserStatus(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendUserStatus', $useToken, $params);
    }

    /**
     * POST /sendWhatsAppCode - Отправить код верификации через WhatsApp - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function sendWhatsAppCode(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'sendWhatsAppCode', $useToken, $params);
    }

    /**
     * POST /setAvailableAtWeekends - Изменение доступности кворков на выходных - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setAvailableAtWeekends(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setAvailableAtWeekends', $useToken, $params);
    }

    /**
     * POST /setDialogStarred - Пометить диалог избранным - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setDialogStarred(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setDialogStarred', $useToken, $params);
    }

    /**
     * POST /setFavorite - Изменение списка любимых категорий пользователя, объединяет функционал add и delete - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setFavorite(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setFavorite', $useToken, $params);
    }

    /**
     * POST /setFishingTutorialStatus - Установка статуса о прохождении опроса о мошенниках - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setFishingTutorialStatus(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setFishingTutorialStatus', $useToken, $params);
    }

    /**
     * POST /setOrderRating - Оценить продавца - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setOrderRating(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setOrderRating', $useToken, $params);
    }

    /**
     * POST /setTakingOrders - Сохранение настройки пользователя по доступности его кворков для заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setTakingOrders(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setTakingOrders', $useToken, $params);
    }

    /**
     * POST /setUserType - Установка типа пользователя (покупатель/продавец) - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setUserType(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setUserType', $useToken, $params);
    }

    /**
     * POST /setVoiceMessageReceiving - Разрешить/запретить принимать голосовые сообщения - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setVoiceMessageReceiving(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setVoiceMessageReceiving', $useToken, $params);
    }

    /**
     * POST /setVoiceMessageSpeed - Изменение скорости воспроизведения голосовых сообщений - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function setVoiceMessageSpeed(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'setVoiceMessageSpeed', $useToken, $params);
    }

    /**
     * POST /signIn - Аутентификация пользователя с выдачей токена - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function signIn(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('signIn', $useToken, $body, $params);
    }

    /**
     * POST /signUp - Регистрация пользователя с выдачей токена - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function signUp(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('signUp', $useToken, $body, $params);
    }

    /**
     * POST /socialSignIn - Аутентификация пользователя через социальные сети по коду провайдера - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function socialSignIn(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('socialSignIn', $useToken, $body, $params);
    }

    /**
     * POST /socialSignInByToken - Аутентификация пользователя через токен социальной сети (при нативной авторизации моб. приложений)
     * Аутентификация + регистрация
     * (Устаревший метод, вскоре должен быть удален) - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function socialSignInByToken(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('socialSignInByToken', $useToken, $body, $params);
    }

    /**
     * POST /socialSignInByTokenv2 - Аутентификация пользователя через токен социальной сети (при нативной авторизации моб. приложений) - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function socialSignInByTokenv2(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('socialSignInByTokenv2', $useToken, $body, $params);
    }

    /**
     * POST /socialSignUp - Регистрация через социальные сети с указанием email (привязка социального аккаунта если такой email уже есть) - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function socialSignUp(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('socialSignUp', $useToken, $body, $params);
    }

    /**
     * POST /socialSignUpByToken - Регистрация через социальные сети по токену с указанием email (привязка социального аккаунта если такой email уже есть) - content: form - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function socialSignUpByToken(bool $useToken = false, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('socialSignUpByToken', $useToken, $body, $params);
    }

    /**
     * POST /startKwork - Активирует(запускает) кворк - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function startKwork(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'startKwork', $useToken, $params);
    }

    /**
     * POST /stopWant - Останавливает запрос на услугу на бирже - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function stopWant(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'stopWant', $useToken, $params);
    }

    /**
     * POST /suggestStages - Встречное предложение этапов - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function suggestStages(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'suggestStages', $useToken, $params);
    }

    /**
     * POST /terms - Вывод договора-оферты - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function terms(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'terms', $useToken, $params);
    }

    /**
     * POST /termsOfService - Вывод правил сайта - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function termsOfService(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'termsOfService', $useToken, $params);
    }

    /**
     * POST /timezones - Получение списка временных зон - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function timezones(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'timezones', $useToken, $params);
    }

    /**
     * POST /trackDelete - Удаление трека - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function trackDelete(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'trackDelete', $useToken, $params);
    }

    /**
     * POST /trackEdit - Редактирование трека - content: form - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function trackEdit(bool $useToken = true, ?array $body = null, array $params = []): array
    {
        return $this->requestWithBody('trackEdit', $useToken, $body, $params);
    }

    /**
     * POST /trackMessage - Получить сообщение трека - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function trackMessage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'trackMessage', $useToken, $params);
    }

    /**
     * POST /trackRead - Пометить указанные треки прочитанными - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function trackRead(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'trackRead', $useToken, $params);
    }

    /**
     * POST /translationLanguages - Получить массив всех доступных в системе языков для переводов с падежами - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function translationLanguages(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'translationLanguages', $useToken, $params);
    }

    /**
     * POST /typing - Отправить флаг "Юзер печатает" - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function typing(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'typing', $useToken, $params);
    }

    /**
     * POST /unarchiveDialog - Вернуть диалог из архива - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function unarchiveDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'unarchiveDialog', $useToken, $params);
    }

    /**
     * POST /unblockDialog - Разблокировать диалог - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function unblockDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'unblockDialog', $useToken, $params);
    }

    /**
     * POST /unreadDialog - Пометить диалог с заданным пользователем непрочитанным - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function unreadDialog(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'unreadDialog', $useToken, $params);
    }

    /**
     * POST /updateAvatar - Смена аватара пользователя - content: multipart - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function updateAvatar(bool $useToken = true, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('updateAvatar', $useToken, $fields, $files, $params);
    }

    /**
     * POST /updateChatDraftMessage - Обновление черновика - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function updateChatDraftMessage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'updateChatDraftMessage', $useToken, $params);
    }

    /**
     * POST /updateOrderDraftMessage - Обновление черновика - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function updateOrderDraftMessage(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'updateOrderDraftMessage', $useToken, $params);
    }

    /**
     * POST /updateSettings - Редактирование настроек пользователя - content: multipart - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function updateSettings(bool $useToken = true, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('updateSettings', $useToken, $fields, $files, $params);
    }

    /**
     * POST /updateStageProgress - Обновить прогресс по задаче - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function updateStageProgress(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'updateStageProgress', $useToken, $params);
    }

    /**
     * POST /uploadCover - Загрузка обложки пользователя - content: multipart - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function uploadCover(bool $useToken = true, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('uploadCover', $useToken, $fields, $files, $params);
    }

    /**
     * POST /uploadLog - Загрузка лога мобильного приложения - content: multipart - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function uploadLog(bool $useToken = false, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('uploadLog', $useToken, $fields, $files, $params);
    }

    /**
     * POST /uploadPortfolioFile - Загрузка файла из FILES["file"] - content: multipart - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function uploadPortfolioFile(bool $useToken = false, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('uploadPortfolioFile', $useToken, $fields, $files, $params);
    }

    /**
     * POST /uploadedFile - Получение загруженного файла - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function uploadedFile(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'uploadedFile', $useToken, $params);
    }

    /**
     * POST /user - Данные пользователя по идентификатору - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function user(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'user', $useToken, $params);
    }

    /**
     * POST /userByUsername - Получение данных пользователя по username - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function userByUsername(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'userByUsername', $useToken, $params);
    }

    /**
     * POST /userKworks - Список кворков пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function userKworks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'userKworks', $useToken, $params);
    }

    /**
     * POST /userReviews - Список отзывов для пользователя - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function userReviews(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'userReviews', $useToken, $params);
    }

    /**
     * POST /userSearch - Поиск пользователей - auth: basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function userSearch(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'userSearch', $useToken, $params);
    }

    /**
     * POST /verifyPhoneActivationCode - Проверка кода активации номера телефона - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function verifyPhoneActivationCode(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'verifyPhoneActivationCode', $useToken, $params);
    }

    /**
     * POST /verifySmsCodeForAccountDeleting - Проверка кода удаления аккаунта - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function verifySmsCodeForAccountDeleting(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'verifySmsCodeForAccountDeleting', $useToken, $params);
    }

    /**
     * POST /viewedCatalogKworks - Список просмотренных кворков - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function viewedCatalogKworks(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'viewedCatalogKworks', $useToken, $params);
    }

    /**
     * POST /voiceUpload - Загрузка файла из FILES["upload_files"] - content: multipart - auth: basic
     *
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function voiceUpload(bool $useToken = false, ?array $fields = null, ?array $files = null, array $params = []): array
    {
        return $this->requestMultipart('voiceUpload', $useToken, $fields, $files, $params);
    }

    /**
     * POST /want - Возвращает данные по запросу на услугу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function want(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'want', $useToken, $params);
    }

    /**
     * POST /wantsStatusList - Список запросов на услугу на бирже, сгруппированных по альтернативному статусу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function wantsStatusList(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'wantsStatusList', $useToken, $params);
    }

    /**
     * POST /workerConfirmsExtraRemovalRequest - Продавец подтверждает запрос на удаление опции - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function workerConfirmsExtraRemovalRequest(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'workerConfirmsExtraRemovalRequest', $useToken, $params);
    }

    /**
     * POST /workerDeclineExtras - Продавец отклоняет предложенные опции - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function workerDeclineExtras(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'workerDeclineExtras', $useToken, $params);
    }

    /**
     * POST /workerDeclinesExtraRemovalRequest - Продавец отклоняет запрос на удаление опции из заказа - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function workerDeclinesExtraRemovalRequest(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'workerDeclinesExtraRemovalRequest', $useToken, $params);
    }

    /**
     * POST /workerExtraDelete - Удалить опцию из заказа, для продавца - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function workerExtraDelete(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'workerExtraDelete', $useToken, $params);
    }

    /**
     * POST /workerInprogress - Продавец взял заказ в работу - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function workerInprogress(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'workerInprogress', $useToken, $params);
    }

    /**
     * POST /workerOrders - Список заказов пользователя, которые он должен выполнить - auth: token+basic
     *
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function workerOrders(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'workerOrders', $useToken, $params);
    }
}

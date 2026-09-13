<?php

declare(strict_types=1);

namespace Kwork\Async;

use GuzzleHttp\Promise\PromiseInterface;
use Kwork\Async\AsyncKworkWebClient;
use Kwork\Contract\DialogLookupInterface;
use Kwork\KworkAPI;
use Kwork\Proxy\ProxyConfig;
use Kwork\Schema\Actor;
use Kwork\Schema\Connects;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\InboxMessage;
use Kwork\Schema\ParentCategory;
use Kwork\Schema\User;
use Kwork\Schema\WantWorker;
use Kwork\Traits\AsyncAPKExtraMethodsTrait;
use Kwork\Traits\AsyncOpenAPIMethodsTrait;
use Kwork\WebLoginResult;

/**
 * High-level async typed client for kwork.ru mobile API.
 */
class AsyncKworkClient extends AsyncKworkAPI implements DialogLookupInterface
{
    use AsyncOpenAPIMethodsTrait;
    use AsyncAPKExtraMethodsTrait;

    private ?AsyncKworkWebClient $webClient = null;

    public function __construct(
        string $login,
        string $password,
        string|ProxyConfig|null $proxy = null,
        ?string $phoneLast = null,
        string $apiHost = KworkAPI::API_HOST,
        ?float $timeout = 30.0,
        int $retryMaxAttempts = 1,
        float $retryBackoffBase = 0.5,
        float $retryBackoffMax = 8.0,
        float $retryJitter = 0.1,
        ?array $retryStatuses = null,
        bool $reloginOnAuthError = false,
        ?\GuzzleHttp\Client $httpClient = null,
    ) {
        parent::__construct(
            $login,
            $password,
            $proxy,
            $phoneLast,
            $apiHost,
            $timeout,
            $retryMaxAttempts,
            $retryBackoffBase,
            $retryBackoffMax,
            $retryJitter,
            $retryStatuses,
            $reloginOnAuthError,
            $httpClient,
        );
    }

    public function web(): AsyncKworkWebClient
    {
        if ($this->webClient === null) {
            $this->webClient = new AsyncKworkWebClient($this);
        }

        return $this->webClient;
    }

    /**
     * @return PromiseInterface<WebLoginResult>
     */
    public function webLoginAsync(
        ?string $urlToRedirect = '/',
        ?string $userAgent = null,
    ): PromiseInterface {
        return $this->web()->loginViaMobileWebAuthTokenAsync(
            urlToRedirect: $urlToRedirect,
            userAgent: $userAgent,
        );
    }

    /**
     * @return PromiseInterface<Actor>
     */
    public function getMeAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'actor', true)->then(
            static fn (array $data): Actor => Actor::fromArray($data['response']),
        );
    }

    public function getMe(): Actor
    {
        return $this->getMeAsync()->wait();
    }

    /**
     * @return PromiseInterface<User>
     */
    public function getUserAsync(int $userId): PromiseInterface
    {
        return $this->requestAsync('post', 'user', false, ['id' => $userId])->then(
            static fn (array $data): User => User::fromArray($data['response']),
        );
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function setTypingAsync(int $recipientId): PromiseInterface
    {
        return $this->requestAsync('post', 'typing', true, ['recipientId' => $recipientId]);
    }

    public function setTyping(int $recipientId): array
    {
        return $this->setTypingAsync($recipientId)->wait();
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function setOfflineAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'offline', true);
    }

    /**
     * @return PromiseInterface<string>
     */
    public function getChannelAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'getChannel', true)->then(
            static fn (array $data): string => (string) ($data['response']['channel'] ?? ''),
        );
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function sendMessageAsync(int $userId, string $text): PromiseInterface
    {
        return $this->requestWithBodyAsync(
            endpoint: 'inboxCreate',
            useToken: true,
            body: ['text' => $text],
            params: ['user_id' => $userId],
            retry: false,
        );
    }

    public function sendMessage(int $userId, string $text): array
    {
        return $this->sendMessageAsync($userId, $text)->wait();
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function deleteMessageAsync(int $messageId): PromiseInterface
    {
        return $this->requestAsync('post', 'inboxDelete', true, ['id' => $messageId]);
    }

    /**
     * @return list<DialogMessage>
     */
    public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
    {
        return $this->getDialogsPageAsync($page, $excludedIds)->wait();
    }

    /**
     * @return list<InboxMessage>
     */
    public function getDialogWithUser(string $username): array
    {
        return $this->getDialogWithUserAsync($username)->wait();
    }

    /**
     * @return PromiseInterface<list<DialogMessage>>
     */
    public function getDialogsPageAsync(int $page = 1, ?string $excludedIds = null): PromiseInterface
    {
        return $this->requestAsync('post', 'dialogs', true, [
            'page' => $page,
            'excludedIds' => $excludedIds,
        ])->then(static function (array $data): array {
            $response = $data['response'] ?? [];

            return array_map(
                static fn (array $item): DialogMessage => DialogMessage::fromArray($item),
                is_array($response) ? $response : [],
            );
        });
    }

    /**
     * @return PromiseInterface<list<DialogMessage>>
     */
    public function getAllDialogsAsync(): PromiseInterface
    {
        return $this->paginateDialogsAsync(1, []);
    }

    /**
     * @param list<DialogMessage> $accumulated
     * @return PromiseInterface<list<DialogMessage>>
     */
    private function paginateDialogsAsync(int $page, array $accumulated): PromiseInterface
    {
        return $this->getDialogsPageAsync($page)->then(function (array $pageDialogs) use ($page, $accumulated): array|PromiseInterface {
            if ($pageDialogs === []) {
                return $accumulated;
            }

            $next = [...$accumulated, ...$pageDialogs];

            return $this->paginateDialogsAsync($page + 1, $next);
        });
    }

    /**
     * @return PromiseInterface<list<InboxMessage>>
     */
    public function getDialogWithUserAsync(string $username): PromiseInterface
    {
        return $this->paginateInboxAsync($username, 1, []);
    }

    /**
     * @param list<InboxMessage> $accumulated
     * @return PromiseInterface<list<InboxMessage>>
     */
    private function paginateInboxAsync(string $username, int $page, array $accumulated): PromiseInterface
    {
        return $this->getDialogWithUserPageAsync($username, $page)->then(function (array $result) use ($username, $page, $accumulated): array|PromiseInterface {
            [$pageMessages, $paging] = $result;

            if ($pageMessages === []) {
                return $accumulated;
            }

            $next = [...$accumulated, ...$pageMessages];
            $pages = $paging['pages'] ?? $page;

            if ($page >= (int) $pages) {
                return $next;
            }

            return $this->paginateInboxAsync($username, $page + 1, $next);
        });
    }

    /**
     * @return PromiseInterface<array{0: list<InboxMessage>, 1: array<string, mixed>}>
     */
    public function getDialogWithUserPageAsync(string $username, int $page = 1): PromiseInterface
    {
        return $this->requestAsync('post', 'inboxes', true, [
            'username' => $username,
            'page' => $page,
        ])->then(static function (array $data): array {
            $response = $data['response'] ?? [];
            $paging = $data['paging'] ?? [];
            $messages = array_map(
                static fn (array $item): InboxMessage => InboxMessage::fromArray($item),
                is_array($response) ? $response : [],
            );

            return [$messages, is_array($paging) ? $paging : []];
        });
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function getWorkerOrdersAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'workerOrders', true, ['filter' => 'all']);
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function getPayerOrdersAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'payerOrders', true, ['filter' => 'all']);
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function getNotificationsAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'notifications', true);
    }

    /**
     * @return PromiseInterface<list<ParentCategory>>
     */
    public function getCategoriesAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'categories')->then(static function (array $data): array {
            return array_map(
                static fn (array $item): ParentCategory => ParentCategory::fromArray($item),
                is_array($data['response'] ?? null) ? $data['response'] : [],
            );
        });
    }

    /**
     * @return PromiseInterface<Connects>
     */
    public function getConnectsAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'projects', true, ['categories' => ''])->then(
            static fn (array $data): Connects => Connects::fromArray($data['connects'] ?? []),
        );
    }

    /**
     * @param list<int|string> $categoriesIds
     * @return PromiseInterface<list<WantWorker>>
     */
    public function getProjectsAsync(
        array $categoriesIds,
        ?int $priceFrom = null,
        ?int $priceTo = null,
        ?int $hiringFrom = null,
        ?int $kworksFilterFrom = null,
        ?int $kworksFilterTo = null,
        ?int $page = null,
        ?string $query = null,
    ): PromiseInterface {
        $categoriesStr = implode(',', array_map(static fn (int|string $id): string => (string) $id, $categoriesIds));

        return $this->requestAsync('post', 'projects', true, [
            'categories' => $categoriesStr,
            'price_from' => $priceFrom,
            'price_to' => $priceTo,
            'hiring_from' => $hiringFrom,
            'kworks_filter_from' => $kworksFilterFrom,
            'kworks_filter_to' => $kworksFilterTo,
            'page' => $page,
            'query' => $query,
        ])->then(static function (array $data): array {
            return array_map(
                static fn (array $item): WantWorker => WantWorker::fromArray($item),
                is_array($data['response'] ?? null) ? $data['response'] : [],
            );
        });
    }

    /**
     * @param list<int|string> $categoriesIds
     * @return PromiseInterface<list<WantWorker>>
     */
    public function getAllProjectsAsync(
        array $categoriesIds,
        ?int $priceFrom = null,
        ?int $priceTo = null,
        ?int $hiringFrom = null,
        ?int $kworksFilterFrom = null,
        ?int $kworksFilterTo = null,
        ?string $query = null,
    ): PromiseInterface {
        return $this->paginateProjectsAsync($categoriesIds, 1, [], $priceFrom, $priceTo, $hiringFrom, $kworksFilterFrom, $kworksFilterTo, $query);
    }

    /**
     * @param list<WantWorker> $accumulated
     * @param list<int|string> $categoriesIds
     * @return PromiseInterface<list<WantWorker>>
     */
    private function paginateProjectsAsync(
        array $categoriesIds,
        int $page,
        array $accumulated,
        ?int $priceFrom,
        ?int $priceTo,
        ?int $hiringFrom,
        ?int $kworksFilterFrom,
        ?int $kworksFilterTo,
        ?string $query,
    ): PromiseInterface {
        return $this->getProjectsAsync(
            $categoriesIds,
            $priceFrom,
            $priceTo,
            $hiringFrom,
            $kworksFilterFrom,
            $kworksFilterTo,
            $page,
            $query,
        )->then(function (array $pageProjects) use (
            $categoriesIds,
            $page,
            $accumulated,
            $priceFrom,
            $priceTo,
            $hiringFrom,
            $kworksFilterFrom,
            $kworksFilterTo,
            $query,
        ): array|PromiseInterface {
            if ($pageProjects === []) {
                return $accumulated;
            }

            return $this->paginateProjectsAsync(
                $categoriesIds,
                $page + 1,
                [...$accumulated, ...$pageProjects],
                $priceFrom,
                $priceTo,
                $hiringFrom,
                $kworksFilterFrom,
                $kworksFilterTo,
                $query,
            );
        });
    }

    /**
     * @return PromiseInterface<array<string, mixed>>
     */
    public function markNotificationsReadAsync(): PromiseInterface
    {
        return $this->requestAsync('post', 'notificationsRead', true);
    }
}

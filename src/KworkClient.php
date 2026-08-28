<?php

declare(strict_types=1);

namespace Kwork;

use Kwork\Schema\Actor;
use Kwork\Schema\Connects;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\InboxMessage;
use Kwork\Schema\ParentCategory;
use Kwork\Schema\User;
use Kwork\Schema\WantWorker;
use Kwork\Traits\APKExtraMethodsTrait;
use Kwork\Traits\OpenAPIMethodsTrait;

/**
 * High-level typed client for kwork.ru mobile API.
 */
class KworkClient extends KworkAPI
{
    use OpenAPIMethodsTrait;
    use APKExtraMethodsTrait;

    private ?KworkWebClient $webClient = null;

    public function __construct(
        string $login,
        string $password,
        ?string $proxy = null,
        ?string $phoneLast = null,
        string $apiHost = self::API_HOST,
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

    public function web(): KworkWebClient
    {
        if ($this->webClient === null) {
            $this->webClient = new KworkWebClient($this);
        }

        return $this->webClient;
    }

    public function webLogin(
        ?string $urlToRedirect = '/',
        ?string $userAgent = null,
    ): WebLoginResult {
        return $this->web()->loginViaMobileWebAuthToken(
            urlToRedirect: $urlToRedirect,
            userAgent: $userAgent,
        );
    }

    public function getMe(): Actor
    {
        $data = $this->request('post', 'actor', true);

        return Actor::fromArray($data['response']);
    }

    public function getUser(int $userId): User
    {
        $data = $this->request('post', 'user', false, ['id' => $userId]);

        return User::fromArray($data['response']);
    }

    /**
     * @return array<string, mixed>
     */
    public function setTyping(int $recipientId): array
    {
        return $this->request('post', 'typing', true, ['recipientId' => $recipientId]);
    }

    /**
     * @return array<string, mixed>
     */
    public function setOffline(): array
    {
        return $this->request('post', 'offline', true);
    }

    public function getChannel(): string
    {
        $data = $this->request('post', 'getChannel', true);

        return (string) ($data['response']['channel'] ?? '');
    }

    /**
     * @return array<string, mixed>
     */
    public function sendMessage(int $userId, string $text): array
    {
        return $this->requestWithBody(
            endpoint: 'inboxCreate',
            useToken: true,
            body: ['text' => $text],
            params: ['user_id' => $userId],
            retry: false,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteMessage(int $messageId): array
    {
        return $this->request('post', 'inboxDelete', true, ['id' => $messageId]);
    }

    /**
     * @return list<DialogMessage>
     */
    public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
    {
        $data = $this->request('post', 'dialogs', true, [
            'page' => $page,
            'excludedIds' => $excludedIds,
        ]);

        $response = $data['response'] ?? [];

        return array_map(
            static fn (array $item): DialogMessage => DialogMessage::fromArray($item),
            is_array($response) ? $response : [],
        );
    }

    /**
     * @return list<DialogMessage>
     */
    public function getAllDialogs(): array
    {
        $dialogs = [];
        $page = 1;

        while (true) {
            $pageDialogs = $this->getDialogsPage($page);
            if ($pageDialogs === []) {
                break;
            }

            array_push($dialogs, ...$pageDialogs);
            ++$page;
        }

        return $dialogs;
    }

    /**
     * @return list<InboxMessage>
     */
    public function getDialogWithUser(string $username): array
    {
        $messages = [];
        $page = 1;

        while (true) {
            [$pageMessages, $paging] = $this->getDialogWithUserPage($username, $page);
            if ($pageMessages === []) {
                break;
            }

            array_push($messages, ...$pageMessages);

            $pages = $paging['pages'] ?? $page;
            if ($page >= (int) $pages) {
                break;
            }

            ++$page;
        }

        return $messages;
    }

    /**
     * @return array{0: list<InboxMessage>, 1: array<string, mixed>}
     */
    public function getDialogWithUserPage(string $username, int $page = 1): array
    {
        $data = $this->request('post', 'inboxes', true, [
            'username' => $username,
            'page' => $page,
        ]);

        $response = $data['response'] ?? [];
        $paging = $data['paging'] ?? [];

        $messages = array_map(
            static fn (array $item): InboxMessage => InboxMessage::fromArray($item),
            is_array($response) ? $response : [],
        );

        return [$messages, is_array($paging) ? $paging : []];
    }

    /**
     * @return array<string, mixed>
     */
    public function getWorkerOrders(): array
    {
        return $this->request('post', 'workerOrders', true, ['filter' => 'all']);
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayerOrders(): array
    {
        return $this->request('post', 'payerOrders', true, ['filter' => 'all']);
    }

    /**
     * @return array<string, mixed>
     */
    public function getNotifications(): array
    {
        return $this->request('post', 'notifications', true);
    }

    /**
     * @return list<ParentCategory>
     */
    public function getCategories(): array
    {
        $data = $this->request('post', 'categories');

        return array_map(
            static fn (array $item): ParentCategory => ParentCategory::fromArray($item),
            is_array($data['response'] ?? null) ? $data['response'] : [],
        );
    }

    public function getConnects(): Connects
    {
        $data = $this->request('post', 'projects', true, ['categories' => '']);

        return Connects::fromArray($data['connects'] ?? []);
    }

    /**
     * @param list<int|string> $categoriesIds
     * @return list<WantWorker>
     */
    public function getProjects(
        array $categoriesIds,
        ?int $priceFrom = null,
        ?int $priceTo = null,
        ?int $hiringFrom = null,
        ?int $kworksFilterFrom = null,
        ?int $kworksFilterTo = null,
        ?int $page = null,
        ?string $query = null,
    ): array {
        $categoriesStr = implode(',', array_map(static fn (int|string $id): string => (string) $id, $categoriesIds));

        $data = $this->request('post', 'projects', true, [
            'categories' => $categoriesStr,
            'price_from' => $priceFrom,
            'price_to' => $priceTo,
            'hiring_from' => $hiringFrom,
            'kworks_filter_from' => $kworksFilterFrom,
            'kworks_filter_to' => $kworksFilterTo,
            'page' => $page,
            'query' => $query,
        ]);

        return array_map(
            static fn (array $item): WantWorker => WantWorker::fromArray($item),
            is_array($data['response'] ?? null) ? $data['response'] : [],
        );
    }
}

<?php

declare(strict_types=1);

namespace Kwork;

use Kwork\Exception\KworkBotException;
use Kwork\Exception\KworkException;
use Kwork\Schema\Message;
use WebSocket\Client as WebSocketClient;
use WebSocket\ConnectionException;

/**
 * WebSocket bot for handling incoming Kwork messages.
 */
class KworkBot extends KworkClient
{
    public const WEBSOCKET_URI = 'wss://notice.kwork.ru/ws/public/%s';
    public const RECONNECT_DELAY = 10;

    /** @var list<array{callable: callable, text: ?string, onStart: bool, textContains: ?string}> */
    private array $handlers = [];

    private EventParser $eventParser;

    /** @var array<int, string> */
    private array $userIdToUsername = [];

    /** @var array<int, bool> */
    private array $onStartSuppressedUserIds = [];

    public function __construct(
        string $login,
        string $password,
        ?string $proxy = null,
        ?string $phoneLast = null,
        private int $usernameCacheMax = 4096,
        private int $dialogStateCacheMax = 8192,
        ?\GuzzleHttp\Client $httpClient = null,
    ) {
        if ($usernameCacheMax < 0) {
            throw new \InvalidArgumentException('usernameCacheMax must be >= 0');
        }

        if ($dialogStateCacheMax < 0) {
            throw new \InvalidArgumentException('dialogStateCacheMax must be >= 0');
        }

        parent::__construct($login, $password, $proxy, $phoneLast, httpClient: $httpClient);
        $this->eventParser = new EventParser($this);
    }

    /**
     * Register a message handler.
     *
     * @param callable(Message): void $handler
     */
    public function messageHandler(
        callable $handler,
        ?string $text = null,
        bool $onStart = false,
        ?string $textContains = null,
    ): callable {
        $this->handlers[] = [
            'callable' => $handler,
            'text' => $text,
            'onStart' => $onStart,
            'textContains' => $textContains,
        ];

        return $handler;
    }

    public function run(): void
    {
        if ($this->handlers === []) {
            throw new KworkBotException('No handlers registered. Add at least one handler.');
        }

        try {
            foreach ($this->listenMessages() as $message) {
                $this->processMessage($message);
            }
        } finally {
            $this->close();
        }
    }

    /**
     * @return \Generator<int, Message, mixed, void>
     */
    private function listenMessages(): \Generator
    {
        while (true) {
            try {
                yield from $this->websocketLoop();
            } catch (KworkException) {
                sleep(self::RECONNECT_DELAY);
            } catch (\Throwable) {
                sleep(self::RECONNECT_DELAY);
            }
        }
    }

    /**
     * @return \Generator<int, Message, mixed, void>
     */
    private function websocketLoop(): \Generator
    {
        $channel = $this->getChannel();
        $uri = sprintf(self::WEBSOCKET_URI, $channel);

        $client = new WebSocketClient($uri, ['timeout' => 60]);

        try {
            while (true) {
                $message = $this->receiveMessage($client);
                if ($message !== null) {
                    yield $message;
                }
            }
        } finally {
            $client->close();
        }
    }

    private function receiveMessage(WebSocketClient $ws): ?Message
    {
        try {
            $rawData = $ws->receive();
        } catch (ConnectionException $e) {
            throw new KworkException('WebSocket connection closed', 0, $e);
        }

        $rawText = is_string($rawData) ? $rawData : (string) $rawData;
        $event = $this->eventParser->parseRawEvent($rawText);

        if ($event === null || $this->eventParser->shouldSkipEvent($event)) {
            return null;
        }

        return $this->eventParser->extractMessage($event);
    }

    private function processMessage(Message $message): void
    {
        foreach ($this->handlers as $handler) {
            if ($this->shouldHandle($message, $handler)) {
                ($handler['callable'])($message);
                break;
            }
        }
    }

    /**
     * @param array{callable: callable, text: ?string, onStart: bool, textContains: ?string} $handler
     */
    public function shouldHandle(Message $message, array $handler): bool
    {
        if (!$handler['onStart'] && $handler['text'] === null && $handler['textContains'] === null) {
            return true;
        }

        if ($handler['onStart']) {
            return $this->checkIsFirstMessage($message);
        }

        if ($handler['text'] !== null) {
            return mb_strtolower($handler['text']) === mb_strtolower($message->text);
        }

        if ($handler['textContains'] !== null) {
            return self::textContainsWord($handler['textContains'], $message->text);
        }

        return false;
    }

    public function checkIsFirstMessage(Message $message): bool
    {
        $userId = $message->fromId;

        if ($this->lruGet($this->onStartSuppressedUserIds, $userId) !== null) {
            return false;
        }

        $username = $this->getUsernameForUserId($userId);
        if ($username === null) {
            return false;
        }

        [$pageMessages, $paging] = $this->getDialogWithUserPage($username, 1);
        $pages = $paging['pages'] ?? null;

        if (is_int($pages) && $pages > 1) {
            $this->lruSet($this->onStartSuppressedUserIds, $userId, true, $this->dialogStateCacheMax);

            return false;
        }

        if ($pages === null) {
            if (count($pageMessages) > 1) {
                $this->lruSet($this->onStartSuppressedUserIds, $userId, true, $this->dialogStateCacheMax);

                return false;
            }

            $dialogMessages = $this->getDialogWithUser($username);
            $isFirst = count($dialogMessages) === 1;
        } else {
            $isFirst = count($pageMessages) === 1;
        }

        $this->lruSet($this->onStartSuppressedUserIds, $userId, true, $this->dialogStateCacheMax);

        return $isFirst;
    }

    private function getUsernameForUserId(int $userId): ?string
    {
        $cached = $this->lruGet($this->userIdToUsername, $userId);
        if ($cached !== null) {
            return $cached;
        }

        $page = 1;
        while (true) {
            $dialogs = $this->getDialogsPage($page);
            if ($dialogs === []) {
                return null;
            }

            foreach ($dialogs as $dialog) {
                if ($dialog->userId === $userId && $dialog->username) {
                    $this->lruSet($this->userIdToUsername, $userId, $dialog->username, $this->usernameCacheMax);

                    return $dialog->username;
                }
            }

            ++$page;
        }
    }

    public static function textContainsWord(string $word, string $text): bool
    {
        $punctuation = ".,!?;:\"'()-…";
        $words = preg_split('/\s+/', mb_strtolower($text)) ?: [];

        foreach ($words as $item) {
            if ($word === trim($item, $punctuation)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @template T
     * @param array<int, T> $cache
     * @return T|null
     */
    private function lruGet(array &$cache, int $key): mixed
    {
        if (!array_key_exists($key, $cache)) {
            return null;
        }

        $value = $cache[$key];
        unset($cache[$key]);
        $cache[$key] = $value;

        return $value;
    }

    /**
     * @template T
     * @param array<int, T> $cache
     * @param T $value
     */
    private function lruSet(array &$cache, int $key, mixed $value, int $maxSize): void
    {
        if ($maxSize === 0) {
            return;
        }

        $cache[$key] = $value;

        if (count($cache) > $maxSize) {
            $oldestKey = array_key_first($cache);
            if ($oldestKey !== null) {
                unset($cache[$oldestKey]);
            }
        }
    }
}

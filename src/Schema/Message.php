<?php

declare(strict_types=1);

namespace Kwork\Schema;

use Kwork\KworkClient;

class MessageModel extends DataModel
{
    public ?int $messageId = null;
    public ?int $toId = null;
    public ?string $toUsername = null;
    public ?int $toLiveDate = null;
    public ?int $fromId = null;
    public ?string $fromUsername = null;
    public ?int $fromLiveDate = null;
    public ?string $fromProfilepicture = null;
    public ?string $message = null;
    public ?int $time = null;
    public ?bool $unread = null;
    public ?string $type = null;
    public ?string $status = null;
    public ?string $createdOrderId = null;
    public ?bool $forwarded = null;
    public ?int $updatedAt = null;
    public ?int $messagePage = null;

    protected function aliases(): array
    {
        return [
            'messageId' => 'message_id',
            'toId' => 'to_id',
            'toUsername' => 'to_username',
            'toLiveDate' => 'to_live_date',
            'fromId' => 'from_id',
            'fromUsername' => 'from_username',
            'fromLiveDate' => 'from_live_date',
            'fromProfilepicture' => 'from_profilepicture',
            'createdOrderId' => 'created_order_id',
            'updatedAt' => 'updated_at',
            'messagePage' => 'message_page',
        ];
    }
}

class Message
{
    public function __construct(
        public readonly KworkClient $api,
        public readonly int $fromId,
        public readonly string $text,
        public readonly ?int $toUserId = null,
        public readonly ?int $inboxId = null,
        public readonly ?string $title = null,
        /** @var array<string, mixed>|null */
        public readonly ?array $lastMessage = null,
    ) {
    }

    public function answerSimulation(string $text): void
    {
        $this->sleepSeconds(2);
        $this->api->setTyping($this->fromId);
        $this->sleepSeconds(2);
        $this->api->sendMessage($this->fromId, $text);
    }

    protected function sleepSeconds(int $seconds): void
    {
        sleep($seconds);
    }

    public function fastAnswer(string $text): void
    {
        $this->api->sendMessage($this->fromId, $text);
    }
}

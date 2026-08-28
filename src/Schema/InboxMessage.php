<?php

declare(strict_types=1);

namespace Kwork\Schema;

class InboxMessage extends DataModel
{
    public ?int $messageId = null;
    public ?int $toId = null;
    public ?string $toUsername = null;
    public ?int $toLiveDate = null;
    public ?int $fromId = null;
    public ?string $fromUsername = null;
    public ?int $fromLiveDate = null;
    public ?string $fromProfilepicture = null;
    public ?string $toProfilepicture = null;
    public ?string $message = null;
    public ?int $time = null;
    public ?bool $unread = null;
    public ?string $type = null;
    public ?string $status = null;
    public ?string $createdOrderId = null;
    public ?bool $forwarded = null;
    public ?int $updatedAt = null;
    public ?string $warningType = null;
    public ?int $countup = null;
    /** @var list<mixed>|null */
    public ?array $files = null;
    /** @var array<string, mixed>|null */
    public ?array $quote = null;
    public ?int $messagePage = null;
    /** @var array<string, mixed>|null */
    public ?array $customRequest = null;
    /** @var array<string, mixed>|null */
    public ?array $inboxOrder = null;

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
            'toProfilepicture' => 'to_profilepicture',
            'createdOrderId' => 'created_order_id',
            'updatedAt' => 'updated_at',
            'warningType' => 'warning_type',
            'messagePage' => 'message_page',
            'customRequest' => 'custom_request',
            'inboxOrder' => 'inbox_order',
        ];
    }
}

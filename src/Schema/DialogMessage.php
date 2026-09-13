<?php

declare(strict_types=1);

namespace Kwork\Schema;

class DialogLastMessage extends DataModel
{
    public ?bool $unread = null;
    public ?string $fromUsername = null;
    public ?int $fromUserId = null;
    public ?string $type = null;
    public ?int $time = null;
    public ?string $message = null;
    public ?string $profilePicture = null;

    protected function aliases(): array
    {
        return [
            'fromUsername' => 'fromUsername',
            'fromUserId' => 'fromUserId',
            'profilePicture' => 'profilePicture',
        ];
    }
}

class DialogMessage extends DataModel
{
    public ?int $unread = null;
    public ?int $unreadCount = null;
    public ?string $lastMessageText = null;
    public ?int $time = null;
    public ?int $userId = null;
    public ?string $username = null;
    public ?string $profilepicture = null;
    public ?bool $isOnline = null;
    public ?int $lastOnlineTime = null;
    public ?string $link = null;
    public ?string $status = null;
    public ?bool $blockedByUser = null;
    public ?bool $allowedDialog = null;
    public ?DialogLastMessage $lastMessageObj = null;
    public ?bool $hasActiveOrder = null;
    public ?bool $archived = null;
    public ?bool $isStarred = null;
    public ?int $warningMessageId = null;
    public ?int $warningMessageTime = null;
    public ?int $countup = null;
    public ?bool $hasAnswer = null;
    public ?bool $isAllowCustomRequest = null;
    public ?int $hiddenAt = null;
    public ?int $disallowReason = null;

    protected function aliases(): array
    {
        return [
            'unreadCount' => 'unread_count',
            'lastMessageText' => 'last_message',
            'userId' => 'user_id',
            'isOnline' => 'is_online',
            'lastOnlineTime' => 'lastOnlineTime',
            'blockedByUser' => 'blocked_by_user',
            'allowedDialog' => 'allowedDialog',
            'lastMessageObj' => 'lastMessage',
            'hasActiveOrder' => 'has_active_order',
            'isStarred' => 'isStarred',
            'warningMessageId' => 'warning_message_id',
            'warningMessageTime' => 'warning_message_time',
            'hasAnswer' => 'has_answer',
            'isAllowCustomRequest' => 'is_allow_custom_request',
            'hiddenAt' => 'hidden_at',
            'disallowReason' => 'disallowReason',
        ];
    }
}

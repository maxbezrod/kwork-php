<?php

declare(strict_types=1);

namespace Kwork\Contract;

/**
 * Minimal contract for bot message replies (sync or async).
 */
interface MessagingClientInterface
{
    /**
     * @return array<string, mixed>
     */
    public function sendMessage(int $userId, string $text): array;

    /**
     * @return array<string, mixed>
     */
    public function setTyping(int $recipientId): array;
}

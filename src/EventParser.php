<?php

declare(strict_types=1);

namespace Kwork;

use Kwork\Schema\BaseEvent;
use Kwork\Schema\EventType;
use Kwork\Schema\Message;
use Kwork\Schema\Notify;

class EventParser
{
    public function __construct(
        private readonly KworkClient $client,
    ) {
    }

    public function parseRawEvent(string $rawData): ?BaseEvent
    {
        try {
            $jsonEvent = json_decode($rawData, true);
            if (!is_array($jsonEvent)) {
                return null;
            }

            $eventData = self::parseEventTextPayload($jsonEvent['text'] ?? null);
            if ($eventData === null) {
                return null;
            }

            return BaseEvent::fromArray($eventData);
        } catch (\Throwable) {
            return null;
        }
    }

    public function shouldSkipEvent(BaseEvent $event): bool
    {
        return $event->event === EventType::IsTyping->value;
    }

    public function extractMessage(BaseEvent $event): ?Message
    {
        if ($event->data === null) {
            return null;
        }

        $parsed = $this->parseEventToMessage($event);
        if ($parsed === null) {
            return null;
        }

        return new Message(
            api: $this->client,
            fromId: $parsed['fromId'],
            text: $parsed['text'],
            toUserId: $parsed['toUserId'] ?? null,
            inboxId: $parsed['inboxId'] ?? null,
            lastMessage: $parsed['lastMessage'] ?? null,
            title: $parsed['title'] ?? null,
        );
    }

    /**
     * @return array{fromId: int, text: string, toUserId?: int|null, inboxId?: int|null, title?: string|null, lastMessage?: array<string, mixed>|null}|null
     */
    private function parseEventToMessage(BaseEvent $event): ?array
    {
        return match ($event->event) {
            EventType::NewMessage->value => $this->parseNewMessage($event),
            EventType::Notify->value => $this->parseNotify($event),
            EventType::PopUpNotify->value => $this->parsePopupNotify($event),
            default => null,
        };
    }

    /**
     * @return array{fromId: int, text: string, toUserId?: int|null, inboxId?: int|null, title?: string|null, lastMessage?: array<string, mixed>|null}|null
     */
    private function parseNewMessage(BaseEvent $event): ?array
    {
        $data = $event->data;
        if ($data === null) {
            return null;
        }

        $fromRaw = $data['from'] ?? null;
        $text = $data['inboxMessage'] ?? null;

        if (!is_string($text)) {
            return null;
        }

        if (is_int($fromRaw)) {
            $fromId = $fromRaw;
        } else {
            if ($fromRaw === null) {
                return null;
            }

            if (!is_numeric($fromRaw)) {
                return null;
            }

            $fromId = (int) $fromRaw;
        }

        return [
            'fromId' => $fromId,
            'text' => $text,
            'toUserId' => isset($data['to_user_id']) ? (int) $data['to_user_id'] : null,
            'inboxId' => isset($data['inbox_id']) ? (int) $data['inbox_id'] : null,
            'title' => isset($data['title']) ? (string) $data['title'] : null,
            'lastMessage' => is_array($data['lastMessage'] ?? null) ? $data['lastMessage'] : null,
        ];
    }

    /**
     * @return array{fromId: int, text: string}|null
     */
    private function parseNotify(BaseEvent $event): ?array
    {
        $data = $event->data;
        if ($data === null || ($data[Notify::NewMessage->value] ?? null) === null) {
            return null;
        }

        if (($data['dialog_data'] ?? null) === null) {
            return $this->parseNotifyFromDialogs();
        }

        return $this->parseNotifyFromDialogData($data);
    }

    /**
     * @return array{fromId: int, text: string}|null
     */
    private function parseNotifyFromDialogs(): ?array
    {
        $dialogs = $this->client->getDialogsPage(1);
        if ($dialogs === []) {
            return null;
        }

        $lastDialog = $dialogs[0];
        if ($lastDialog->userId === null || $lastDialog->lastMessageText === null) {
            return null;
        }

        return [
            'fromId' => $lastDialog->userId,
            'text' => $lastDialog->lastMessageText,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array{fromId: int, text: string, toUserId?: int, inboxId?: int}|null
     */
    private function parseNotifyFromDialogData(array $data): ?array
    {
        $dialogData = $data['dialog_data'] ?? null;
        if (!is_array($dialogData) || $dialogData === []) {
            return null;
        }

        $login = $dialogData[0]['login'] ?? null;
        if (!is_string($login) || $login === '') {
            return null;
        }

        $messages = $this->client->getDialogWithUser($login);
        if ($messages === []) {
            return null;
        }

        $msg = $messages[0];
        if ($msg->fromId === null || $msg->message === null) {
            return null;
        }

        return [
            'fromId' => $msg->fromId,
            'text' => $msg->message,
            'toUserId' => $msg->toId,
            'inboxId' => $msg->messageId,
        ];
    }

    /**
     * @return array{fromId: int, text: string, toUserId?: int, inboxId?: int}|null
     */
    private function parsePopupNotify(BaseEvent $event): ?array
    {
        $data = $event->data;
        if ($data === null) {
            return null;
        }

        $popUpNotify = $data['pop_up_notify'] ?? null;
        if (!is_array($popUpNotify)) {
            return null;
        }

        $notifyData = $popUpNotify['data'] ?? null;
        if (!is_array($notifyData)) {
            return null;
        }

        $username = $notifyData['username'] ?? null;
        if (!is_string($username) || $username === '') {
            return null;
        }

        $messages = $this->client->getDialogWithUser($username);
        if ($messages === []) {
            return null;
        }

        $msg = $messages[0];
        if ($msg->fromId === null || $msg->message === null) {
            return null;
        }

        return [
            'fromId' => $msg->fromId,
            'text' => $msg->message,
            'toUserId' => $msg->toId,
            'inboxId' => $msg->messageId,
        ];
    }

    public static function parseEventTextPayload(mixed $text): ?array
    {
        if (is_array($text)) {
            return $text;
        }

        if (!is_string($text)) {
            return null;
        }

        $s = trim($text);
        if ($s === '') {
            return null;
        }

        if (str_starts_with($s, 'data:')) {
            $s = trim(substr($s, 5));
            if ($s === '') {
                return null;
            }
        }

        $parsed = self::loadJsonObject($s);
        if ($parsed !== null) {
            return $parsed;
        }

        if (str_contains($s, '%') || str_contains($s, '+')) {
            $decoded = urldecode(str_replace('+', ' ', $s));
            $decoded = trim($decoded);

            if (str_starts_with($decoded, 'data:')) {
                $decoded = trim(substr($decoded, 5));
            }

            if ($decoded !== '') {
                return self::loadJsonObject($decoded);
            }
        }

        return null;
    }

    private static function loadJsonObject(string $text): ?array
    {
        $parsed = json_decode($text, true);

        return is_array($parsed) ? $parsed : null;
    }
}

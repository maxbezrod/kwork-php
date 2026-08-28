<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\EventParser;
use Kwork\KworkClient;
use Kwork\Schema\BaseEvent;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\EventType;
use Kwork\Schema\InboxMessage;
use Kwork\Schema\Message;
use PHPUnit\Framework\TestCase;

final class EventParserMoreTest extends TestCase
{
    public function testParseEventTextPayloadSupportsDataPrefix(): void
    {
        $inner = ['event' => 'new_inbox', 'data' => ['from' => 1, 'inboxMessage' => 'hi']];
        $payload = 'data:' . json_encode($inner, JSON_THROW_ON_ERROR);
        self::assertSame($inner, EventParser::parseEventTextPayload($payload));
    }

    public function testParseEventTextPayloadSupportsUrlencodedAndDataPrefix(): void
    {
        $inner = ['event' => 'new_inbox', 'data' => ['from' => 1, 'inboxMessage' => 'hi']];
        $raw = 'data:%7B%22event%22%3A%22new_inbox%22%2C%22data%22%3A%7B%22from%22%3A1%2C%22inboxMessage%22%3A%22hi%22%7D%7D';
        self::assertSame($inner, EventParser::parseEventTextPayload($raw));
    }

    public function testParseRawEventReturnsNullForNonDictTopLevelJson(): void
    {
        $parser = new EventParser(new KworkClient('x', 'y'));
        self::assertNull($parser->parseRawEvent(json_encode([1, 2, 3], JSON_THROW_ON_ERROR)));
    }

    public function testShouldSkipEventIsTyping(): void
    {
        $parser = new EventParser(new KworkClient('x', 'y'));
        $event = BaseEvent::fromArray(['event' => EventType::IsTyping->value]);
        self::assertTrue($parser->shouldSkipEvent($event));
    }

    public function testExtractMessageNotifyFromDialogsUsesLastDialog(): void
    {
        $client = new class ('x', 'y') extends KworkClient {
            public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
            {
                return [DialogMessage::fromArray(['user_id' => 9, 'last_message' => 'last'])];
            }
        };

        $parser = new EventParser($client);
        $event = BaseEvent::fromArray([
            'event' => EventType::Notify->value,
            'data' => ['new_message' => true],
        ]);
        $msg = $parser->extractMessage($event);

        self::assertInstanceOf(Message::class, $msg);
        self::assertSame(9, $msg->fromId);
        self::assertSame('last', $msg->text);
    }

    public function testExtractMessageNotifyFromDialogDataFetchesDialogMessages(): void
    {
        $client = new class ('x', 'y') extends KworkClient {
            public function getDialogWithUser(string $username): array
            {
                return [InboxMessage::fromArray([
                    'message_id' => 11,
                    'from_id' => 3,
                    'to_id' => 4,
                    'message' => 'hey',
                ])];
            }
        };

        $parser = new EventParser($client);
        $event = BaseEvent::fromArray([
            'event' => EventType::Notify->value,
            'data' => ['new_message' => true, 'dialog_data' => [['login' => 'u1']]],
        ]);
        $msg = $parser->extractMessage($event);

        self::assertNotNull($msg);
        self::assertSame(3, $msg->fromId);
        self::assertSame('hey', $msg->text);
        self::assertSame(4, $msg->toUserId);
        self::assertSame(11, $msg->inboxId);
    }

    public function testExtractMessagePopupNotifyFetchesDialogMessages(): void
    {
        $client = new class ('x', 'y') extends KworkClient {
            public function getDialogWithUser(string $username): array
            {
                return [InboxMessage::fromArray([
                    'message_id' => 12,
                    'from_id' => 5,
                    'to_id' => 6,
                    'message' => 'yo',
                ])];
            }
        };

        $parser = new EventParser($client);
        $event = BaseEvent::fromArray([
            'event' => EventType::PopUpNotify->value,
            'data' => ['pop_up_notify' => ['data' => ['username' => 'u1']]],
        ]);
        $msg = $parser->extractMessage($event);

        self::assertNotNull($msg);
        self::assertSame(5, $msg->fromId);
        self::assertSame('yo', $msg->text);
    }
}

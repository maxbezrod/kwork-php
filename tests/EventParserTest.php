<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\EventParser;
use Kwork\KworkClient;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\InboxMessage;
use PHPUnit\Framework\TestCase;

final class EventParserTest extends TestCase
{
    public function testParseRawEventParsesJsonText(): void
    {
        $parser = new EventParser($this->createStub(KworkClient::class));
        $inner = ['event' => 'new_inbox', 'data' => ['from' => 123, 'inboxMessage' => 'hi']];
        $raw = json_encode(['text' => json_encode($inner)], JSON_THROW_ON_ERROR);

        $event = $parser->parseRawEvent($raw);

        self::assertNotNull($event);
        self::assertSame('new_inbox', $event->event);
        self::assertSame(['from' => 123, 'inboxMessage' => 'hi'], $event->data);
    }

    public function testParseRawEventParsesUrlencodedText(): void
    {
        $parser = new EventParser($this->createStub(KworkClient::class));
        $inner = ['event' => 'new_inbox', 'data' => ['from' => 1, 'inboxMessage' => 'hello']];
        $raw = json_encode(['text' => rawurlencode(json_encode($inner, JSON_THROW_ON_ERROR))], JSON_THROW_ON_ERROR);

        $event = $parser->parseRawEvent($raw);

        self::assertNotNull($event);
        self::assertSame('new_inbox', $event->event);
        self::assertSame(['from' => 1, 'inboxMessage' => 'hello'], $event->data);
    }

    public function testParseRawEventParsesDictTextPayload(): void
    {
        $parser = new EventParser($this->createStub(KworkClient::class));
        $inner = ['event' => 'notify', 'data' => ['new_message' => true]];
        $raw = json_encode(['text' => $inner], JSON_THROW_ON_ERROR);

        $event = $parser->parseRawEvent($raw);

        self::assertNotNull($event);
        self::assertSame('notify', $event->event);
        self::assertSame(['new_message' => true], $event->data);
    }

    public function testParseRawEventSkipsEmptyOrNonJsonText(): void
    {
        $parser = new EventParser($this->createStub(KworkClient::class));

        self::assertNull($parser->parseRawEvent(json_encode(['text' => ''], JSON_THROW_ON_ERROR)));
        self::assertNull($parser->parseRawEvent(json_encode(['text' => '   '], JSON_THROW_ON_ERROR)));
        self::assertNull($parser->parseRawEvent(json_encode(['text' => 'ping'], JSON_THROW_ON_ERROR)));
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\KworkBot;
use Kwork\Schema\Message;
use PHPUnit\Framework\TestCase;

final class BotMatchingTest extends TestCase
{
    public function testTextContainsWordStripsPunctuationAndIsCaseInsensitive(): void
    {
        self::assertTrue(KworkBot::textContainsWord('hello', 'Hello, world!'));
        self::assertTrue(KworkBot::textContainsWord('hello', '(hello)'));
        self::assertFalse(KworkBot::textContainsWord('hello', 'shellow'));
    }

    public function testShouldHandleMatchesExactTextCaseInsensitive(): void
    {
        $bot = new KworkBot('x', 'y');
        $msg = new Message($bot, 1, 'HeLLo');
        $handler = [
            'callable' => static fn () => null,
            'text' => 'hello',
            'onStart' => false,
            'textContains' => null,
        ];

        self::assertTrue($bot->shouldHandle($msg, $handler));
    }

    public function testShouldHandleMatchesWordContains(): void
    {
        $bot = new KworkBot('x', 'y');
        $msg = new Message($bot, 1, 'Ping, please.');
        $handler = [
            'callable' => static fn () => null,
            'text' => null,
            'onStart' => false,
            'textContains' => 'ping',
        ];

        self::assertTrue($bot->shouldHandle($msg, $handler));
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\KworkBot;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\InboxMessage;
use Kwork\Schema\Message;
use PHPUnit\Framework\TestCase;

final class BotOnStartTest extends TestCase
{
    public function testOnStartMatchesDialogBySenderUserId(): void
    {
        $bot = new class ('x', 'y') extends KworkBot {
            public int $dialogsCalls = 0;
            public int $pageCalls = 0;

            public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
            {
                ++$this->dialogsCalls;

                return [
                    DialogMessage::fromArray(['user_id' => 111, 'username' => 'u111']),
                    DialogMessage::fromArray(['user_id' => 222, 'username' => 'u222']),
                ];
            }

            public function getDialogWithUserPage(string $username, int $page = 1): array
            {
                ++$this->pageCalls;

                return [[InboxMessage::fromArray(['message_id' => 1, 'from_id' => 222, 'message' => 'hi'])], ['pages' => 1]];
            }
        };

        $msg = new Message($bot, 222, 'hi');
        self::assertTrue($bot->checkIsFirstMessage($msg));
        self::assertSame(1, $bot->pageCalls);
        self::assertSame(1, $bot->dialogsCalls);

        self::assertFalse($bot->checkIsFirstMessage($msg));
        self::assertSame(1, $bot->dialogsCalls);
        self::assertSame(1, $bot->pageCalls);
    }

    public function testOnStartCachesUsernameLookup(): void
    {
        $bot = new class ('x', 'y') extends KworkBot {
            public int $dialogsCalls = 0;

            public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
            {
                ++$this->dialogsCalls;

                return [DialogMessage::fromArray(['user_id' => 1, 'username' => 'u1'])];
            }

            public function getDialogWithUserPage(string $username, int $page = 1): array
            {
                return [[InboxMessage::fromArray(['message_id' => 1, 'from_id' => 1, 'message' => 'first'])], ['pages' => 1]];
            }
        };

        $msg = new Message($bot, 1, 'first');
        self::assertTrue($bot->checkIsFirstMessage($msg));
        self::assertSame(1, $bot->dialogsCalls);

        $msg2 = new Message($bot, 1, 'second');
        self::assertFalse($bot->checkIsFirstMessage($msg2));
        self::assertSame(1, $bot->dialogsCalls);
    }

    public function testOnStartMarksNotFirstWhenMultiplePages(): void
    {
        $bot = new class ('x', 'y') extends KworkBot {
            public int $dialogsCalls = 0;
            public int $pageCalls = 0;

            public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
            {
                ++$this->dialogsCalls;

                return [DialogMessage::fromArray(['user_id' => 5, 'username' => 'u5'])];
            }

            public function getDialogWithUserPage(string $username, int $page = 1): array
            {
                ++$this->pageCalls;

                return [[InboxMessage::fromArray(['message_id' => 10, 'from_id' => 5, 'message' => 'latest'])], ['pages' => 2]];
            }
        };

        $msg = new Message($bot, 5, 'latest');
        self::assertFalse($bot->checkIsFirstMessage($msg));
        self::assertFalse($bot->checkIsFirstMessage($msg));
        self::assertSame(1, $bot->dialogsCalls);
        self::assertSame(1, $bot->pageCalls);
    }

    public function testOnStartFallsBackWhenPagingMissing(): void
    {
        $bot = new class ('x', 'y') extends KworkBot {
            public int $fullDialogCalls = 0;

            public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
            {
                return [DialogMessage::fromArray(['user_id' => 9, 'username' => 'u9'])];
            }

            public function getDialogWithUserPage(string $username, int $page = 1): array
            {
                return [[InboxMessage::fromArray(['message_id' => 1, 'from_id' => 9, 'message' => 'only'])], []];
            }

            public function getDialogWithUser(string $username): array
            {
                ++$this->fullDialogCalls;

                return [InboxMessage::fromArray(['message_id' => 1, 'from_id' => 9, 'message' => 'only'])];
            }
        };

        $msg = new Message($bot, 9, 'only');
        self::assertTrue($bot->checkIsFirstMessage($msg));
        self::assertSame(1, $bot->fullDialogCalls);
    }
}

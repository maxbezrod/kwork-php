<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\KworkClient as RootKworkClient;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\InboxMessage;
use PHPUnit\Framework\TestCase;

final class ClientCoreTest extends TestCase
{
    public function testClientUsesRealRequestImplementation(): void
    {
        self::assertSame(
            (new \ReflectionMethod(RootKworkClient::class, 'request'))->getDeclaringClass()->getName(),
            \Kwork\KworkAPI::class,
        );
        self::assertSame(
            (new \ReflectionMethod(RootKworkClient::class, 'requestWithBody'))->getDeclaringClass()->getName(),
            \Kwork\KworkAPI::class,
        );
    }

    public function testGetAllDialogsPaginatesUntilEmpty(): void
    {
        $client = new class('x', 'y') extends RootKworkClient {
            /** @var list<list<DialogMessage>> */
            public array $pages = [];

            public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array
            {
                return $this->pages[$page - 1] ?? [];
            }
        };

        $client->pages = [
            [DialogMessage::fromArray(['user_id' => 1]), DialogMessage::fromArray(['user_id' => 2])],
            [DialogMessage::fromArray(['user_id' => 3])],
            [],
        ];

        $out = $client->getAllDialogs();
        self::assertSame([1, 2, 3], array_map(static fn (DialogMessage $d): ?int => $d->userId, $out));
    }

    public function testGetDialogWithUserPaginatesUntilPagesExhausted(): void
    {
        $client = new class('x', 'y') extends RootKworkClient {
            public int $pageCalls = 0;

            public function getDialogWithUserPage(string $username, int $page = 1): array
            {
                ++$this->pageCalls;
                if ($page === 1) {
                    return [[InboxMessage::fromArray(['message_id' => 1])], ['pages' => 2]];
                }

                return [[InboxMessage::fromArray(['message_id' => 2])], ['pages' => 2]];
            }
        };

        $out = $client->getDialogWithUser('u');
        self::assertSame([1, 2], array_map(static fn (InboxMessage $m): ?int => $m->messageId, $out));
        self::assertSame(2, $client->pageCalls);
    }

    public function testKworkAliasExists(): void
    {
        self::assertTrue(class_exists(\Kwork\Kwork::class));
        self::assertInstanceOf(RootKworkClient::class, new \Kwork\Kwork('a', 'b'));
    }
}

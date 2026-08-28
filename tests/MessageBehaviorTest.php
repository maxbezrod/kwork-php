<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\KworkClient;
use Kwork\Schema\Message;
use PHPUnit\Framework\TestCase;

final class MessageBehaviorTest extends TestCase
{
    public function testFastAnswerCallsSendMessage(): void
    {
        $api = new class ('x', 'y') extends KworkClient {
            public ?array $lastSend = null;

            public function sendMessage(int $userId, string $text): array
            {
                $this->lastSend = ['userId' => $userId, 'text' => $text];

                return [];
            }
        };

        $msg = new Message($api, 7, 'hi');
        $msg->fastAnswer('ok');

        self::assertSame(['userId' => 7, 'text' => 'ok'], $api->lastSend);
    }

    public function testAnswerSimulationCallsTypingThenSendMessageAndSleeps(): void
    {
        $events = [];

        $api = new class ('x', 'y') extends KworkClient {
            /** @var list<string> */
            public array $events = [];

            public function setTyping(int $recipientId): array
            {
                $this->events[] = 'typing:' . $recipientId;

                return [];
            }

            public function sendMessage(int $userId, string $text): array
            {
                $this->events[] = 'send:' . $userId . ':' . $text;

                return [];
            }
        };

        $msg = new class ($api, 3, 'hi') extends Message {
            protected function sleepSeconds(int $seconds): void
            {
                $this->api->events[] = 'sleep';
            }
        };

        $msg->answerSimulation('reply');

        self::assertSame(['sleep', 'typing:3', 'sleep', 'send:3:reply'], $api->events);
    }
}

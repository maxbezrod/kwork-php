<?php

declare(strict_types=1);

namespace Kwork\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kwork\Async\AsyncKworkAPI;
use Kwork\Async\AsyncKworkClient;
use PHPUnit\Framework\TestCase;

final class AsyncClientTest extends TestCase
{
    public function testGetMeAsyncReturnsActor(): void
    {
        $jsonHeaders = ['Content-Type' => 'application/json'];
        $mock = new MockHandler([
            new Response(200, $jsonHeaders, json_encode([
                'success' => true,
                'response' => ['token' => 'abc123'],
            ])),
            new Response(200, $jsonHeaders, json_encode([
                'success' => true,
                'response' => [
                    'id' => 42,
                    'username' => 'demo',
                    'free_amount' => 100,
                    'currency' => 'RUB',
                ],
            ])),
        ]);

        $http = new Client(['handler' => HandlerStack::create($mock)]);
        $api = new AsyncKworkClient('login', 'password', httpClient: $http);

        $actor = $api->getMeAsync()->wait();

        self::assertSame(42, $actor->id);
        self::assertSame('demo', $actor->username);
    }

    public function testAsyncKworkAliasExists(): void
    {
        self::assertTrue(class_exists(\Kwork\AsyncKwork::class));
        self::assertInstanceOf(AsyncKworkClient::class, new \Kwork\AsyncKwork('a', 'b'));
    }

    public function testAllUnwrapsPromises(): void
    {
        $p1 = \GuzzleHttp\Promise\Create::promiseFor(['success' => true, 'response' => ['a' => 1]]);
        $p2 = \GuzzleHttp\Promise\Create::promiseFor(['success' => true, 'response' => ['b' => 2]]);

        $results = AsyncKworkAPI::all([$p1, $p2]);

        self::assertSame(1, $results[0]['response']['a']);
        self::assertSame(2, $results[1]['response']['b']);
    }
}

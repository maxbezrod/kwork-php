<?php

declare(strict_types=1);

namespace Kwork\Tests\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

final class MockHttp
{
    /**
     * @param list<Response|\Throwable> $responses
     */
    public static function client(array $responses): Client
    {
        return new Client([
            'handler' => HandlerStack::create(new MockHandler($responses)),
            'http_errors' => false,
        ]);
    }

    public static function jsonResponse(int $status, array $payload, array $headers = []): Response
    {
        $headers = array_merge(['Content-Type' => 'application/json'], $headers);

        return new Response($status, $headers, json_encode($payload, JSON_THROW_ON_ERROR));
    }

    public static function textResponse(int $status, string $body, string $contentType = 'text/plain'): Response
    {
        return new Response($status, ['Content-Type' => $contentType], $body);
    }
}

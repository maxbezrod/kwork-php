<?php

declare(strict_types=1);

namespace Kwork\Tests;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Kwork\Exception\KworkHTTPException;
use Kwork\Exception\KworkRetryExceeded;
use Kwork\KworkAPI;
use Kwork\Tests\Support\MockHttp;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class ApiRetriesTest extends TestCase
{
    public function testNon2xxRaisesHttpExceptionWithBody(): void
    {
        $api = new KworkAPI(
            login: 'x',
            password: 'y',
            retryMaxAttempts: 1,
            httpClient: MockHttp::client([
                MockHttp::textResponse(500, 'oops'),
            ]),
        );

        try {
            $api->request('get', 'ping', retry: false);
            self::fail('Expected KworkHTTPException');
        } catch (KworkHTTPException $e) {
            self::assertSame(500, $e->status);
            self::assertSame('ping', $e->endpoint);
            self::assertStringContainsString('oops', $e->getMessage());
        }
    }

    public function testRetriesOn5xxThenSucceeds(): void
    {
        $api = new KworkAPI(
            login: 'x',
            password: 'y',
            retryMaxAttempts: 3,
            retryBackoffBase: 0.0,
            retryJitter: 0.0,
            httpClient: MockHttp::client([
                MockHttp::textResponse(500, 'fail'),
                MockHttp::textResponse(502, 'fail2'),
                MockHttp::jsonResponse(200, ['success' => true, 'response' => ['ok' => 1]]),
            ]),
        );

        $data = $api->request('post', 'actor');
        self::assertSame(1, $data['response']['ok']);
    }

    public function testRetriesOn429UsesRetryAfter(): void
    {
        $api = new KworkAPI(
            login: 'x',
            password: 'y',
            retryMaxAttempts: 2,
            retryBackoffBase: 0.0,
            retryBackoffMax: 10.0,
            retryJitter: 0.0,
            httpClient: MockHttp::client([
                MockHttp::jsonResponse(429, ['error' => 'rate limit'], ['Retry-After' => '0']),
                MockHttp::jsonResponse(200, ['success' => true, 'response' => ['ok' => 1]]),
            ]),
        );

        $data = $api->request('post', 'actor');
        self::assertSame(1, $data['response']['ok']);
    }

    public function testNetworkErrorsRaiseRetryExceeded(): void
    {
        $request = new Request('GET', 'https://api.kwork.ru/ping');
        $api = new KworkAPI(
            login: 'x',
            password: 'y',
            retryMaxAttempts: 2,
            retryBackoffBase: 0.0,
            retryJitter: 0.0,
            httpClient: MockHttp::client([
                new ConnectException('timeout', $request),
                new ConnectException('nope', $request),
            ]),
        );

        try {
            $api->request('get', 'ping');
            self::fail('Expected KworkRetryExceeded');
        } catch (KworkRetryExceeded $e) {
            self::assertSame(2, $e->attempts);
            self::assertNotNull($e->getPrevious());
            self::assertStringContainsString('ConnectException', $e->getMessage());
        }
    }

    public function testTimeoutErrorMessageIsInformative(): void
    {
        $request = new Request('GET', 'https://api.kwork.ru/ping');
        $api = new KworkAPI(
            'x',
            'y',
            retryMaxAttempts: 1,
            httpClient: MockHttp::client([
                new ConnectException('timeout', $request),
            ]),
        );

        try {
            $api->request('get', 'ping', false, [], retry: false);
            self::fail('Expected KworkRetryExceeded');
        } catch (KworkRetryExceeded $e) {
            self::assertStringContainsString('ConnectException', $e->getMessage());
        }
    }
}

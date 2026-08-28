<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\Exception\KworkException;
use Kwork\Exception\KworkHTTPException;
use Kwork\KworkAPI;
use Kwork\Tests\Support\MockHttp;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class ApiHandleJsonTest extends TestCase
{
    public function testHandleJsonPayloadRaisesOnNonJsonResponseEvenIf200(): void
    {
        $api = new KworkAPI('x', 'y');
        $response = MockHttp::textResponse(200, 'ok');
        $ref = new ReflectionMethod(KworkAPI::class, 'handleJsonPayload');
        $ref->setAccessible(true);

        try {
            $ref->invoke($api, $response, 'endpoint', 'get', null, null);
            self::fail('Expected KworkHTTPException');
        } catch (KworkHTTPException $e) {
            self::assertSame(200, $e->status);
            self::assertSame('endpoint', $e->endpoint);
            self::assertSame('ok', $e->responseText);
        }
    }

    public function testHandleJsonPayloadRaisesOnApiLevelErrorSuccessFalse(): void
    {
        $api = new KworkAPI('x', 'y');
        $response = MockHttp::jsonResponse(200, ['success' => false, 'error' => 'nope']);
        $ref = new ReflectionMethod(KworkAPI::class, 'handleJsonPayload');
        $ref->setAccessible(true);

        $this->expectException(KworkException::class);
        $this->expectExceptionMessage('nope');
        $ref->invoke($api, $response, 'endpoint', 'post', null, null);
    }

    public function testHandleJsonPayloadReturnsDataOnSuccessTrue(): void
    {
        $api = new KworkAPI('x', 'y');
        $response = MockHttp::jsonResponse(200, ['success' => true, 'response' => ['ok' => 1]]);
        $ref = new ReflectionMethod(KworkAPI::class, 'handleJsonPayload');
        $ref->setAccessible(true);

        $out = $ref->invoke($api, $response, 'endpoint', 'post', ['a' => 1], ['b' => 2]);
        self::assertSame(1, $out['response']['ok']);
    }
}

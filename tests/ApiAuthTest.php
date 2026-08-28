<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\Exception\KworkException;
use Kwork\KworkAPI;
use Kwork\Tests\Support\MockHttp;
use PHPUnit\Framework\TestCase;

final class ApiAuthTest extends TestCase
{
    public function testGetTokenStoresTokenFromSignIn(): void
    {
        $api = new KworkAPI(
            login: 'user',
            password: 'pass',
            httpClient: MockHttp::client([
                MockHttp::jsonResponse(200, [
                    'success' => true,
                    'response' => ['token' => 'abc-token'],
                ]),
            ]),
        );

        self::assertSame('abc-token', $api->getToken());
        self::assertSame('abc-token', $api->getToken());
    }

    public function testGetTokenRaisesWhenMissing(): void
    {
        $api = new KworkAPI(
            login: 'user',
            password: 'pass',
            httpClient: MockHttp::client([
                MockHttp::jsonResponse(200, ['success' => true, 'response' => []]),
            ]),
        );

        $this->expectException(KworkException::class);
        $api->getToken();
    }
}

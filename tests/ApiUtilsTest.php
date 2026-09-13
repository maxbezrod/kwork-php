<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\KworkAPI;
use PHPUnit\Framework\TestCase;

final class ApiUtilsTest extends TestCase
{
    public function testIsSensitiveKeyDetectsCommonSecretFields(): void
    {
        self::assertTrue(KworkAPI::isSensitiveKey('password'));
        self::assertTrue(KworkAPI::isSensitiveKey('new_password'));
        self::assertTrue(KworkAPI::isSensitiveKey('access_token'));
        self::assertTrue(KworkAPI::isSensitiveKey('Authorization'));
        self::assertTrue(KworkAPI::isSensitiveKey('phone_last'));
        self::assertTrue(KworkAPI::isSensitiveKey('phoneLast'));
        self::assertFalse(KworkAPI::isSensitiveKey('username'));
    }

    public function testRedactSensitiveRedactsNestedStructures(): void
    {
        $data = [
            'password' => 'secret',
            'nested' => [
                'token' => 'abc',
                'ok' => 1,
                'items' => [
                    ['authorization' => 'Bearer x'],
                    ['phone_last' => '0000'],
                ],
            ],
        ];

        $redacted = KworkAPI::redactSensitive($data);

        self::assertSame('<redacted>', $redacted['password']);
        self::assertSame('<redacted>', $redacted['nested']['token']);
        self::assertSame(1, $redacted['nested']['ok']);
        self::assertSame('<redacted>', $redacted['nested']['items'][0]['authorization']);
        self::assertSame('<redacted>', $redacted['nested']['items'][1]['phone_last']);
    }

    public function testTruncateKeepsShortStrings(): void
    {
        $api = new KworkAPI('x', 'y');
        self::assertSame('hi', $api->truncate('hi', 10));
    }

    public function testTruncateAddsSuffixWhenLong(): void
    {
        $api = new KworkAPI('x', 'y');
        $out = $api->truncate(str_repeat('a', 20), 5);
        self::assertStringStartsWith('aaaaa', $out);
        self::assertStringEndsWith('...<truncated>', $out);
    }

    public function testComputeBackoffRespectsMaxAndNoJitter(): void
    {
        $api = new KworkAPI(
            'x',
            'y',
            retryBackoffBase: 1.0,
            retryBackoffMax: 3.0,
            retryJitter: 0.0,
        );

        self::assertSame(1.0, $api->computeBackoff(1));
        self::assertSame(2.0, $api->computeBackoff(2));
        self::assertSame(3.0, $api->computeBackoff(3));
        self::assertSame(3.0, $api->computeBackoff(4));
    }

    public function testParseRetryAfterSecondsNumeric(): void
    {
        $api = new KworkAPI('x', 'y');
        self::assertSame(2.5, $api->parseRetryAfterSeconds('2.5'));
    }

    public function testFormatExceptionShortIsInformativeForTimeoutError(): void
    {
        $message = KworkAPI::formatExceptionShort(new \RuntimeException(''));
        self::assertStringContainsString('RuntimeException', $message);
        self::assertNotSame('RuntimeException:', trim($message));
    }

    public function testNormalizeTimeoutAcceptsFloatAndNone(): void
    {
        self::assertNull(KworkAPI::normalizeTimeout(null));
        self::assertSame(1.25, KworkAPI::normalizeTimeout(1.25));
    }

    public function testParseRetryAfterSecondsHttpDateFutureIsPositive(): void
    {
        $api = new KworkAPI('x', 'y');
        $value = gmdate('D, d M Y H:i:s', time() + 5) . ' GMT';
        $out = $api->parseRetryAfterSeconds($value);
        self::assertNotNull($out);
        self::assertGreaterThan(0.0, $out);
        self::assertLessThanOrEqual(6.0, $out);
    }

    public function testParseRetryAfterSecondsHttpDatePastIsZero(): void
    {
        $api = new KworkAPI('x', 'y');
        self::assertSame(0.0, $api->parseRetryAfterSeconds('Sat, 01 Jan 2000 00:00:00 GMT'));
    }
}

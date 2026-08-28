<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\KworkWebClient;
use PHPUnit\Framework\TestCase;

final class WebClientUtilsTest extends TestCase
{
    public function testExtractDraftKeyFromCommonPatterns(): void
    {
        self::assertSame('abc123', KworkWebClient::extractDraftKey('var x = {"draftKey":"abc123"};'));
        self::assertSame('zz99aa', KworkWebClient::extractDraftKey("<input name='draftKey' value='zz99aa' />"));
        self::assertSame('k1k2k3', KworkWebClient::extractDraftKey("<div data-draft-key='k1k2k3'></div>"));
        self::assertNull(KworkWebClient::extractDraftKey('nope'));
    }

    public function testExtractCsrfUserTokenFromCommonPatterns(): void
    {
        $token = str_repeat('a', 32);
        self::assertSame($token, KworkWebClient::extractCsrfUserToken('csrf_user_token="' . $token . '"'));
        self::assertSame($token, KworkWebClient::extractCsrfUserToken("<input name='csrftoken' value='{$token}' />"));
        self::assertNull(KworkWebClient::extractCsrfUserToken('nope'));
    }

    public function testMaybeAddCsrfHeadersAddsOriginRefererAndTokens(): void
    {
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];
        KworkWebClient::maybeAddCsrfHeaders(
            'https://kwork.ru/api/offer/createoffer',
            $headers,
            'https://kwork.ru/',
            [
                'XSRF-TOKEN' => 'a%2Bb',
                'csrf_token' => 'csrf1',
                'csrf_user_token' => 'csrf2',
            ],
        );

        self::assertSame('a+b', $headers['X-XSRF-TOKEN']);
        self::assertSame('csrf1', $headers['X-CSRF-Token']);
        self::assertSame('https://kwork.ru', $headers['Origin']);
        self::assertSame('https://kwork.ru/', $headers['Referer']);
    }

    public function testMaybeAddCsrfHeadersDoesNotOverrideExistingCsrfHeader(): void
    {
        $headers = ['X-Requested-With' => 'XMLHttpRequest', 'X-CSRF-Token' => 'already'];
        KworkWebClient::maybeAddCsrfHeaders(
            'https://kwork.ru/x',
            $headers,
            'https://kwork.ru/',
            ['csrf_token' => 'cookie-csrf'],
        );
        self::assertSame('already', $headers['X-CSRF-Token']);
    }
}

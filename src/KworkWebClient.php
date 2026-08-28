<?php

declare(strict_types=1);

namespace Kwork;

use GuzzleHttp\RequestOptions;
use Kwork\Exception\KworkException;

/**
 * Web client that reuses the authenticated mobile API session.
 */
class KworkWebClient
{
    public const DEFAULT_WEB_BASE_URL = 'https://kwork.ru/';

    public function __construct(
        private readonly KworkAPI $api,
        private string $baseUrl = self::DEFAULT_WEB_BASE_URL,
    ) {
        $this->baseUrl = rtrim($this->baseUrl, '/') . '/';
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function loginViaMobileWebAuthToken(
        ?string $urlToRedirect = '/',
        ?string $userAgent = null,
        bool $allowRedirects = true,
        int $maxRedirects = 10,
        ?float $timeout = null,
    ): WebLoginResult {
        if ($urlToRedirect !== null && !str_starts_with($urlToRedirect, '/')) {
            throw new \InvalidArgumentException("urlToRedirect must be a relative URL starting with '/'");
        }

        $tokenResp = $this->api->request('post', 'getWebAuthToken', true, [
            'url_to_redirect' => $urlToRedirect,
        ]);

        $payload = is_array($tokenResp['response'] ?? null) ? $tokenResp['response'] : [];
        $loginUrl = $payload['url'] ?? null;

        if (!is_string($loginUrl) || $loginUrl === '') {
            throw new \RuntimeException('Unexpected getWebAuthToken response: ' . json_encode($tokenResp));
        }

        $parsed = parse_url($loginUrl);
        if (!in_array($parsed['scheme'] ?? '', ['http', 'https'], true)) {
            throw new \InvalidArgumentException('Unexpected login_url scheme: ' . $loginUrl);
        }

        if (!str_ends_with($parsed['host'] ?? '', 'kwork.ru')) {
            throw new \InvalidArgumentException('Unexpected login_url host: ' . $loginUrl);
        }

        $headers = [];
        if ($userAgent !== null) {
            $headers['User-Agent'] = $userAgent;
        }

        $options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::ALLOW_REDIRECTS => $allowRedirects ? ['max' => $maxRedirects] : false,
        ];

        if ($timeout !== null) {
            $options[RequestOptions::TIMEOUT] = $timeout;
        }

        $response = $this->api->getHttpClient()->request('GET', $loginUrl, $options);
        $finalUrl = (string) $response->getHeaderLine('X-Guzzle-Redirect-History');
        if ($finalUrl === '') {
            $finalUrl = $loginUrl;
        } else {
            $parts = explode(', ', $finalUrl);
            $finalUrl = end($parts) ?: $loginUrl;
        }

        $status = $response->getStatusCode();

        if ($urlToRedirect) {
            $targetUrl = $this->baseUrl . ltrim($urlToRedirect, '/');
            $response2 = $this->api->getHttpClient()->request('GET', $targetUrl, $options);
            $redirectHistory = $response2->getHeaderLine('X-Guzzle-Redirect-History');
            $finalUrl = $redirectHistory !== '' ? (end(explode(', ', $redirectHistory)) ?: $targetUrl) : $targetUrl;
            $status = $response2->getStatusCode();
        }

        return new WebLoginResult(
            token: isset($payload['token']) ? (string) $payload['token'] : null,
            expiresAt: isset($payload['expires_at']) ? (int) $payload['expires_at'] : null,
            loginUrl: $loginUrl,
            urlToRedirect: isset($payload['url_to_redirect']) ? (string) $payload['url_to_redirect'] : null,
            finalUrl: $finalUrl,
            status: $status,
        );
    }

    /**
     * @param array<string, mixed>|null $params
     * @param array<string, mixed>|null $headers
     * @return array{status: int, url: string, headers: array<string, string>, text: string, json: mixed}
     */
    public function request(
        string $method,
        string $pathOrUrl,
        ?array $params = null,
        mixed $data = null,
        mixed $jsonData = null,
        ?array $headers = null,
        bool $allowRedirects = true,
        ?float $timeout = null,
    ): array {
        if (str_starts_with($pathOrUrl, 'http://') || str_starts_with($pathOrUrl, 'https://')) {
            $url = $pathOrUrl;
        } else {
            $url = $this->baseUrl . ltrim($pathOrUrl, '/');
        }

        $hdrs = $headers ?? [];
        if (isset($hdrs['X-Requested-With'])) {
            $this->maybeAddCsrfHeadersInstance($url, $hdrs);
        }

        $options = [
            RequestOptions::HEADERS => $hdrs,
            RequestOptions::QUERY => $params ?? [],
            RequestOptions::ALLOW_REDIRECTS => $allowRedirects,
        ];

        if ($jsonData !== null) {
            $options[RequestOptions::JSON] = $jsonData;
        } elseif ($data !== null) {
            $options[RequestOptions::BODY] = $data;
        }

        if ($timeout !== null) {
            $options[RequestOptions::TIMEOUT] = $timeout;
        }

        $response = $this->api->getHttpClient()->request(strtoupper($method), $url, $options);
        $text = (string) $response->getBody();
        $contentType = $response->getHeaderLine('Content-Type');
        $parsedJson = null;

        if (str_contains($contentType, 'application/json') || str_ends_with($contentType, '+json')) {
            $parsedJson = json_decode($text, true);
        }

        $responseHeaders = [];
        foreach ($response->getHeaders() as $name => $values) {
            $responseHeaders[$name] = implode(', ', $values);
        }

        return [
            'status' => $response->getStatusCode(),
            'url' => (string) $response->getHeaderLine('X-Guzzle-Effective-Url') ?: $url,
            'headers' => $responseHeaders,
            'text' => $text,
            'json' => $parsedJson,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function quickFaqInit(string $referer, ?string $userAgent = null, string $page = 'new_offer'): array
    {
        $headers = $this->buildXhrHeaders($userAgent, 'application/json, text/plain, */*', $referer);
        $headers['Content-Type'] = 'application/json';

        $resp = $this->request('POST', 'quick-faq/init', headers: $headers, jsonData: ['page' => $page]);
        $this->raiseOnWebError($resp, 'quick-faq/init');

        return $resp;
    }

    /**
     * @return array<string, mixed>
     */
    public function createOfferDraft(
        int $projectId,
        string $csrftoken,
        string $draftKey,
        string $referer,
        string $message = '',
        ?string $userAgent = null,
    ): array {
        $headers = $this->buildXhrHeaders($userAgent, '*/*', $referer);
        $multipart = [
            ['name' => 'csrftoken', 'contents' => $csrftoken],
            ['name' => 'projectId', 'contents' => (string) $projectId],
            ['name' => 'message', 'contents' => $message],
            ['name' => 'draftKey', 'contents' => $draftKey],
        ];

        $resp = $this->requestMultipartWeb('POST', 'wants/create_offer_draft', $headers, $multipart);
        $this->raiseOnWebError($resp, 'wants/create_offer_draft');

        return $resp;
    }

    /**
     * @return array<string, mixed>
     */
    public function checkIsTemplate(
        int $wantId,
        string $description,
        string $referer,
        ?string $userAgent = null,
    ): array {
        $headers = $this->buildXhrHeaders($userAgent, 'application/json, text/plain, */*', $referer);
        $headers['Content-Type'] = 'application/json';

        $resp = $this->request(
            'POST',
            'projects/check_is_template',
            headers: $headers,
            jsonData: ['description' => $description, 'wantid' => $wantId],
        );
        $this->raiseOnWebError($resp, 'projects/check_is_template');

        return $resp;
    }

    /**
     * @return array<string, mixed>
     */
    public function openNewOfferPage(int $projectId, ?string $userAgent = null): array
    {
        $headers = [];
        if ($userAgent !== null) {
            $headers['User-Agent'] = $userAgent;
        }

        $resp = $this->request('GET', 'new_offer?project=' . $projectId, headers: $headers ?: null);
        $status = $resp['status'] ?? 0;

        if (!in_array($status, [200, 302], true)) {
            throw new KworkException('Failed to open /new_offer page: HTTP ' . $status);
        }

        return $resp;
    }

    /**
     * @param array<string, string>|null $extraHeaders
     * @return array<string, mixed>
     */
    public function createExchangeOffer(
        int $wantId,
        string $description,
        int $kworkDuration,
        int $kworkPrice,
        string $kworkName,
        string $offerType = 'custom',
        ?string $userAgent = null,
        ?array $extraHeaders = null,
        bool $raiseOnError = true,
        ?string $referer = null,
    ): array {
        $headers = $this->buildXhrHeaders($userAgent, 'application/json, text/plain, */*', $referer);
        if ($extraHeaders !== null) {
            $headers = array_merge($headers, $extraHeaders);
        }

        $multipart = [
            ['name' => 'wantId', 'contents' => (string) $wantId],
            ['name' => 'offerType', 'contents' => $offerType],
            ['name' => 'description', 'contents' => $description],
            ['name' => 'kwork_duration', 'contents' => (string) $kworkDuration],
            ['name' => 'kwork_price', 'contents' => (string) $kworkPrice],
            ['name' => 'kwork_name', 'contents' => $kworkName],
        ];

        $resp = $this->requestMultipartWeb(
            'POST',
            'api/offer/createoffer',
            $headers,
            $multipart,
            ['wantId' => $wantId, 'offerType' => $offerType],
        );

        if ($raiseOnError) {
            $this->raiseOnWebError($resp, 'api/offer/createoffer');
        }

        return $resp;
    }

    /**
     * @return array<string, mixed>
     */
    public function submitExchangeOffer(
        int $projectId,
        string $description,
        int $kworkDuration,
        int $kworkPrice,
        string $kworkName,
        string $offerType = 'custom',
        ?string $userAgent = null,
        bool $raiseOnError = true,
    ): array {
        $referer = $this->baseUrl . 'new_offer?project=' . $projectId;
        $page = $this->openNewOfferPage($projectId, $userAgent);
        $html = (string) ($page['text'] ?? '');

        $cookies = $this->filteredCookies($this->baseUrl);
        $csrftoken = $cookies['csrf_user_token'] ?? self::extractCsrfUserToken($html);
        if ($csrftoken === null) {
            throw new \RuntimeException('csrf_user_token cookie not found; cannot continue web offer flow');
        }

        $draftKey = self::extractDraftKey($html) ?? $this->genDraftKey();

        $this->quickFaqInit($referer, $userAgent, 'new_offer');
        $this->createOfferDraft($projectId, $csrftoken, $draftKey, $referer, '', $userAgent);
        $this->checkIsTemplate($projectId, $description, $referer, $userAgent);

        return $this->createExchangeOffer(
            wantId: $projectId,
            description: $description,
            kworkDuration: $kworkDuration,
            kworkPrice: $kworkPrice,
            kworkName: $kworkName,
            offerType: $offerType,
            userAgent: $userAgent,
            raiseOnError: $raiseOnError,
            referer: $referer,
        );
    }

    /**
     * @param array<string, mixed> $resp
     */
    private function raiseOnWebError(array $resp, string $where): void
    {
        $json = $resp['json'] ?? null;
        if (is_array($json) && ($json['success'] ?? null) === false) {
            $msg = $json['message'] ?? $json['error'] ?? $json['response'] ?? 'Web API error';
            throw new KworkException($where . ': ' . (is_string($msg) ? $msg : 'Web API error'));
        }
    }

    /**
     * @return array<string, string>
     */
    private function filteredCookies(string $url): array
    {
        $host = parse_url($url, PHP_URL_HOST) ?: 'kwork.ru';
        $cookies = [];

        foreach ($this->api->getCookieJar()->toArray() as $cookie) {
            if (($cookie['Domain'] ?? '') === '' || str_contains($host, ltrim((string) $cookie['Domain'], '.'))) {
                $cookies[$cookie['Name']] = $cookie['Value'];
            }
        }

        return $cookies;
    }

    /**
     * @param array<string, string> $cookies
     * @param array<string, string> $headers
     */
    public static function maybeAddCsrfHeaders(
        string $url,
        array &$headers,
        string $baseUrl,
        array $cookies,
    ): void {
        if (!isset($headers['X-XSRF-TOKEN']) && isset($cookies['XSRF-TOKEN'])) {
            $headers['X-XSRF-TOKEN'] = rawurldecode($cookies['XSRF-TOKEN']);
        }

        if (!isset($headers['X-CSRF-Token']) && isset($cookies['csrf_token'])) {
            $headers['X-CSRF-Token'] = $cookies['csrf_token'];
        }

        if (!isset($headers['X-CSRF-Token']) && isset($cookies['csrf_user_token'])) {
            $headers['X-CSRF-Token'] = $cookies['csrf_user_token'];
        }

        if (!isset($headers['Origin'])) {
            $parsed = parse_url($url);
            $headers['Origin'] = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? 'kwork.ru');
        }

        if (!isset($headers['Referer'])) {
            $headers['Referer'] = rtrim($baseUrl, '/') . '/';
        }
    }

    public static function extractDraftKey(string $html): ?string
    {
        $patterns = [
            '/draftKey["\']?\s*[:=]\s*["\']([a-z0-9]{6,64})["\']/i',
            '/name=["\']draftKey["\']\s+value=["\']([a-z0-9]{6,64})["\']/i',
            '/data-draft-key=["\']([a-z0-9]{6,64})["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }

    public static function extractCsrfUserToken(string $html): ?string
    {
        $patterns = [
            '/csrf_user_token["\']?\s*[:=]\s*["\']([a-f0-9]{16,128})["\']/i',
            '/name=["\']csrftoken["\']\s+value=["\']([a-f0-9]{16,128})["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches) === 1) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * @param array<string, string> $headers
     */
    private function maybeAddCsrfHeadersInstance(string $url, array &$headers): void
    {
        self::maybeAddCsrfHeaders($url, $headers, $this->baseUrl, $this->filteredCookies($url));
    }

    /**
     * @return array<string, string>
     */
    private function buildXhrHeaders(?string $userAgent, ?string $accept, ?string $referer): array
    {
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        if ($accept !== null) {
            $headers['Accept'] = $accept;
        }

        if ($userAgent !== null) {
            $headers['User-Agent'] = $userAgent;
        }

        if ($referer !== null) {
            $headers['Referer'] = $referer;
        }

        return $headers;
    }

    private function genDraftKey(int $length = 8): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';

        for ($i = 0; $i < $length; ++$i) {
            $result .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $result;
    }

    /**
     * @param array<string, string> $headers
     * @param list<array<string, mixed>> $multipart
     * @param array<string, mixed>|null $params
     * @return array<string, mixed>
     */
    private function requestMultipartWeb(
        string $method,
        string $path,
        array $headers,
        array $multipart,
        ?array $params = null,
    ): array {
        $url = $this->baseUrl . ltrim($path, '/');
        $this->maybeAddCsrfHeadersInstance($url, $headers);

        $response = $this->api->getHttpClient()->request($method, $url, [
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY => $params ?? [],
            RequestOptions::MULTIPART => $multipart,
        ]);

        $text = (string) $response->getBody();
        $contentType = $response->getHeaderLine('Content-Type');
        $parsedJson = str_contains($contentType, 'json') ? json_decode($text, true) : null;

        return [
            'status' => $response->getStatusCode(),
            'url' => $url,
            'headers' => [],
            'text' => $text,
            'json' => $parsedJson,
        ];
    }
}

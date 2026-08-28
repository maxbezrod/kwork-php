<?php

declare(strict_types=1);

namespace Kwork;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Kwork\Exception\KworkException;
use Kwork\Exception\KworkHTTPException;
use Kwork\Exception\KworkRetryExceeded;
use Psr\Http\Message\ResponseInterface;

/**
 * Low-level HTTP client for api.kwork.ru.
 *
 * @phpstan-type ApiResponse array<string, mixed>
 */
class KworkAPI
{
    public const AUTH_HEADER = 'Basic bW9iaWxlX2FwaTpxRnZmUmw3dw==';
    public const API_HOST = 'https://api.kwork.ru/%s';

    private const REDACTED = '<redacted>';

    /** @var list<int> */
    private const DEFAULT_RETRY_STATUSES = [429, 500, 502, 503, 504];

    private ?Client $client = null;
    private CookieJar $cookieJar;
    private ?string $token = null;

    /** @var list<int> */
    private array $retryStatuses;

    public function __construct(
        private readonly string $login,
        private readonly string $password,
        private readonly ?string $proxy = null,
        private readonly ?string $phoneLast = null,
        private string $apiHost = self::API_HOST,
        private readonly ?float $timeout = 30.0,
        private readonly int $retryMaxAttempts = 1,
        private readonly float $retryBackoffBase = 0.5,
        private readonly float $retryBackoffMax = 8.0,
        private readonly float $retryJitter = 0.1,
        ?array $retryStatuses = null,
        private readonly bool $reloginOnAuthError = false,
        ?Client $httpClient = null,
    ) {
        if ($this->retryMaxAttempts < 1) {
            throw new \InvalidArgumentException('retryMaxAttempts must be >= 1');
        }
        if ($this->retryBackoffBase < 0) {
            throw new \InvalidArgumentException('retryBackoffBase must be >= 0');
        }
        if ($this->retryBackoffMax < 0) {
            throw new \InvalidArgumentException('retryBackoffMax must be >= 0');
        }
        if ($this->retryJitter < 0) {
            throw new \InvalidArgumentException('retryJitter must be >= 0');
        }

        $this->retryStatuses = $retryStatuses ?? self::DEFAULT_RETRY_STATUSES;
        $this->cookieJar = new CookieJar();

        if ($httpClient !== null) {
            $this->client = $httpClient;
        }
    }

    public function setHttpClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getHttpClient(): Client
    {
        if ($this->client === null) {
            $config = [
                RequestOptions::COOKIES => $this->cookieJar,
                RequestOptions::HTTP_ERRORS => false,
            ];

            if ($this->timeout !== null) {
                $config[RequestOptions::TIMEOUT] = $this->timeout;
                $config[RequestOptions::CONNECT_TIMEOUT] = $this->timeout;
            }

            if ($this->proxy !== null) {
                $config[RequestOptions::PROXY] = $this->proxy;
            }

            $this->client = new Client($config);
        }

        return $this->client;
    }

    public function getCookieJar(): CookieJar
    {
        return $this->cookieJar;
    }

    public function close(): void
    {
        $this->client = null;
        $this->token = null;
    }

    public function getToken(): string
    {
        if ($this->token !== null) {
            return $this->token;
        }

        $body = [
            'login' => $this->login,
            'password' => $this->password,
        ];

        if ($this->phoneLast !== null) {
            $body['phone_last'] = $this->phoneLast;
        }

        $response = $this->requestWithBody('signIn', false, $body);
        $token = $response['response']['token'] ?? null;

        if (!is_string($token) || $token === '') {
            throw new KworkException('Authentication response did not include a token');
        }

        $this->token = $token;

        return $token;
    }

    /**
     * @param array<string, mixed> $params
     * @return ApiResponse
     */
    public function request(
        string $method,
        string $endpoint,
        bool $useToken = false,
        array $params = [],
        ?array $headers = null,
        ?array $cookies = null,
        ?bool $retry = null,
        ?float $timeout = null,
        ?int $maxAttempts = null,
    ): array {
        $filtered = $this->filterParams($params);

        if ($useToken) {
            $filtered['token'] = $this->getToken();
        }

        $requestHeaders = ['Authorization' => self::AUTH_HEADER];
        if ($headers !== null) {
            $requestHeaders = array_merge($requestHeaders, $headers);
        }

        return $this->requestJson(
            method: $method,
            endpoint: $endpoint,
            headers: $requestHeaders,
            params: $filtered,
            data: null,
            cookies: $cookies,
            retry: $retry,
            timeout: $timeout,
            maxAttempts: $maxAttempts,
            useToken: $useToken,
        );
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return ApiResponse
     */
    public function requestWithBody(
        string $endpoint,
        bool $useToken = false,
        ?array $body = null,
        array $params = [],
        ?array $headers = null,
        ?array $cookies = null,
        ?bool $retry = null,
        ?float $timeout = null,
        ?int $maxAttempts = null,
    ): array {
        $filtered = $this->filterParams($params);

        if ($useToken) {
            $filtered['token'] = $this->getToken();
        }

        $requestHeaders = ['Authorization' => self::AUTH_HEADER];
        if ($headers !== null) {
            $requestHeaders = array_merge($requestHeaders, $headers);
        }

        return $this->requestJson(
            method: 'post',
            endpoint: $endpoint,
            headers: $requestHeaders,
            params: $filtered,
            data: $body,
            cookies: $cookies,
            retry: $retry,
            timeout: $timeout,
            maxAttempts: $maxAttempts,
            useToken: $useToken,
        );
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return ApiResponse
     */
    public function requestMultipart(
        string $endpoint,
        bool $useToken = false,
        ?array $fields = null,
        ?array $files = null,
        array $params = [],
        ?array $headers = null,
        ?array $cookies = null,
        ?bool $retry = null,
        ?float $timeout = null,
        ?int $maxAttempts = null,
    ): array {
        $filtered = $this->filterParams($params);

        if ($useToken) {
            $filtered['token'] = $this->getToken();
        }

        $requestHeaders = ['Authorization' => self::AUTH_HEADER];
        if ($headers !== null) {
            $requestHeaders = array_merge($requestHeaders, $headers);
        }

        $attemptsLimit = $maxAttempts ?? 1;
        if ($attemptsLimit < 1) {
            throw new \InvalidArgumentException('maxAttempts must be >= 1');
        }

        $enableRetry = $retry ?? $attemptsLimit > 1;
        $attempts = 0;

        while (true) {
            ++$attempts;

            try {
                $multipart = $this->buildMultipart($fields, $files, $enableRetry && $attemptsLimit > 1);

                $options = [
                    RequestOptions::HEADERS => $requestHeaders,
                    RequestOptions::QUERY => $filtered,
                    RequestOptions::MULTIPART => $multipart,
                ];

                if ($cookies !== null) {
                    $options[RequestOptions::COOKIES] = CookieJar::fromArray($cookies, parse_url($this->formatEndpoint($endpoint), PHP_URL_HOST) ?: 'api.kwork.ru');
                }

                if ($timeout !== null) {
                    $options[RequestOptions::TIMEOUT] = $timeout;
                }

                $response = $this->getHttpClient()->request('POST', $this->formatEndpoint($endpoint), $options);

                try {
                    return $this->handleJsonPayload(
                        $response,
                        $endpoint,
                        'post',
                        $filtered,
                        null,
                    );
                } catch (KworkHTTPException $e) {
                    if (
                        $enableRetry
                        && $e->status !== null
                        && $this->shouldRetryStatus($e->status)
                        && $attempts < $attemptsLimit
                    ) {
                        $retryAfter = $e->status === 429 ? $this->parseRetryAfterSeconds($response) : null;
                        $delay = $this->computeBackoff($attempts);
                        if ($retryAfter !== null) {
                            $delay = min(max($delay, $retryAfter), $this->retryBackoffMax);
                        }
                        if ($delay > 0) {
                            usleep((int) ($delay * 1_000_000));
                        }
                        continue;
                    }

                    throw $e;
                }
            } catch (GuzzleException $e) {
                if (!$enableRetry || $attempts >= $attemptsLimit) {
                    throw new KworkRetryExceeded(
                        sprintf('Request POST /%s failed after %d attempts: %s', $endpoint, $attempts, trim($e::class . ($e->getMessage() !== '' ? ': ' . $e->getMessage() : ''))),
                        $attempts,
                        $e,
                    );
                }

                $delay = $this->computeBackoff($attempts);
                if ($delay > 0) {
                    usleep((int) ($delay * 1_000_000));
                }
            }
        }
    }

    /**
     * @param array<string, mixed>|null $params
     * @param array<string, mixed>|null $data
     * @return ApiResponse
     */
    private function requestJson(
        string $method,
        string $endpoint,
        array $headers,
        ?array $params,
        ?array $data,
        ?array $cookies,
        ?bool $retry,
        ?float $timeout,
        ?int $maxAttempts,
        bool $useToken,
    ): array {
        $attemptsLimit = $maxAttempts ?? $this->retryMaxAttempts;
        if ($attemptsLimit < 1) {
            throw new \InvalidArgumentException('maxAttempts must be >= 1');
        }

        $enableRetry = $retry ?? $attemptsLimit > 1;
        $attempts = 0;
        $authResetDone = false;

        while (true) {
            ++$attempts;

            try {
                $options = [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::QUERY => $params ?? [],
                ];

                if ($data !== null) {
                    $options[RequestOptions::FORM_PARAMS] = $data;
                }

                if ($cookies !== null) {
                    $options[RequestOptions::COOKIES] = CookieJar::fromArray(
                        $cookies,
                        parse_url($this->formatEndpoint($endpoint), PHP_URL_HOST) ?: 'api.kwork.ru',
                    );
                }

                if ($timeout !== null) {
                    $options[RequestOptions::TIMEOUT] = $timeout;
                }

                $response = $this->getHttpClient()->request(
                    strtoupper($method),
                    $this->formatEndpoint($endpoint),
                    $options,
                );

                if (
                    in_array($response->getStatusCode(), [401, 403], true)
                    && $useToken
                    && $this->reloginOnAuthError
                    && !$authResetDone
                    && $enableRetry
                    && $attempts < $attemptsLimit
                ) {
                    $authResetDone = true;
                    $this->token = null;
                    $delay = $this->computeBackoff($attempts);
                    if ($delay > 0) {
                        usleep((int) ($delay * 1_000_000));
                    }
                    continue;
                }

                try {
                    return $this->handleJsonPayload($response, $endpoint, $method, $params, $data);
                } catch (KworkHTTPException $e) {
                    if (
                        $enableRetry
                        && $e->status !== null
                        && $this->shouldRetryStatus($e->status)
                        && $attempts < $attemptsLimit
                    ) {
                        $retryAfter = $e->status === 429 ? $this->parseRetryAfterSeconds($response) : null;
                        $delay = $this->computeBackoff($attempts);
                        if ($retryAfter !== null) {
                            $delay = min(max($delay, $retryAfter), $this->retryBackoffMax);
                        }
                        if ($delay > 0) {
                            usleep((int) ($delay * 1_000_000));
                        }
                        continue;
                    }

                    throw $e;
                }
            } catch (GuzzleException $e) {
                if (!$enableRetry || $attempts >= $attemptsLimit) {
                    throw new KworkRetryExceeded(
                        sprintf(
                            'Request %s /%s failed after %d attempts: %s',
                            strtoupper($method),
                            $endpoint,
                            $attempts,
                            trim($e::class . ($e->getMessage() !== '' ? ': ' . $e->getMessage() : '')),
                        ),
                        $attempts,
                        $e,
                    );
                }

                $delay = $this->computeBackoff($attempts);
                if ($delay > 0) {
                    usleep((int) ($delay * 1_000_000));
                }
            }
        }
    }

    /**
     * @param array<string, mixed>|null $requestParams
     * @param array<string, mixed>|null $requestBody
     * @return ApiResponse
     */
    public function handleJsonPayload(
        ResponseInterface $response,
        string $endpoint,
        string $method,
        ?array $requestParams,
        ?array $requestBody,
    ): array {
        $bodyText = (string) $response->getBody();
        $status = $response->getStatusCode();
        $contentType = $response->getHeaderLine('Content-Type');
        $data = str_contains($contentType, 'application/json')
            ? json_decode($bodyText, true)
            : null;

        if ($status < 200 || $status >= 300) {
            throw new KworkHTTPException(
                sprintf('HTTP %d for %s /%s: %s', $status, strtoupper($method), $endpoint, $this->truncate($bodyText)),
                status: $status,
                method: strtoupper($method),
                endpoint: $endpoint,
                responseText: $bodyText,
                responseJson: is_array($data) ? $data : null,
                requestParams: $requestParams !== null ? self::redactSensitive($requestParams) : null,
                requestBody: is_array($requestBody) ? self::redactSensitive($requestBody) : null,
            );
        }

        if (!is_array($data)) {
            throw new KworkHTTPException(
                sprintf('Non-JSON response from /%s: %s', $endpoint, $this->truncate($bodyText)),
                status: $status,
                method: strtoupper($method),
                endpoint: $endpoint,
                responseText: $bodyText,
                requestParams: $requestParams !== null ? self::redactSensitive($requestParams) : null,
            );
        }

        if (!($data['success'] ?? false)) {
            $error = $data['error'] ?? 'Unknown API error';
            throw new KworkException(is_string($error) ? $error : 'Unknown API error');
        }

        return $data;
    }

    /**
     * @return array{0: string, 1: array<string, mixed>|null}
     */
    private function readResponseBody(ResponseInterface $response): array
    {
        $text = (string) $response->getBody();
        $contentType = $response->getHeaderLine('Content-Type');

        if (!str_contains($contentType, 'application/json')) {
            return [$text, null];
        }

        $parsed = json_decode($text, true);

        return [$text, is_array($parsed) ? $parsed : null];
    }

    private function formatEndpoint(string $endpoint): string
    {
        return sprintf($this->apiHost, $endpoint);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    private function filterParams(array $params): array
    {
        return array_filter(
            $params,
            static fn (mixed $value): bool => $value !== null,
        );
    }

    public function shouldRetryStatus(int $status): bool
    {
        return in_array($status, $this->retryStatuses, true);
    }

    public function computeBackoff(int $retryN): float
    {
        $delay = $this->retryBackoffBase * (2 ** ($retryN - 1));
        $delay = min($delay, $this->retryBackoffMax);

        if ($delay <= 0) {
            return 0.0;
        }

        if ($this->retryJitter <= 0) {
            return $delay;
        }

        return $delay + (mt_rand() / mt_getrandmax()) * $delay * $this->retryJitter;
    }

    public function parseRetryAfterSeconds(ResponseInterface|string $responseOrHeader): ?float
    {
        $value = $responseOrHeader instanceof ResponseInterface
            ? $responseOrHeader->getHeaderLine('Retry-After')
            : $responseOrHeader;
        if ($value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return max(0.0, (float) ($timestamp - time()));
    }

    public function truncate(string $text, int $limit = 2048): string
    {
        if (strlen($text) <= $limit) {
            return $text;
        }

        return substr($text, 0, $limit) . '...<truncated>';
    }

    public static function formatExceptionShort(\Throwable $err): string
    {
        $message = trim($err->getMessage());
        if ($message !== '') {
            return $err::class . ': ' . $message;
        }

        return $err::class . ' (' . $err . ')';
    }

    public static function normalizeTimeout(mixed $timeout): ?float
    {
        if ($timeout === null) {
            return null;
        }

        return (float) $timeout;
    }

    /**
     * @param array<string, mixed>|list<mixed> $value
     */
    public static function redactSensitive(mixed $value, int $depth = 0): mixed
    {
        if ($depth >= 20) {
            return '<max_depth>';
        }

        if (!is_array($value)) {
            return $value;
        }

        $isAssoc = array_keys($value) !== range(0, count($value) - 1);
        if (!$isAssoc) {
            return array_map(fn (mixed $item): mixed => self::redactSensitive($item, $depth + 1), $value);
        }

        $redacted = [];
        foreach ($value as $key => $item) {
            if (is_string($key) && self::isSensitiveKey($key)) {
                $redacted[$key] = self::REDACTED;
            } else {
                $redacted[$key] = self::redactSensitive($item, $depth + 1);
            }
        }

        return $redacted;
    }

    public static function isSensitiveKey(string $key): bool
    {
        $normalized = strtolower($key);
        if (in_array($normalized, ['phone_last', 'phonelast'], true)) {
            return true;
        }

        foreach (['password', 'token', 'authorization'] as $part) {
            if (str_contains($normalized, $part)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return list<array<string, mixed>>
     */
    private function buildMultipart(?array $fields, ?array $files, bool $retryEnabled): array
    {
        $multipart = [];

        if ($fields !== null) {
            foreach ($fields as $key => $value) {
                if ($value === null) {
                    continue;
                }

                if (is_bool($value)) {
                    $value = (int) $value;
                }

                $multipart[] = [
                    'name' => (string) $key,
                    'contents' => is_scalar($value) ? (string) $value : json_encode($value),
                ];
            }
        }

        if ($files !== null) {
            foreach ($files as $field => $spec) {
                if ($spec === null) {
                    continue;
                }

                $filename = null;
                $contentType = null;
                $value = $spec;

                if (is_array($spec) && isset($spec[0], $spec[1])) {
                    $filename = (string) $spec[0];
                    $value = $spec[1];
                    $contentType = isset($spec[2]) ? (string) $spec[2] : null;
                } elseif (is_string($spec) && is_file($spec)) {
                    $filename = basename($spec);
                    $contentType = mime_content_type($spec) ?: 'application/octet-stream';
                    $value = fopen($spec, 'rb');
                } elseif (is_string($spec)) {
                    $filename = (string) $field;
                    $contentType = 'application/octet-stream';
                } elseif (is_resource($spec)) {
                    if ($retryEnabled) {
                        throw new \InvalidArgumentException(
                            'Retry for multipart requests requires file specs as paths/bytes/tuples, not file-like objects.',
                        );
                    }
                    $filename = (string) $field;
                    $contentType = 'application/octet-stream';
                    $value = $spec;
                }

                $entry = [
                    'name' => (string) $field,
                    'contents' => $value,
                ];

                if ($filename !== null) {
                    $entry['filename'] = $filename;
                }

                if ($contentType !== null) {
                    $entry['headers'] = ['Content-Type' => $contentType];
                }

                $multipart[] = $entry;
            }
        }

        return $multipart;
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Async;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\RequestOptions;
use Kwork\Exception\KworkException;
use Kwork\Exception\KworkHTTPException;
use Kwork\Exception\KworkRetryExceeded;
use Kwork\KworkAPI;
use Kwork\Proxy\ProxyConfig;
use Psr\Http\Message\ResponseInterface;

/**
 * Promise-based async HTTP client for api.kwork.ru.
 *
 * @phpstan-type ApiResponse array<string, mixed>
 */
class AsyncKworkAPI
{
    private ?Client $client = null;
    private CookieJar $cookieJar;
    private ?string $token = null;

    /** @var list<int> */
    private array $retryStatuses;

    public function __construct(
        private readonly string $login,
        private readonly string $password,
        private readonly string|ProxyConfig|null $proxy = null,
        private readonly ?string $phoneLast = null,
        private string $apiHost = KworkAPI::API_HOST,
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

        $this->retryStatuses = $retryStatuses ?? [429, 500, 502, 503, 504];
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
                $config[RequestOptions::PROXY] = ProxyConfig::resolve($this->proxy);
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

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPhoneLast(): ?string
    {
        return $this->phoneLast;
    }

    public function getProxy(): string|ProxyConfig|null
    {
        return $this->proxy;
    }

    /**
     * @return PromiseInterface<string>
     */
    public function getTokenAsync(): PromiseInterface
    {
        if ($this->token !== null) {
            return \GuzzleHttp\Promise\Create::promiseFor($this->token);
        }

        $body = [
            'login' => $this->login,
            'password' => $this->password,
        ];

        if ($this->phoneLast !== null) {
            $body['phone_last'] = $this->phoneLast;
        }

        return $this->requestWithBodyAsync('signIn', false, $body)->then(function (array $response): string {
            $token = $response['response']['token'] ?? null;

            if (!is_string($token) || $token === '') {
                throw new KworkException('Authentication response did not include a token');
            }

            $this->token = $token;

            return $token;
        });
    }

    /**
     * @param array<string, mixed> $params
     * @return PromiseInterface<ApiResponse>
     */
    public function requestAsync(
        string $method,
        string $endpoint,
        bool $useToken = false,
        array $params = [],
        ?array $headers = null,
        ?array $cookies = null,
        ?bool $retry = null,
        ?float $timeout = null,
        ?int $maxAttempts = null,
    ): PromiseInterface {
        $filtered = $this->filterParams($params);

        if ($useToken) {
            return $this->getTokenAsync()->then(function (string $token) use (
                $method,
                $endpoint,
                $filtered,
                $headers,
                $cookies,
                $retry,
                $timeout,
                $maxAttempts,
                $useToken,
            ): PromiseInterface {
                $filtered['token'] = $token;

                return $this->requestJsonAsync(
                    $method,
                    $endpoint,
                    $this->buildHeaders($headers),
                    $filtered,
                    null,
                    $cookies,
                    $retry,
                    $timeout,
                    $maxAttempts,
                    $useToken,
                );
            });
        }

        return $this->requestJsonAsync(
            $method,
            $endpoint,
            $this->buildHeaders($headers),
            $filtered,
            null,
            $cookies,
            $retry,
            $timeout,
            $maxAttempts,
            false,
        );
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $body
     * @return PromiseInterface<ApiResponse>
     */
    public function requestWithBodyAsync(
        string $endpoint,
        bool $useToken = false,
        ?array $body = null,
        array $params = [],
        ?array $headers = null,
        ?array $cookies = null,
        ?bool $retry = null,
        ?float $timeout = null,
        ?int $maxAttempts = null,
    ): PromiseInterface {
        $filtered = $this->filterParams($params);

        if ($useToken) {
            return $this->getTokenAsync()->then(function (string $token) use (
                $endpoint,
                $filtered,
                $body,
                $headers,
                $cookies,
                $retry,
                $timeout,
                $maxAttempts,
            ): PromiseInterface {
                $filtered['token'] = $token;

                return $this->requestJsonAsync(
                    'post',
                    $endpoint,
                    $this->buildHeaders($headers),
                    $filtered,
                    $body,
                    $cookies,
                    $retry,
                    $timeout,
                    $maxAttempts,
                    true,
                );
            });
        }

        return $this->requestJsonAsync(
            'post',
            $endpoint,
            $this->buildHeaders($headers),
            $filtered,
            $body,
            $cookies,
            $retry,
            $timeout,
            $maxAttempts,
            false,
        );
    }

    /**
     * @param array<string, mixed>|null $params
     * @param array<string, mixed>|null $data
     * @return PromiseInterface<ApiResponse>
     */
    private function requestJsonAsync(
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
    ): PromiseInterface {
        $attemptsLimit = $maxAttempts ?? $this->retryMaxAttempts;
        $enableRetry = $retry ?? $attemptsLimit > 1;

        return $this->attemptJsonRequest(
            $method,
            $endpoint,
            $headers,
            $params,
            $data,
            $cookies,
            $enableRetry,
            $attemptsLimit,
            1,
            false,
            $useToken,
            $timeout,
        );
    }

    /**
     * @param array<string, mixed>|null $params
     * @param array<string, mixed>|null $data
     * @return PromiseInterface<ApiResponse>
     */
    private function attemptJsonRequest(
        string $method,
        string $endpoint,
        array $headers,
        ?array $params,
        ?array $data,
        ?array $cookies,
        bool $enableRetry,
        int $attemptsLimit,
        int $attempt,
        bool $authResetDone,
        bool $useToken,
        ?float $timeout,
    ): PromiseInterface {
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

        return $this->getHttpClient()
            ->requestAsync(strtoupper($method), $this->formatEndpoint($endpoint), $options)
            ->then(
                function (ResponseInterface $response) use (
                    $method,
                    $endpoint,
                    $params,
                    $data,
                    $enableRetry,
                    $attemptsLimit,
                    $attempt,
                    $authResetDone,
                    $useToken,
                    $headers,
                    $cookies,
                    $timeout,
                ): array|PromiseInterface {
                    if (
                        in_array($response->getStatusCode(), [401, 403], true)
                        && $useToken
                        && $this->reloginOnAuthError
                        && !$authResetDone
                        && $enableRetry
                        && $attempt < $attemptsLimit
                    ) {
                        $this->token = null;

                        return $this->delay($this->computeBackoff($attempt))->then(
                            fn (): PromiseInterface => $this->attemptJsonRequest(
                                $method,
                                $endpoint,
                                $headers,
                                $params,
                                $data,
                                $cookies,
                                $enableRetry,
                                $attemptsLimit,
                                $attempt + 1,
                                true,
                                $useToken,
                                $timeout,
                            ),
                        );
                    }

                    try {
                        return $this->handleJsonPayload($response, $endpoint, $method, $params, $data);
                    } catch (KworkHTTPException $e) {
                        if (
                            $enableRetry
                            && $e->status !== null
                            && $this->shouldRetryStatus($e->status)
                            && $attempt < $attemptsLimit
                        ) {
                            $retryAfter = $e->status === 429 ? $this->parseRetryAfterSeconds($response) : null;
                            $delay = $this->computeBackoff($attempt);
                            if ($retryAfter !== null) {
                                $delay = min(max($delay, $retryAfter), $this->retryBackoffMax);
                            }

                            return $this->delay($delay)->then(
                                fn (): PromiseInterface => $this->attemptJsonRequest(
                                    $method,
                                    $endpoint,
                                    $headers,
                                    $params,
                                    $data,
                                    $cookies,
                                    $enableRetry,
                                    $attemptsLimit,
                                    $attempt + 1,
                                    $authResetDone,
                                    $useToken,
                                    $timeout,
                                ),
                            );
                        }

                        throw $e;
                    }
                },
                function (\Throwable $e) use (
                    $method,
                    $endpoint,
                    $headers,
                    $params,
                    $data,
                    $cookies,
                    $enableRetry,
                    $attemptsLimit,
                    $attempt,
                    $authResetDone,
                    $useToken,
                    $timeout,
                ): PromiseInterface {
                    if (!$enableRetry || $attempt >= $attemptsLimit) {
                        throw new KworkRetryExceeded(
                            sprintf(
                                'Request %s /%s failed after %d attempts: %s',
                                strtoupper($method),
                                $endpoint,
                                $attempt,
                                KworkAPI::formatExceptionShort($e),
                            ),
                            $attempt,
                            $e,
                        );
                    }

                    return $this->delay($this->computeBackoff($attempt))->then(
                        fn (): PromiseInterface => $this->attemptJsonRequest(
                            $method,
                            $endpoint,
                            $headers,
                            $params,
                            $data,
                            $cookies,
                            $enableRetry,
                            $attemptsLimit,
                            $attempt + 1,
                            $authResetDone,
                            $useToken,
                            $timeout,
                        ),
                    );
                },
            );
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
                requestParams: $requestParams !== null ? KworkAPI::redactSensitive($requestParams) : null,
                requestBody: is_array($requestBody) ? KworkAPI::redactSensitive($requestBody) : null,
            );
        }

        if (!is_array($data)) {
            throw new KworkHTTPException(
                sprintf('Non-JSON response from /%s: %s', $endpoint, $this->truncate($bodyText)),
                status: $status,
                method: strtoupper($method),
                endpoint: $endpoint,
                responseText: $bodyText,
                requestParams: $requestParams !== null ? KworkAPI::redactSensitive($requestParams) : null,
            );
        }

        if (!($data['success'] ?? false)) {
            $error = $data['error'] ?? 'Unknown API error';
            throw new KworkException(is_string($error) ? $error : 'Unknown API error');
        }

        return $data;
    }

    /**
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed> $params
     * @return PromiseInterface<ApiResponse>
     */
    public function requestMultipartAsync(
        string $endpoint,
        bool $useToken = false,
        ?array $fields = null,
        array $params = [],
        ?array $headers = null,
        ?array $cookies = null,
        ?bool $retry = null,
        ?float $timeout = null,
        ?int $maxAttempts = null,
    ): PromiseInterface {
        $filtered = $this->filterParams($params);

        $prepare = function (string $token = null) use ($endpoint, $useToken, $fields, $filtered, $headers, $cookies, $timeout): PromiseInterface {
            $requestParams = $filtered;
            if ($useToken && $token !== null) {
                $requestParams['token'] = $token;
            }

            $requestHeaders = $this->buildHeaders($headers);
            $multipart = $this->buildMultipart($fields, $params['files'] ?? null);

            $options = [
                RequestOptions::HEADERS => $requestHeaders,
                RequestOptions::QUERY => $requestParams,
                RequestOptions::MULTIPART => $multipart,
            ];

            if ($cookies !== null) {
                $options[RequestOptions::COOKIES] = CookieJar::fromArray(
                    $cookies,
                    parse_url($this->formatEndpoint($endpoint), PHP_URL_HOST) ?: 'api.kwork.ru',
                );
            }

            if ($timeout !== null) {
                $options[RequestOptions::TIMEOUT] = $timeout;
            }

            return $this->getHttpClient()
                ->requestAsync('POST', $this->formatEndpoint($endpoint), $options)
                ->then(fn ($response) => $this->handleJsonPayload($response, $endpoint, 'post', $requestParams, null));
        };

        if ($useToken) {
            return $this->getTokenAsync()->then(fn (string $token): PromiseInterface => $prepare($token));
        }

        return $prepare();
    }

    /**
     * @param array<string, mixed>|null $fields
     * @param array<string, mixed>|null $files
     * @return list<array<string, mixed>>
     */
    private function buildMultipart(?array $fields, ?array $files): array
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

                $filename = (string) $field;
                $contentType = 'application/octet-stream';
                $value = $spec;

                if (is_array($spec) && isset($spec[0], $spec[1])) {
                    $filename = (string) $spec[0];
                    $value = $spec[1];
                    $contentType = isset($spec[2]) ? (string) $spec[2] : $contentType;
                } elseif (is_string($spec) && is_file($spec)) {
                    $filename = basename($spec);
                    $contentType = mime_content_type($spec) ?: $contentType;
                    $value = fopen($spec, 'rb');
                }

                $multipart[] = [
                    'name' => (string) $field,
                    'contents' => $value,
                    'filename' => $filename,
                    'headers' => ['Content-Type' => $contentType],
                ];
            }
        }

        return $multipart;
    }

    /**
     * Run multiple API calls concurrently and return results in order.
     *
     * @param list<PromiseInterface<ApiResponse>> $promises
     * @return list<ApiResponse>
     */
    public static function all(array $promises): array
    {
        return Utils::unwrap($promises);
    }

    /**
     * @param array<string, string>|null $headers
     * @return array<string, string>
     */
    private function buildHeaders(?array $headers): array
    {
        $requestHeaders = ['Authorization' => KworkAPI::AUTH_HEADER];

        return $headers !== null ? array_merge($requestHeaders, $headers) : $requestHeaders;
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
        return array_filter($params, static fn (mixed $value): bool => $value !== null);
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

        return $timestamp === false ? null : max(0.0, (float) ($timestamp - time()));
    }

    public function truncate(string $text, int $limit = 2048): string
    {
        if (strlen($text) <= $limit) {
            return $text;
        }

        return substr($text, 0, $limit) . '...<truncated>';
    }

    /**
     * @return PromiseInterface<null>
     */
    private function delay(float $seconds): PromiseInterface
    {
        if ($seconds <= 0) {
            return \GuzzleHttp\Promise\Create::promiseFor(null);
        }

        return \GuzzleHttp\Promise\Create::promiseFor(null)->then(static function () use ($seconds): void {
            usleep((int) ($seconds * 1_000_000));
        });
    }
}

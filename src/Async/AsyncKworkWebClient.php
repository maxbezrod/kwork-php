<?php

declare(strict_types=1);

namespace Kwork\Async;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use Kwork\KworkWebClient;
use Kwork\WebLoginResult;

/**
 * Async web client that reuses the authenticated mobile API session.
 */
class AsyncKworkWebClient
{
    public function __construct(
        private readonly AsyncKworkAPI $api,
        private string $baseUrl = KworkWebClient::DEFAULT_WEB_BASE_URL,
    ) {
        $this->baseUrl = rtrim($this->baseUrl, '/') . '/';
    }

    /**
     * @return PromiseInterface<WebLoginResult>
     */
    public function loginViaMobileWebAuthTokenAsync(
        ?string $urlToRedirect = '/',
        ?string $userAgent = null,
        bool $allowRedirects = true,
        int $maxRedirects = 10,
        ?float $timeout = null,
    ): PromiseInterface {
        if ($urlToRedirect !== null && !str_starts_with($urlToRedirect, '/')) {
            throw new \InvalidArgumentException("urlToRedirect must be a relative URL starting with '/'");
        }

        return $this->api->requestAsync('post', 'getWebAuthToken', true, [
            'url_to_redirect' => $urlToRedirect,
        ])->then(function (array $tokenResp) use ($urlToRedirect, $userAgent, $allowRedirects, $maxRedirects, $timeout): PromiseInterface|WebLoginResult {
            $payload = is_array($tokenResp['response'] ?? null) ? $tokenResp['response'] : [];
            $loginUrl = $payload['url'] ?? null;

            if (!is_string($loginUrl) || $loginUrl === '') {
                throw new \RuntimeException('Unexpected getWebAuthToken response: ' . json_encode($tokenResp));
            }

            $options = [
                RequestOptions::ALLOW_REDIRECTS => $allowRedirects ? ['max' => $maxRedirects] : false,
            ];

            if ($userAgent !== null) {
                $options[RequestOptions::HEADERS] = ['User-Agent' => $userAgent];
            }

            if ($timeout !== null) {
                $options[RequestOptions::TIMEOUT] = $timeout;
            }

            return $this->api->getHttpClient()->requestAsync('GET', $loginUrl, $options)->then(
                function ($response) use ($payload, $loginUrl, $urlToRedirect, $options): WebLoginResult|PromiseInterface {
                    $finalUrl = (string) $response->getHeaderLine('X-Guzzle-Redirect-History');
                    if ($finalUrl === '') {
                        $finalUrl = $loginUrl;
                    } else {
                        $parts = explode(', ', $finalUrl);
                        $finalUrl = end($parts) ?: $loginUrl;
                    }

                    if ($urlToRedirect) {
                        $targetUrl = $this->baseUrl . ltrim($urlToRedirect, '/');

                        return $this->api->getHttpClient()->requestAsync('GET', $targetUrl, $options)->then(
                            function ($response2) use ($payload, $loginUrl, $urlToRedirect, $targetUrl): WebLoginResult {
                                $redirectHistory = $response2->getHeaderLine('X-Guzzle-Redirect-History');
                                $finalUrl = $redirectHistory !== '' ? (end(explode(', ', $redirectHistory)) ?: $targetUrl) : $targetUrl;

                                return new WebLoginResult(
                                    token: isset($payload['token']) ? (string) $payload['token'] : null,
                                    expiresAt: isset($payload['expires_at']) ? (int) $payload['expires_at'] : null,
                                    loginUrl: $loginUrl,
                                    urlToRedirect: isset($payload['url_to_redirect']) ? (string) $payload['url_to_redirect'] : null,
                                    finalUrl: $finalUrl,
                                    status: $response2->getStatusCode(),
                                );
                            },
                        );
                    }

                    return new WebLoginResult(
                        token: isset($payload['token']) ? (string) $payload['token'] : null,
                        expiresAt: isset($payload['expires_at']) ? (int) $payload['expires_at'] : null,
                        loginUrl: $loginUrl,
                        urlToRedirect: isset($payload['url_to_redirect']) ? (string) $payload['url_to_redirect'] : null,
                        finalUrl: $finalUrl,
                        status: $response->getStatusCode(),
                    );
                },
            );
        });
    }
}

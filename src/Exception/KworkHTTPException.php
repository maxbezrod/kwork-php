<?php

declare(strict_types=1);

namespace Kwork\Exception;

/**
 * HTTP-level error (non-2xx or invalid/undecodable response).
 *
 * @phpstan-type JsonDict array<string, mixed>
 */
class KworkHTTPException extends KworkException
{
    public function __construct(
        string $message,
        public readonly ?int $status = null,
        public readonly ?string $method = null,
        public readonly ?string $endpoint = null,
        public readonly ?string $responseText = null,
        /** @var JsonDict|null */
        public readonly ?array $responseJson = null,
        /** @var array<string, mixed>|null */
        public readonly ?array $requestParams = null,
        public readonly mixed $requestBody = null,
        /** @var array<string, string> */
        public readonly array $responseHeaders = [],
    ) {
        parent::__construct($message);
    }
}

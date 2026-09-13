<?php

declare(strict_types=1);

namespace Kwork;

readonly class WebLoginResult
{
    public function __construct(
        public ?string $token,
        public ?int $expiresAt,
        public string $loginUrl,
        public ?string $urlToRedirect,
        public ?string $finalUrl,
        public ?int $status,
    ) {
    }
}

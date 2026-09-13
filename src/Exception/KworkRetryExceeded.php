<?php

declare(strict_types=1);

namespace Kwork\Exception;

use Throwable;

class KworkRetryExceeded extends KworkException
{
    public function __construct(
        string $message,
        public readonly int $attempts,
        public readonly ?Throwable $lastError = null,
    ) {
        parent::__construct($message, 0, $lastError);
    }
}

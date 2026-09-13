<?php

declare(strict_types=1);

namespace Kwork\Traits;

use GuzzleHttp\Promise\PromiseInterface;

/**
 * Async endpoints found in the decompiled app but missing from docs/openapi.json.
 */
trait AsyncAPKExtraMethodsTrait
{
    /**
     * @param array<string, mixed> $params
     * @return PromiseInterface<array<string, mixed>>
     */
    public function getPublicFeaturesAsync(bool $useToken = false, array $params = []): PromiseInterface
    {
        return $this->requestAsync('post', 'getPublicFeatures', $useToken, $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return PromiseInterface<array<string, mixed>>
     */
    public function tosAsync(bool $useToken = false, array $params = []): PromiseInterface
    {
        return $this->requestAsync('post', 'tos', $useToken, $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return PromiseInterface<array<string, mixed>>
     */
    public function validateEventAsync(bool $useToken = true, array $params = []): PromiseInterface
    {
        return $this->requestAsync('post', 'validateEvent', $useToken, $params);
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Traits;

/**
 * Endpoints found in the decompiled app but missing from docs/openapi.json.
 */
trait APKExtraMethodsTrait
{
    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getPublicFeatures(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'getPublicFeatures', $useToken, $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function tos(bool $useToken = false, array $params = []): array
    {
        return $this->request('post', 'tos', $useToken, $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function validateEvent(bool $useToken = true, array $params = []): array
    {
        return $this->request('post', 'validateEvent', $useToken, $params);
    }
}

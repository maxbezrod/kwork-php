<?php

declare(strict_types=1);

namespace Kwork\Schema;

class Achievement extends DataModel
{
    public ?int $id = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $imageUrl = null;

    protected function aliases(): array
    {
        return ['imageUrl' => 'image_url'];
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Schema;

class KworkMinObject extends DataModel
{
    public ?int $id = null;
    public ?string $title = null;
    public ?int $active = null;
    public ?bool $feat = null;
}

class Writer extends DataModel
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $profilepicture = null;
}

class Review extends DataModel
{
    public ?int $id = null;
    public ?int $timeAdded = null;
    public ?string $text = null;
    public ?string $autoMode = null;
    public ?bool $good = null;
    public ?bool $bad = null;
    public ?KworkMinObject $kwork = null;
    public ?Writer $writer = null;

    protected function aliases(): array
    {
        return [
            'timeAdded' => 'time_added',
            'autoMode' => 'auto_mode',
        ];
    }
}

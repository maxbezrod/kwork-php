<?php

declare(strict_types=1);

namespace Kwork\Schema;

class Connects extends DataModel
{
    public ?int $allConnects = null;
    public ?int $activeConnects = null;
    public ?int $updateTime = null;

    protected function aliases(): array
    {
        return [
            'allConnects' => 'all_connects',
            'activeConnects' => 'active_connects',
            'updateTime' => 'update_time',
        ];
    }
}

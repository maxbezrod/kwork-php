<?php

declare(strict_types=1);

namespace Kwork\Schema;

class PortfolioItem extends DataModel
{
    public ?int $id = null;
    public ?string $title = null;
    public int|string|null $orderId = null;
    public ?int $categoryId = null;
    public ?string $categoryName = null;
    public ?string $itemType = null;
    public ?string $photo = null;
    public ?string $video = null;
    public ?int $likes = null;
    public ?int $likesDirty = null;
    public ?int $views = null;
    public ?int $viewsDirty = null;
    public ?int $commentsCount = null;
    public ?bool $isLiked = null;
    /** @var list<array<string, mixed>>|null */
    public ?array $images = null;
    /** @var list<array<string, mixed>>|null */
    public ?array $videos = null;
    public ?string $duplicateFrom = null;

    protected function aliases(): array
    {
        return [
            'orderId' => 'order_id',
            'categoryId' => 'category_id',
            'categoryName' => 'category_name',
            'itemType' => 'type',
            'likesDirty' => 'likes_dirty',
            'viewsDirty' => 'views_dirty',
            'commentsCount' => 'comments_count',
            'isLiked' => 'is_liked',
            'duplicateFrom' => 'duplicate_from',
        ];
    }
}

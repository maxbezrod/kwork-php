<?php

declare(strict_types=1);

namespace Kwork\Schema;

class Cover extends DataModel
{
    public ?string $phone = null;
    public ?string $tablet = null;
}

class Worker extends DataModel
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $fullname = null;
    public ?string $profilepicture = null;
    public ?float $rating = null;
    public ?int $reviewsCount = null;
    public ?int $ratingCount = null;
    public ?bool $isOnline = null;

    protected function aliases(): array
    {
        return [
            'reviewsCount' => 'reviews_count',
            'ratingCount' => 'rating_count',
            'isOnline' => 'is_online',
        ];
    }
}

class Activity extends DataModel
{
    public ?int $views = null;
    public ?int $orders = null;
    public ?int $earned = null;
}

class KworkObject extends DataModel
{
    public ?int $id = null;
    public ?int $categoryId = null;
    public ?string $categoryName = null;
    public ?int $statusId = null;
    public ?string $statusName = null;
    public ?string $title = null;
    public ?string $url = null;
    public ?string $imageUrl = null;
    public ?Cover $cover = null;
    public ?int $price = null;
    public ?bool $isPriceFrom = null;
    public ?bool $isFrom = null;
    public ?string $photo = null;
    public ?bool $isBest = null;
    public ?bool $isHidden = null;
    public ?bool $isFavorite = null;
    public ?string $lang = null;
    public ?Worker $worker = null;
    public ?Activity $activity = null;
    /** @var list<mixed>|null */
    public ?array $editsList = null;
    public ?int $profileSort = null;
    public ?bool $isSubscription = null;
    /** @var list<mixed>|null */
    public ?array $badges = null;

    protected function aliases(): array
    {
        return [
            'categoryId' => 'category_id',
            'categoryName' => 'category_name',
            'statusId' => 'status_id',
            'statusName' => 'status_name',
            'imageUrl' => 'image_url',
            'isPriceFrom' => 'is_price_from',
            'isFrom' => 'is_from',
            'isBest' => 'is_best',
            'isHidden' => 'is_hidden',
            'isFavorite' => 'is_favorite',
            'editsList' => 'edits_list',
            'profileSort' => 'profile_sort',
            'isSubscription' => 'isSubscription',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Schema;

class User extends DataModel
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $status = null;
    public ?string $fullname = null;
    public ?string $profilepicture = null;
    public ?string $description = null;
    public ?string $slogan = null;
    public ?string $location = null;
    public ?float $rating = null;
    public ?int $ratingCount = null;
    public ?string $levelDescription = null;
    public ?int $goodReviews = null;
    public ?int $badReviews = null;
    public ?bool $online = null;
    public ?int $liveDate = null;
    public ?string $cover = null;
    public ?int $customRequestMinBudget = null;
    public ?int $isAllowCustomRequest = null;
    public ?int $orderDonePersent = null;
    public ?int $orderDoneIntimePersent = null;
    public ?int $orderDoneRepeatPersent = null;
    public ?int $timezoneId = null;
    public ?bool $blockedByUser = null;
    public ?bool $allowedDialog = null;
    public ?int $addtime = null;
    /** @var list<Achievement>|null */
    public ?array $achievmentsList = null;
    public ?int $completedOrdersCount = null;
    public ?string $specialization = null;
    public ?string $profession = null;
    public ?int $kworksCount = null;
    /** @var list<KworkObject>|null */
    public ?array $kworks = null;
    /** @var list<PortfolioItem>|null */
    public ?array $portfolioList = null;
    /** @var list<Review>|null */
    public ?array $reviews = null;

    protected function aliases(): array
    {
        return [
            'ratingCount' => 'rating_count',
            'levelDescription' => 'level_description',
            'goodReviews' => 'good_reviews',
            'badReviews' => 'bad_reviews',
            'liveDate' => 'live_date',
            'customRequestMinBudget' => 'custom_request_min_budget',
            'isAllowCustomRequest' => 'is_allow_custom_request',
            'orderDonePersent' => 'order_done_persent',
            'orderDoneIntimePersent' => 'order_done_intime_persent',
            'orderDoneRepeatPersent' => 'order_done_repeat_persent',
            'timezoneId' => 'timezoneId',
            'blockedByUser' => 'blocked_by_user',
            'allowedDialog' => 'allowedDialog',
            'achievmentsList' => 'achievments_list',
            'completedOrdersCount' => 'completed_orders_count',
            'kworksCount' => 'kworks_count',
            'portfolioList' => 'portfolio_list',
        ];
    }
}

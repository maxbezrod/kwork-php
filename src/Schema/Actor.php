<?php

declare(strict_types=1);

namespace Kwork\Schema;

class Actor extends DataModel
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $status = null;
    public ?string $email = null;
    public ?string $type = null;
    public ?bool $verified = null;
    public ?string $profilePicture = null;
    public ?string $description = null;
    public ?string $slogan = null;
    public ?string $fullname = null;
    public ?string $levelDescription = null;
    public ?string $cover = null;
    public ?int $goodReviews = null;
    public ?int $badReviews = null;
    public ?string $location = null;
    public ?float $rating = null;
    public ?int $ratingCount = null;
    public ?int $totalAmount = null;
    public ?int $holdAmount = null;
    public ?int $freeAmount = null;
    public ?string $currency = null;
    public ?int $inboxArchiveCount = null;
    public ?int $unreadDialogCount = null;
    public ?int $notifyUnreadCount = null;
    public ?bool $redNotify = null;
    public ?int $warningInboxCount = null;
    public ?int $appNotifyCount = null;
    public ?int $unreadMessagesCount = null;
    public ?string $fullnameEn = null;
    public ?string $descriptionEn = null;
    public ?int $countryId = null;
    public ?int $cityId = null;
    public ?int $timezoneId = null;
    public ?int $addtime = null;
    public ?bool $allowMobilePush = null;
    public ?bool $isMorePayer = null;
    public ?int $kworksCount = null;
    public ?int $favouriteKworksCount = null;
    public ?int $hiddenKworksCount = null;
    public ?string $specialization = null;
    public ?string $profession = null;
    public ?bool $kworksAvailableAtWeekends = null;
    /** @var list<Achievement>|null */
    public ?array $achievmentsList = null;
    public ?int $completedOrdersCount = null;
    /** @var list<KworkObject>|null */
    public ?array $kworks = null;
    /** @var list<PortfolioItem>|null */
    public ?array $portfolioList = null;
    /** @var list<Review>|null */
    public ?array $reviews = null;
    public ?string $workerStatus = null;
    public ?bool $hasOffers = null;
    public ?int $wantsCount = null;
    public ?int $offersCount = null;
    public ?int $archivedWantsCount = null;
    public ?bool $pushNotificationsSoundAllowed = null;
    public ?bool $blackFridayForSellers = null;

    protected function aliases(): array
    {
        return [
            'profilePicture' => 'profilepicture',
            'levelDescription' => 'level_description',
            'goodReviews' => 'good_reviews',
            'badReviews' => 'bad_reviews',
            'ratingCount' => 'rating_count',
            'totalAmount' => 'total_amount',
            'holdAmount' => 'hold_amount',
            'freeAmount' => 'free_amount',
            'inboxArchiveCount' => 'inbox_archive_count',
            'unreadDialogCount' => 'unread_dialog_count',
            'notifyUnreadCount' => 'notify_unread_count',
            'redNotify' => 'red_notify',
            'warningInboxCount' => 'warning_inbox_count',
            'appNotifyCount' => 'app_notify_count',
            'unreadMessagesCount' => 'unread_messages_count',
            'fullnameEn' => 'fullnameEn',
            'descriptionEn' => 'descriptionEn',
            'countryId' => 'country_id',
            'cityId' => 'city_id',
            'timezoneId' => 'timezone_id',
            'allowMobilePush' => 'allow_mobile_push',
            'isMorePayer' => 'is_more_payer',
            'kworksCount' => 'kworks_count',
            'favouriteKworksCount' => 'favourite_kworks_count',
            'hiddenKworksCount' => 'hidden_kworks_count',
            'kworksAvailableAtWeekends' => 'kworks_available_at_weekends',
            'achievmentsList' => 'achievments_list',
            'completedOrdersCount' => 'completed_orders_count',
            'portfolioList' => 'portfolio_list',
            'workerStatus' => 'worker_status',
            'hasOffers' => 'has_offers',
            'wantsCount' => 'wants_count',
            'offersCount' => 'offers_count',
            'archivedWantsCount' => 'archived_wants_count',
            'pushNotificationsSoundAllowed' => 'pushNotificationsSoundAllowed',
            'blackFridayForSellers' => 'black_friday_for_sellers',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Kwork\Schema;

class Project extends DataModel
{
    public ?int $id = null;
    public ?int $userId = null;
    public ?string $username = null;
    public ?string $profilePicture = null;
    public ?int $price = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?int $offers = null;
    public ?int $timeLeft = null;
    public ?int $parentCategoryId = null;
    public ?int $categoryId = null;
    public ?int $dateConfirm = null;
    /** @var list<Achievement>|null */
    public ?array $achievementsList = null;

    protected function aliases(): array
    {
        return [
            'userId' => 'user_id',
            'profilePicture' => 'profile_picture',
            'timeLeft' => 'time_left',
            'parentCategoryId' => 'parent_category_id',
            'categoryId' => 'category_id',
            'dateConfirm' => 'date_confirm',
            'achievementsList' => 'achievements_list',
        ];
    }
}

class WantWorker extends DataModel
{
    public ?int $id = null;
    public ?string $status = null;
    public ?int $userId = null;
    public ?string $username = null;
    public ?string $profilePicture = null;
    public ?int $price = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?int $offers = null;
    public ?int $timeLeft = null;
    public ?int $parentCategoryId = null;
    public ?int $categoryId = null;
    public ?int $dateConfirm = null;
    public ?int $categoryBasePrice = null;
    public ?int $userProjectsCount = null;
    public ?int $userHiredPercent = null;
    public ?int $userActiveProjectsCount = null;
    /** @var list<Achievement>|null */
    public ?array $achievementsList = null;
    public ?bool $isViewed = null;
    public ?int $alreadyWork = null;
    public ?bool $allowHigherPrice = null;
    public ?int $possiblePriceLimit = null;
    public ?int $userNeedPortfolio = null;
    public ?string $userNeedPortfolioRubricName = null;

    protected function aliases(): array
    {
        return [
            'userId' => 'user_id',
            'profilePicture' => 'profile_picture',
            'timeLeft' => 'time_left',
            'parentCategoryId' => 'parent_category_id',
            'categoryId' => 'category_id',
            'dateConfirm' => 'date_confirm',
            'categoryBasePrice' => 'category_base_price',
            'userProjectsCount' => 'user_projects_count',
            'userHiredPercent' => 'user_hired_percent',
            'userActiveProjectsCount' => 'user_active_projects_count',
            'achievementsList' => 'achievements_list',
            'isViewed' => 'is_viewed',
            'alreadyWork' => 'already_work',
            'allowHigherPrice' => 'allow_higher_price',
            'possiblePriceLimit' => 'possible_price_limit',
            'userNeedPortfolio' => 'user_need_portfolio',
            'userNeedPortfolioRubricName' => 'user_need_portfolio_rubric_name',
        ];
    }
}

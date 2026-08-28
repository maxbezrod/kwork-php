<?php

declare(strict_types=1);

namespace Kwork\Schema;

class Category extends DataModel
{
    public ?int $id = null;
    public ?string $name = null;
    public ?string $description = null;
}

class SubCategory extends Category
{
    /** @var list<Category>|null */
    public ?array $subcategories = null;
}

class ParentCategory extends Category
{
    /** @var list<SubCategory>|null */
    public ?array $subcategories = null;
}

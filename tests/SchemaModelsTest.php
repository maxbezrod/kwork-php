<?php

declare(strict_types=1);

namespace Kwork\Tests;

use Kwork\Schema\Category;
use Kwork\Schema\DialogMessage;
use Kwork\Schema\KworkObject;
use Kwork\Schema\ParentCategory;
use Kwork\Schema\SubCategory;
use PHPUnit\Framework\TestCase;

final class SchemaModelsTest extends TestCase
{
    public function testDialogMessageAliasesAndNestedLastMessage(): void
    {
        $obj = DialogMessage::fromArray([
            'user_id' => 123,
            'username' => 'u',
            'lastOnlineTime' => 1700000000,
            'allowedDialog' => true,
            'lastMessage' => [
                'fromUsername' => 'bob',
                'fromUserId' => 5,
                'profilePicture' => 'pic.png',
                'message' => 'hi',
            ],
            'extraField' => 'ignored',
        ]);

        self::assertSame(123, $obj->userId);
        self::assertSame(1700000000, $obj->lastOnlineTime);
        self::assertTrue($obj->allowedDialog);
        self::assertNotNull($obj->lastMessageObj);
        self::assertSame('bob', $obj->lastMessageObj->fromUsername);
        self::assertSame(5, $obj->lastMessageObj->fromUserId);
        self::assertSame('pic.png', $obj->lastMessageObj->profilePicture);
        self::assertSame('hi', $obj->lastMessageObj->message);

        $dumped = $obj->toArrayByAlias();
        self::assertArrayHasKey('lastOnlineTime', $dumped);
        self::assertArrayHasKey('allowedDialog', $dumped);
        self::assertArrayHasKey('lastMessage', $dumped);
    }

    public function testCategoryInheritanceParsesNestedTree(): void
    {
        $data = [
            'id' => 1,
            'name' => 'Root',
            'subcategories' => [
                [
                    'id' => 2,
                    'name' => 'Sub',
                    'subcategories' => [
                        ['id' => 3, 'name' => 'Leaf'],
                    ],
                ],
            ],
        ];

        $root = ParentCategory::fromArray($data);
        self::assertNotNull($root->subcategories);
        self::assertInstanceOf(SubCategory::class, $root->subcategories[0]);
        self::assertNotNull($root->subcategories[0]->subcategories);
        self::assertInstanceOf(Category::class, $root->subcategories[0]->subcategories[0]);
        self::assertSame('Leaf', $root->subcategories[0]->subcategories[0]->name);
    }

    public function testKworkObjectParsesNestedModels(): void
    {
        $obj = KworkObject::fromArray([
            'id' => 1,
            'cover' => ['phone' => 'p.png', 'tablet' => 't.png'],
            'worker' => [
                'id' => 9,
                'username' => 'w',
                'rating' => 4.9,
            ],
            'activity' => ['views' => 10, 'orders' => 2],
            'isSubscription' => true,
        ]);

        self::assertNotNull($obj->cover);
        self::assertSame('p.png', $obj->cover->phone);
        self::assertNotNull($obj->worker);
        self::assertSame('w', $obj->worker->username);
        self::assertNotNull($obj->activity);
        self::assertSame(10, $obj->activity->views);
        self::assertTrue($obj->isSubscription);
    }
}

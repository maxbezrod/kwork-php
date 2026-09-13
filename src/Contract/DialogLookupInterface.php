<?php

declare(strict_types=1);

namespace Kwork\Contract;

use Kwork\Schema\DialogMessage;
use Kwork\Schema\InboxMessage;

interface DialogLookupInterface extends MessagingClientInterface
{
    /**
     * @return list<DialogMessage>
     */
    public function getDialogsPage(int $page = 1, ?string $excludedIds = null): array;

    /**
     * @return list<InboxMessage>
     */
    public function getDialogWithUser(string $username): array;
}

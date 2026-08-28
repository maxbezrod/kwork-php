<?php

declare(strict_types=1);

namespace Kwork\Schema;

enum EventType: string
{
    case IsTyping = 'is_typing';
    case Notify = 'notify';
    case NewMessage = 'new_inbox';
    case PopUpNotify = 'pop_up_notify';
    case MessageDelete = 'inbox_message_delete';
    case RemovePopUpNotify = 'remove_pop_up_notify';
    case DialogUpdate = 'dialog_updated';
}

enum Notify: string
{
    case NewMessage = 'new_message';
}

class BaseEvent extends DataModel
{
    public ?string $event = null;
    /** @var array<string, mixed>|null */
    public ?array $data = null;
}

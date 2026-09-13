<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Kwork\KworkBot;
use Kwork\Schema\Message;

$bot = new KworkBot(
    login: getenv('KWORK_LOGIN') ?: 'login',
    password: getenv('KWORK_PASSWORD') ?: 'password',
);

$bot->messageHandler(function (Message $message): void {
    $message->fastAnswer('Hello from kwork-php bot!');
});

$bot->run();

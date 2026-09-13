<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Kwork\Async\AsyncKworkClient;

$client = new AsyncKworkClient(
    login: getenv('KWORK_LOGIN') ?: 'login',
    password: getenv('KWORK_PASSWORD') ?: 'password',
    retryMaxAttempts: 3,
);

[$me, $categories] = AsyncKworkClient::all([
    $client->getMeAsync(),
    $client->getCategoriesAsync(),
]);

echo "User: {$me->username}, categories: " . count($categories) . "\n";

$client->close();

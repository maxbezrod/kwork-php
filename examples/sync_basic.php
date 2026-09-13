<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Kwork\KworkClient;

$client = new KworkClient(
    login: getenv('KWORK_LOGIN') ?: 'login',
    password: getenv('KWORK_PASSWORD') ?: 'password',
    retryMaxAttempts: 3,
);

$me = $client->getMe();
echo "Logged in as {$me->username}\n";

$client->close();

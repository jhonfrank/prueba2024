<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Api;

$api = Api::getInstance();

require __DIR__ . '/../app/routes/users.php';
require __DIR__ . '/../app/routes/transactions.php';

$api->run();
<?php

declare(strict_types=1);

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

date_default_timezone_set('America/Lima');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Api;

$api = Api::getInstance();

require __DIR__ . '/../app/routes/users.php';
require __DIR__ . '/../app/routes/transactions.php';

$api->run();
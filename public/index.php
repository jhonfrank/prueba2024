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
use App\Response;

$api = Api::getInstance();

try{
    require __DIR__ . '/../app/routes/users.php';
    require __DIR__ . '/../app/routes/transactions.php';
}
catch(Throwable $e){
    Response::InternalError($e->getMessage())->write();
    return;
}

/**
 * Ver el estado del servidor API.
 */
$api->addRoute($api::METHOD_GET, '/', function ($req, $args){
    return Response::Ok([], 'Running...');
});

$api->run();
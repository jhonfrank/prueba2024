<?php

declare(strict_types=1);

namespace App;

/**
 * Crear una nueva transaction.
 */
$api->addRoute('POST', '/api/v1/transactions', function ($req, $args){
    return Response::Ok([]);
});
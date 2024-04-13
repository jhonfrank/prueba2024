<?php

declare(strict_types=1);

namespace App;

use Src\Transaction\Application\TransactionService;
use Src\Transaction\Infrastructure\Persistence\MySQLTransactionRepository;
use Src\Transaction\Infrastructure\Auth\PaymentAuth;
use Src\Transaction\Infrastructure\Notification\SMSNotification;

$transaction_srv = new TransactionService(new MySQLTransactionRepository(), new PaymentAuth(), new SMSNotification());

/**
 * Obtener el listado de todas las transacciones.
 */
$api->addRoute($api::METHOD_GET, '/api/v1/transactions', function ($req, $args) use ($transaction_srv){

    $transactions = $transaction_srv->getAll();

    return Response::Ok([$transactions]);
});

/**
 * Obtener una transaccion por id.
 * 
 *  int {id}: Id de la transacción
 */
$api->addRoute($api::METHOD_GET, '/api/v1/transactions/{id}', function ($req, $args) use ($transaction_srv){

    $transaction = $transaction_srv->getById($args['id']);

    return Response::Ok($transaction);
});

/**
 * Crear una nueva transaction.
 */
$api->addRoute($api::METHOD_POST, '/api/v1/transactions', function ($req, $args) use ($transaction_srv){

    $transaction = $req['body'];

    $dateNow = date_create();
    $transaction['createdAt'] = $dateNow;
    $transaction['updatedAt'] = $dateNow;

    $transaction['id'] = 0;
    $transaction['isNotified'] = false;

    $transaction_srv->create($transaction);

    return Response::Ok([], 'Transaction created successfully.');
});
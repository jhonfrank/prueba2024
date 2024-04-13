<?php

declare(strict_types=1);

namespace App;

use Src\User\Application\UserService;
use Src\User\Infrastructure\Persistence\MySQLUserRepository;

use Src\Transaction\Application\TransactionService;
use Src\Transaction\Infrastructure\Persistence\MySQLTransactionRepository;
use Src\Transaction\Infrastructure\Auth\PaymentAuth;
use Src\Transaction\Infrastructure\Notification\SMSNotification;

$user_srv = new UserService(new MySQLUserRepository());
$transaction_srv = new TransactionService(new MySQLTransactionRepository(), new PaymentAuth(), new SMSNotification());

/**
 * Obtener el listado de todos los usuarios.
 */
$api->addRoute($api::METHOD_GET, '/api/v1/users', function ($req, $args) use ($user_srv) {

    $users = $user_srv->getAll();

    return Response::Ok($users);
});

/**
 * Obtener un usuario por id.
 * 
 *  int {id}: Id del usuario
 */
$api->addRoute($api::METHOD_GET, '/api/v1/users/{id}', function ($req, $args) use ($user_srv) {

    $user = $user_srv->getById($args['id']);

    return Response::Ok($user);
});

/**
 * Obtener las transacciones de un usuario.
 * 
 *  int {id}: Id del usuario
 */
$api->addRoute($api::METHOD_GET, '/api/v1/users/{id}/transactions', function ($req, $args) use ($user_srv, $transaction_srv) {

    $userTransactions = [
        'payerTransaction' => $transaction_srv->getByPayerUserId($args['id']),
        'payeeTransaction' => $transaction_srv->getByPayeeUserId($args['id'])
    ];

    return Response::Ok($userTransactions);
});

/**
 * Crear nuevo usuario.
 */
$api->addRoute($api::METHOD_POST, '/api/v1/users', function ($req, $args) use ($user_srv) {

    $user = $req['body'];

    $dateNow = date_create();
    $user['createdAt'] = $dateNow;
    $user['updatedAt'] = $dateNow;
    
    $user['id'] = 0;

    $user_srv->create($user);

    return Response::Ok([], 'User created successfully.');
});

/**
 * Modificar usuario.
 */
$api->addRoute($api::METHOD_PUT, '/api/v1/users/{id}', function ($req, $args) use ($user_srv) {

    $user = $req['body'];

    $user['id'] = $args['id'];
    $user['updatedAt'] = date_create();

    $user_srv->update($user);

    return Response::Ok([], 'User updated successfully.');
});
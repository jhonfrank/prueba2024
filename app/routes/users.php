<?php

declare(strict_types=1);

namespace App;

/**
 * Obtener el listado de todos los usuarios.
 */
$api->addRoute('GET', '/api/v1/users', function ($req, $args){
    return Response::Ok([]);
});

/**
 * Obtener un usuario por id.
 * 
 *  int {id}: Id del usuario
 */
$api->addRoute('GET', '/api/v1/users/{id}', function ($req, $args){
    return Response::Ok([]);
});

/**
 * Obtener las transacciones de un usuario.
 * 
 *  int {id}: Id del usuario
 */
$api->addRoute('GET', '/api/v1/users/{id}/transactions', function ($req, $args){
    return Response::Ok([]);
});

/**
 * Crear nuevo usuario.
 */

$api->addRoute('POST', '/api/v1/users', function ($req, $args){
    return Response::Ok([]);
});
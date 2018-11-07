<?php
$router->add('GET',     '/api/users',               '\App\Controllers\UsersController::all');
$router->add('GET', '/', function() {
    return file_get_contents(__DIR__ . '/../../template/index.html');
});

$router->add('POST',    '/auth/token',      '\App\Controllers\UsersController::getToken');

//Rota usada para criar usuários sem precisar de Token, feita para criar o administrador o sistema
$router->add('POST',    '/auth/register',   '\App\Controllers\UsersController::create');

$router->add('GET',     '/api/me', function($c) {
    header('Content-Type: application/json');    
    $data = (array)(new \App\Controllers\UsersController)->getCurrentUser($c)['user'];
    $response['id'] = $data['id'];
    $response['name'] = $data['name'];
    $response['type'] = $data['type'];
    return $response;
});

$router->add('POST',    '/api/user',                '\App\Controllers\UsersController::create');
$router->add('PUT',     '/api/user',                '\App\Controllers\UsersController::update');
$router->add('DELETE',  '/api/user',                '\App\Controllers\UsersController::delete');
$router->add('GET',     '/api/users',               '\App\Controllers\UsersController::all');

$router->add('GET',     '/api/teachers',            '\App\Controllers\UsersController::getTeachers');
$router->add('GET',     '/api/edit_permission',     '\App\Controllers\UsersController::getEditPermission');

$router->add('GET',     '/api/user/turmas',         '\App\Controllers\UsersController::getUsersTurmas');
$router->add('POST',    '/api/user/turmas',         '\App\Controllers\UsersController::createUsersTurmas');
$router->add('DELETE',  '/api/user/turmas',         '\App\Controllers\UsersController::deleteUsersTurmas');

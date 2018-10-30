<?php

$router->add('GET', '/', function() {
    return file_get_contents(__DIR__ . '/../../template/index.html');
});

$router->add('POST',    '/auth/token',              '\App\Controllers\UsersController::getToken'); //getToken

$router->add('GET',     '/api/edit_permission',     '\App\Controllers\UsersController::getEditPermission');
$router->add('POST',    '/api/user',           '\App\Controllers\UsersController::create');
$router->add('PUT',    '/api/user',           '\App\Controllers\UsersController::update');
$router->add('DELETE',    '/api/user',           '\App\Controllers\UsersController::delete');
$router->add('GET',     '/api/user', function($c) {
    header('Content-Type: applcation/json');    
    $data = (array)(new \App\Controllers\UsersController)->getCurrentUser($c)['user'];
    $response['id'] = $data['id'];
    $response['name'] = $data['name'];
    $response['user_type'] = $data['user_type'];
    return $response;
});
$router->add('GET',    '/api/teachers',   '\App\Controllers\UsersController::getTeachers');
$router->add('GET',    '/api/user_getall',   '\App\Controllers\UsersController::getUsers');
$router->add('GET',    '/api/user/turmas',   '\App\Controllers\UsersController::getUsersTurmas');
$router->add('POST',    '/api/users_turmas',   '\App\Controllers\UsersController::createUsersTurmas');

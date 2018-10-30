<?php

$router->add('GET', '/api/projects',    'UNI\Framework\Tasks\Controllers\ProjectsController::index');
$router->add('POST', '/api/projects',   'UNI\Framework\Tasks\Controllers\ProjectsController::create');
$router->add('PUT', '/api/projects',    'UNI\Framework\Tasks\Controllers\ProjectsController::update');
$router->add('DELETE', '/api/projects', 'UNI\Framework\Tasks\Controllers\ProjectsController::delete');

$router->add('GET', '/api/sections', 'UNI\Framework\Tasks\Controllers\SectionsController::listByTurma');
$router->add('POST', '/api/sections', 'UNI\Framework\Tasks\Controllers\SectionsController::create');
$router->add('GET', '/api/sections/permission', 'UNI\Framework\Tasks\Controllers\SectionsController::havePermission');

$router->add('GET', '/api/tasks', 'UNI\Framework\Tasks\Controllers\TasksController::listByProject');
$router->add('POST', '/api/tasks', 'UNI\Framework\Tasks\Controllers\TasksController::create');

$router->add('GET', '/api/subtasks', 'UNI\Framework\Tasks\Controllers\SubTasksController::listByTask');
$router->add('POST', '/api/subtasks', 'UNI\Framework\Tasks\Controllers\SubTasksController::create');
$router->add('PUT', '/api/subtasks/(\d+)', 'UNI\Framework\Tasks\Controllers\SubTasksController::update');

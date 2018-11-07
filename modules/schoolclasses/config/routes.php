<?php

$router->add('GET',     '/api/schoolclasses',   'UNI\Framework\SchoolClasses\Controllers\SchoolClassController::index');
$router->add('POST',    '/api/schoolclasses',   'UNI\Framework\SchoolClasses\Controllers\SchoolClassController::create');
$router->add('PUT',     '/api/schoolclasses',   'UNI\Framework\SchoolClasses\Controllers\SchoolClassController::update');
$router->add('DELETE',  '/api/schoolclasses/(\d)+',   'UNI\Framework\SchoolClasses\Controllers\SchoolClassController::delete');

$router->add('GET',     '/api/lessons/(\d)+', 'UNI\Framework\SchoolClasses\Controllers\LessonsController::listByTurma');
$router->add('POST',    '/api/lessons', 'UNI\Framework\SchoolClasses\Controllers\LessonsController::create');
$router->add('GET',     '/api/lessons/permission/(\d)+', 'UNI\Framework\SchoolClasses\Controllers\LessonsController::havePermission');

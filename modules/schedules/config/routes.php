<?php

$router->add('GET', '/api/schedules', 'UNI\Framework\Schedules\Controllers\SchedulesController::index');
$router->add('POST', '/api/schedules', 'UNI\Framework\Schedules\Controllers\SchedulesController::create');

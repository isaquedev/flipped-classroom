<?php

$router->add('GET', '/api/questionnaires/?(\d)*',  'UNI\Framework\Questionnaires\Controllers\QuestionnaireController::index');
$router->add('POST', '/api/questionnaires',  'UNI\Framework\Questionnaires\Controllers\QuestionnaireController::create');
$router->add('PUT', '/api/questionnaires',  'UNI\Framework\Questionnaires\Controllers\QuestionnaireController::update');
$router->add('DELETE', '/api/questionnaires/(\d)+',  'UNI\Framework\Questionnaires\Controllers\QuestionnaireController::delete');

$router->add('POST', '/api/question/return',    'UNI\Framework\Questionnaires\Controllers\QuestionController::retorno');
$router->add('GET', '/api/question/(\d)+',        'UNI\Framework\Questionnaires\Controllers\QuestionController::index');
$router->add('POST', '/api/question',        'UNI\Framework\Questionnaires\Controllers\QuestionController::create');
$router->add('PUT', '/api/question',        'UNI\Framework\Questionnaires\Controllers\QuestionController::update');

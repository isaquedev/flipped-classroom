<?php

$container['events'] = function(){
    return new Zend\EventManager\EventManager;
};

$container['settings'] = function() {
    return [
        'online-db' => [
            'dsn' => 'mysql:host=us-cdbr-iron-east-01.cleardb.net',
            'database' => 'heroku_1bb70b6ea823f68',
            'username' => 'b8eb34bd2165da',
            'port' => '3306',
            'password' => '2f592e94',
            'options' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        ],
        'local-db' => [
            'dsn' => 'mysql:host=localhost',
            'database' => 'flipped_classroom_edu_system',
            'username' => 'root',
            'port' => '',
            'password' => '',
            'options' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        ], 
        'db' => [
            'online' => 'online-db',
            'offline' => 'local-db'
        ]
    ];
};

$container['db'] = function($c) {
    $isOnline = true;
    $db = $isOnline == true ? $c['settings']['db']['online'] : $c['settings']['db']['offline'];
    $dsn = $c['settings'][$db]['dsn'] . ';port=' . $c['settings'][$db]['port'] . ';dbname=' . $c['settings'][$db]['database'];
    $username = $c['settings'][$db]['username'];
    $password = $c['settings'][$db]['password'];
    $options = $c['settings'][$db]['options'];

    $pdo = new \PDO($dsn, $username, $password, $options);
    
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

$container['users_model'] = function ($c) {
    return new \App\Models\Users($c);
};

return $container;
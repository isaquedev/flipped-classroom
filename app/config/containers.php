<?php

$container['events'] = function(){
    return new Zend\EventManager\EventManager;
};

$container['settings'] = function() {
    return [
        'online-db' => [
            'dsn' => 'pgsql:host=ec2-184-73-169-151.compute-1.amazonaws.com',
            'database' => 'dbbkuhe3g5onj1',
            'username' => 'fvgxlekzbvteyd',
            'port' => '5432',
            'password' => '652feed25804ed3fbd3c5016995dd15893afaae94c569dafc96b1a328fd06f7d',
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
<?php

declare(strict_types=1);

return [
    'postgres' => [
        'host' => env('DB_PG_HOST', 'postgres'),
        'port' => (int) env('DB_PG_PORT', 5432),
        'database' => env('DB_PG_DATABASE', 'lemalagasy_db'),
        'username' => env('DB_PG_USERNAME', 'lemalagasy_user'),
        'password' => env('DB_PG_PASSWORD', 'lemalagasy_password'),
        'charset' => env('DB_PG_CHARSET', 'utf8'),
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],

    'mongodb' => [
        'host' => env('DB_MONGO_HOST', 'mongo'),
        'port' => (int) env('DB_MONGO_PORT', 27017),
        'database' => env('DB_MONGO_DATABASE', 'lemalagasy_db'),
        'username' => env('DB_MONGO_USERNAME', ''),
        'password' => env('DB_MONGO_PASSWORD', ''),
        'auth_source' => env('DB_MONGO_AUTH_SOURCE', 'admin'),
    ],
];

<?php

return [
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'mvcini',
        'charset' => 'utf8mb4',
        'user' => 'root',
        'pass' => '',
    ],
    'email' => [
        'host' => 'smtp.example.com',
        'port' => '587',
        'user' => 'user@example.com',
        'pass' => 'secret',
        'from_address' => 'noreply@example.com',
        'from_name' => 'MVCini',
    ],
    'default_lang' => 'en',
    'app_secret' => 'your_app_secret_here', // Change this to a secure random string
];

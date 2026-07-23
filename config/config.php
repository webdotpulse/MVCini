<?php

return [
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'dbname' => $_ENV['DB_NAME'] ?? 'mvcini',
        'charset' => 'utf8mb4',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
    ],
    'email' => [
        'host' => $_ENV['EMAIL_HOST'] ?? 'smtp.example.com',
        'port' => $_ENV['EMAIL_PORT'] ?? '587',
        'user' => $_ENV['EMAIL_USER'] ?? 'user@example.com',
        'pass' => $_ENV['EMAIL_PASS'] ?? 'secret',
        'from_address' => $_ENV['EMAIL_FROM_ADDRESS'] ?? 'noreply@example.com',
        'from_name' => $_ENV['EMAIL_FROM_NAME'] ?? 'MVCini',
    ],
    'default_lang' => $_ENV['DEFAULT_LANG'] ?? 'en',
    'app_secret' => $_ENV['APP_SECRET'] ?? 'your_app_secret_here', // Change this to a secure random string
];

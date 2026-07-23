<?php
session_start();

// Setup Native Autoloader
require_once __DIR__ . '/../src/Core/Autoloader.php';

// Load .env variables
\App\Core\Env::load(__DIR__ . '/../.env');

// Check for installer
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile) && file_exists(__DIR__ . '/install.php')) {
    header('Location: /install.php');
    exit;
}

// Load config
$config = require_once __DIR__ . '/../config/config.php';

// Composer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\I18n;
use App\Core\Database;

// Initialize Database connection
Database::init($config['db']);

// Initialize I18n
I18n::init($config['default_lang']);

// Enforce Session Security
\App\Core\Security::enforceSessionSecurity();

// Check Remember Me Cookie
\App\Controllers\AuthController::checkRememberMe();

// Initialize Router
$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);

<?php
session_start();

// Check for installer
$configFile = __DIR__ . '/../config/config.php';
if (!file_exists($configFile) && file_exists(__DIR__ . '/install.php')) {
    header('Location: /install.php');
    exit;
}

// Load config
$config = require_once $configFile;

// Composer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Setup Native Autoloader
require_once __DIR__ . '/../src/Core/Autoloader.php';

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

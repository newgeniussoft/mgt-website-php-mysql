<?php

// Load autoloader
require_once __DIR__ . '/autoloader.php';

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Start session
session_start();

// Load helpers
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/array_helpers.php';
require_once __DIR__ . '/../helpers/string_helpers.php';

// Setup database connection
try {
    $pdo = new \PDO(
        'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    \App\Models\Model::setConnection($pdo);
} catch (\PDOException $e) {
    logger('Database connection failed: ' . $e->getMessage(), 'error');
    die('Database connection failed');
}

$viewProvider = new \App\View\ViewServiceProvider();
$viewProvider->register();
$viewProvider->boot();
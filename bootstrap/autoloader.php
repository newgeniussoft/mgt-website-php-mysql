<?php

// Simple PSR-4 autoloader
spl_autoload_register(function ($class) {
    // Handle App namespace
    $appPrefix = 'App\\';
    $appBaseDir = __DIR__ . '/../app/';
    
    $len = strlen($appPrefix);
    if (strncmp($appPrefix, $class, $len) === 0) {
        $relativeClass = substr($class, $len);
        $file = $appBaseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }
    
    // Handle Database namespace
    $dbPrefix = 'Database\\';
    $dbBaseDir = __DIR__ . '/../database/';
    
    $len = strlen($dbPrefix);
    if (strncmp($dbPrefix, $class, $len) === 0) {
        $relativeClass = substr($class, $len);
        $file = $dbBaseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }
});

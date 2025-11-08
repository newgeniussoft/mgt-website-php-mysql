<?php

// Router script for PHP built-in server

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files from public directory
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false; // Let the built-in server handle static files
}

// Route all other requests to public/index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/public/index.php';

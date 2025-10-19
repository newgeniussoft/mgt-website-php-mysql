<?php
// PHP built-in server router script

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
// In your router or index.php
if (preg_match('#^/assets/(.+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    $file = __DIR__ . '/public/assets/' . $matches[1];
    
    if (file_exists($file) && is_file($file)) {
        // Set appropriate content type
        $mime = mime_content_type($file);
        header('Content-Type: ' . $mime);
        readfile($file);
        exit;
    }
}
// Serve static files directly from root or public directory
if ($uri !== '/') {
    // Check if file exists in root directory first
    if (file_exists(__DIR__ . $uri)) {
        return false;
    }
    
    // Rewrite /assets/* to /public/assets/*
    if (strpos($uri, '/assets/') === 0) {
        $publicAssetPath = __DIR__ . '/public' . $uri;
        if (file_exists($publicAssetPath)) {
            return false;
        }
    }
    
    // Then check in public directory for other files
    if (file_exists(__DIR__ . '/public' . $uri)) {
        return false;
    }
}

// Route all other requests to public/index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/public/index.php';

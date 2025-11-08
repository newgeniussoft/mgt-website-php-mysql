<?php
// PHP built-in server router script

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
// In your router or index.php
// In your router or index.php
if (preg_match('#^/assets/(.+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    $file = __DIR__ . '/public/assets/' . $matches[1];
    
    if (file_exists($file) && is_file($file)) {
        // Get file extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        
        // Set correct MIME type
        $mimeTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2'=> 'font/woff2',
            'ttf'  => 'font/ttf',
            'eot'  => 'application/vnd.ms-fontobject',
        ];
        
        $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';
        
        // IMPORTANT: Set the Content-Type header
        header('Content-Type: ' . $mimeType);
        
        // Optional: Add cache headers
        header('Cache-Control: public, max-age=31536000');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        // Output the file
        readfile($file);
        exit;
    } else {
        // File not found
        header("HTTP/1.0 404 Not Found");
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

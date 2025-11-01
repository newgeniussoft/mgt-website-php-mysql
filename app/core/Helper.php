<?php
include_once __DIR__ . '/../../config/env.php';
/**
 * Helper Functions
 *
 * This file contains various helper functions used throughout the application.
 */

 /**
  * Returns the main URL.
  *
  * @return string The fully main URL.
  */
function main_url(): string {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    return rtrim("{$protocol}://{$host}", '/');
}
/**
 * Returns the fully qualified URL for a given path.
 *
 * @param string $path The path to the resource.
 * @return string The fully qualified URL.
 */
function url(string $path = ''): string {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = ltrim($path, '/');

    // Check if current URL has language prefix
    $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

    // Add language prefix if needed
    if (!empty($urlParts[0]) && $urlParts[0] === 'es') {
        $path = 'es/' . ltrim($path, '/');
    }

    return rtrim("{$protocol}://{$host}", '/') . "/{$path}";
}

/**
 * Returns the current page.
 *
 * @return string The current page.
 */
function page(): string {
    $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    return $urlParts[0];
}

function page_admin(): string {
    $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    return $urlParts[1];
}

/**
 * Returns the path admin.
 *
 * @return string The path admin.
 */
function admin_route($path = '', $params = []): string {
    $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    if (empty($params)) {
        return '/' . $_ENV['PATH_ADMIN'] . '/' . $path;
    } else {
        $key = array_key_first($params);
        $value = $params[$key];
        return '/' . $_ENV['PATH_ADMIN'] . '/' . $path . '?'. $key . '=' . $value;
    }
}

/**
 * Returns the fully qualified URL for an asset.
 *
 * @param string $path The path to the asset.
 * @return string The fully qualified URL.
 */
function asset(string $path): string {
    $path = ltrim($path, '/');
    return main_url()."/assets/{$path}";
}

/**
 * Returns the fully qualified URL for a CSS file.
 *
 * @param string $file The name of the CSS file.
 * @return string The fully qualified URL.
 */
function css(string $file): string {
    return asset("css/{$file}");
}

/**
 * Returns the fully qualified URL for a JavaScript file.
 *
 * @param string $file The name of the JavaScript file.
 * @return string The fully qualified URL.
 */
function js(string $file): string {
    return asset("js/{$file}");
}

/**
 * Returns the fully qualified URL for an image file.
 *
 * @param string $file The name of the image file.
 * @return string The fully qualified URL.
 */
function image(string $file): string {
    return asset("images/{$file}");
}

/**
 * Returns the URL for a named route.
 *
 * @param string $name The name of the route.
 * @param array $params The parameters for the route.
 * @return string The URL for the route.
 */
function route(string $name, array $params = []): string {
    global $routes;
    if (!isset($routes[$name])) {
        return url('/');
    }
    $url = $routes[$name];
    foreach ($params as $key => $value) {
        $url = str_replace("{{$key}}", $value, $url);
    }
    return url($url);
}

/**
 * Switches the language for the application.
 *
 * @param string $lang The language code (e.g., 'en', 'es').
 */
function set_language(string $lang): string {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $supportedLanguages = ['es', 'en'];

            // Remove the first segment if it is a supported language code
            if (!empty($urlParts) && in_array($urlParts[0], $supportedLanguages)) {
                array_shift($urlParts);
            }

            // Build the new path
            $newPath = '';
            if ($lang === 'en') {
                // For English (default), no language prefix
                $newPath = implode('/', $urlParts);
            } else {
                // For other languages, add the language prefix
                $newPath = $lang . (count($urlParts) ? '/' . implode('/', $urlParts) : '');
            }

            return rtrim("{$protocol}://{$host}", '/') . ($newPath ? '/' . $newPath : '');
}

/**
 * Format file size for display
 *
 * @param int $bytes The file size in bytes
 * @return string The formatted file size
 */
function formatFileSize(int $bytes): string {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}
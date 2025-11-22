<?php

// Application helpers
function app($class = null) {
    static $container = [];
    
    if ($class === null) {
        return $container;
    }
    
    if (!isset($container[$class])) {
        $container[$class] = new $class();
    }
    
    return $container[$class];
}

function config($key, $default = null) {
    static $config = null;
    
    if ($config === null) {
        $config = require __DIR__ . '/../config/app.php';
    }
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    
    return $value;
}

function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

function dd(...$vars) {
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    die();
}

function asset($path) {
    return config('app.url') . '/' . ltrim($path, '/');
}

function url($path = '') {
    $baseUrl = rtrim(config('app.url'), '/');
    $locale = \App\Localization\Lang::getLocale();
    $defaultLocale = 'en';
    
    // Only add locale prefix if it's not the default locale
    $localePath = ($locale && $locale !== $defaultLocale) ? '/' . $locale : '';
    
    // Clean and build the path
    $path = '/' . ltrim($path, '/');
    
    return $baseUrl . $localePath . $path;
}

function page() {
    
    $segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
    
    if (isset($segments[0]) && in_array($segments[0], ['es', 'en'])) {
        array_shift($segments);
    }
    
    return implode('/', $segments);
    
}

function page_admin() {
    
    $segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
    
    if (isset($segments[0]) && in_array($segments[0], ['es', 'en'])) {
        array_shift($segments);
    }
    $page = implode('/', $segments);
    $page = str_replace($_ENV['APP_ADMIN_PREFIX'] . '/', '', $page);
    
    return $page;
    
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function back() {
    redirect($_SERVER['HTTP_REFERER'] ?? '/');
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

function method_field($method) {
    return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
}

function session($key = null, $default = null) {
    if ($key === null) {
        return $_SESSION;
    }
    
    if (is_array($key)) {
        foreach ($key as $k => $v) {
            $_SESSION[$k] = $v;
        }
        return null;
    }
    
    return $_SESSION[$key] ?? $default;
}

function auth() {
    return new class {
        public function check() {
            return isset($_SESSION['user_id']);
        }
        
        public function user() {
            if (!$this->check()) {
                return null;
            }
            return \App\Models\User::find($_SESSION['user_id']);
        }
        
        public function id() {
            return $_SESSION['user_id'] ?? null;
        }
        
        public function login($user) {
            $_SESSION['user_id'] = $user->id;
        }
        
        public function logout() {
            unset($_SESSION['user_id']);
            session_destroy();
        }
    };
}

function response() {
    return new class {
        public function json($data, $status = 200) {
            http_response_code($status);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
        
        public function download($file, $name = null) {
            if (!file_exists($file)) {
                abort(404);
            }
            
            $name = $name ?? basename($file);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    };
}

// Helper function to add to helpers/functions.php
function view($template, $data = []) {
    return \App\View\View::make($template, $data);
}

function abort($code = 404, $message = '') {
    http_response_code($code);
    view("errors.404", ['message' => '404 Not Found']);
    exit;
}

function logger($message, $level = 'info') {
    $logFile = __DIR__ . '/../storage/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $log = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND);
}

// ==========================================
// 8. ARRAY HELPERS (helpers/array_helpers.php)
// ==========================================

function array_get($array, $key, $default = null) {
    if (!is_array($array)) {
        return $default;
    }
    
    if (isset($array[$key])) {
        return $array[$key];
    }
    
    foreach (explode('.', $key) as $segment) {
        if (!is_array($array) || !array_key_exists($segment, $array)) {
            return $default;
        }
        $array = $array[$segment];
    }
    
    return $array;
}

function array_only($array, $keys) {
    return array_intersect_key($array, array_flip((array) $keys));
}

function array_except($array, $keys) {
    return array_diff_key($array, array_flip((array) $keys));
}

function array_pluck($array, $value, $key = null) {
    $results = [];
    
    foreach ($array as $item) {
        $itemValue = is_object($item) ? $item->{$value} : $item[$value];
        
        if ($key === null) {
            $results[] = $itemValue;
        } else {
            $itemKey = is_object($item) ? $item->{$key} : $item[$key];
            $results[$itemKey] = $itemValue;
        }
    }
    
    return $results;
}

function currentUrlToEs() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'];
    $uri    = $_SERVER['REQUEST_URI'];

    // Remove leading slash
    $uri = ltrim($uri, '/');

    // Convert
    if ($uri === '') {
        return "$scheme://$host/es";
    }

    return "$scheme://$host/es/$uri";
}



function __($key, $replace = []) {
    return \App\Localization\Lang::get($key, $replace);
}

function trans($key, $replace = []) {
    return \App\Localization\Lang::get($key, $replace);
}

function trans_choice($key, $number, $replace = []) {
    return \App\Localization\Lang::choice($key, $number, $replace);
}

function app_locale() {
    return \App\Localization\Lang::getLocale();
}

function set_locale($locale) {
    \App\Localization\Lang::setLocale($locale);
}

function locale_url($locale, $path = '') {
    $currentUri = $_SERVER['REQUEST_URI'];
    $segments = explode('/', trim(parse_url($currentUri, PHP_URL_PATH), '/'));
    
    // Remove current locale if exists
    $supportedLocales = ['en', 'es'];
    if (!empty($segments[0]) && in_array($segments[0], $supportedLocales)) {
        array_shift($segments);
    }
    
    // Build new URL
    $baseUrl = rtrim(config('app.url'), '/');
    $newPath = '/' . $locale;
    
    if (!empty($segments)) {
        $newPath .= '/' . implode('/', $segments);
    }
    
    if ($path) {
        $newPath .= '/' . ltrim($path, '/');
    }
    
    return $baseUrl . $newPath;
}

function current_locale() {
    return \App\Localization\Lang::getLocale();
}

function admin_prefix() {
    return $_ENV['APP_ADMIN_PREFIX'] ?? 'cpanel';
}

function admin_url($path = '') {
    return url(admin_prefix() . '/' . ltrim($path, '/'));
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

function admin_user() {
    if (!is_admin_logged_in()) {
        return null;
    }
    
    return (object) [
        'id' => $_SESSION['admin_id'] ?? null,
        'name' => $_SESSION['admin_name'] ?? 'Admin',
        'email' => $_SESSION['admin_email'] ?? ''
    ];
}

function setting($key, $default = null) {
    return \App\Models\Setting::get($key, $default);
}

function settings($group = null) {
    if ($group) {
        return \App\Models\Setting::getByGroup($group);
    }
    return \App\Models\Setting::getAllAsArray();
}

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
    // Detect English or Spanish from current URL
    $uri = $_SERVER['REQUEST_URI'];

    // Clean double slashes
    $uri = preg_replace('#/+#', '/', $uri);

    // Remove leading slash
    $clean = ltrim($uri, '/');

    // Check if URL is Spanish (homepage or any Spanish page)
    $isSpanish = (
        $clean === 'es' ||
        $clean === 'es/' ||
        strpos($clean, 'es/') === 0
    );

    // Always remove leading slash from $path
    $path = ltrim($path, '/');

    // If Spanish: add `es/` prefix
    if ($isSpanish) {
        return "/es/" . $path;
    }

    // English (default): normal path
    return "/" . $path;
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

function switchLanguage($lang = 'es') {
    // Detect current full URL
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'];
    $uri    = $_SERVER['REQUEST_URI'];

    // Clean multiple slashes
    $uri = preg_replace('#/+#', '/', $uri);

    // Remove leading slash
    $clean = ltrim($uri, '/');

    // Detect Spanish (with or without slash)
    $isSpanish = ($clean === 'es' || $clean === 'es/' || strpos($clean, 'es/') === 0);

    // ============================================
    // SWITCH TO SPANISH (Always end with /)
    // ============================================
    if ($lang === 'es') {

        // If homepage in EN → return /es/
        if ($clean === '') {
            return "$scheme://$host/es/";
        }

        // If already Spanish homepage → normalize to /es/
        if ($clean === 'es') {
            return "$scheme://$host/es/";
        }

        if ($clean === 'es/') {
            return "$scheme://$host/es/";
        }

        // If already inside Spanish section
        if (strpos($clean, 'es/') === 0) {
            return "$scheme://$host/$clean";
        }

        // For pages like /tours → /es/tours
        return "$scheme://$host/es/$clean";
    }

    // ============================================
    // SWITCH TO ENGLISH (Always end with / for home)
    // ============================================
    if ($lang === 'en') {

        // If Spanish homepage
        if ($clean === 'es' || $clean === 'es/') {
            return "$scheme://$host/";
        }

        // If Spanish subpage: /es/tours → /tours
        if (strpos($clean, 'es/') === 0) {
            $clean = substr($clean, 3); // remove "es/"
        }

        // English homepage
        if ($clean === '') {
            return "$scheme://$host/";
        }

        return "$scheme://$host/$clean";
    }

    return "$scheme://$host/$clean";
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

function getKmlFiles($folder) {
    $index = 0;
    $results = '[';
    $nFolder = __DIR__."/../public/uploads/kml/".$folder;
    if (is_dir($nFolder)) {
        $dir = scandir($nFolder);
        $dir = array_slice($dir, 2);
       $results .= implode(', ', array_map(fn($name) => '"/uploads/kml/'.$folder."/".$name.'"', $dir));

    }
    $results .= "]";
    return $results;
}

function toPascal($str) {
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
}

function parseExpression($str) {
    // All supported operators (longest first)
    $operators = ['>=', '<=', '!=', '<>', '==', '=', '>', '<'];

    foreach ($operators as $op) {
        if (strpos($str, $op) !== false) {
            $parts = explode($op, $str, 2);
            $key   = trim($parts[0]);
            $value = trim($parts[1], " '\""); // remove spaces + quotes

            return [
                'key'      => $key,
                'operator' => $op,
                'value'    => $value
            ];
        }
    }

    return null; // not matched
}

function toKebabCase(string $string): string {
    // Replace underscores and spaces with a dash
    $string = preg_replace('/[\s_]+/', '-', $string);

    // Add a dash before any uppercase letter except at the start
    $string = preg_replace('/([a-z])([A-Z])/', '$1-$2', $string);

    // Convert to lowercase
    return strtolower($string);
}


function pagination($nbDePages) {
            // Number of pages to display before and after the current page
            $page = 1;
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            $nb_AvAp = 3;
            
            // Start pagination container
            $html = '<div>';
            $html .= '<ul class="pagination">';
            
            // Previous page link
            if ($page > 1) {
                $html .='<li><a href="?page=' . ($page - 1) . '"><i class="fa fa-arrow-left"></i></a></li>';
            } else {
                $html .= '<li><span id="lien_off"><i class="fa fa-arrow-left"></i></span></li>';
            }
                
            // Case 1: Small number of pages - show all page links
            if ($nbDePages <= ($nb_AvAp * 2) + 2) {
                // Display all page numbers
                for ($i = 1; $i <= $nbDePages; $i++) {
                    $html .= renderPageLink($i, $page);
                }
            }
            // Case 2: Current page is near the beginning
            elseif ($page <= $nb_AvAp + 1) {
                // Display first set of pages
                for ($i = 1; $i <= (($nb_AvAp * 2) + 1) && $i <= $nbDePages; $i++) {
                    $html .= renderPageLink($i, $page);
                }
            }
            // Case 3: Current page is right after the beginning section
            elseif ($page == $nb_AvAp + 2) {
                // Display first page
                $html .= '<li><a href="?page=1">1</a></li>';
                
                // Display pages around current page
                for ($i = $page - $nb_AvAp; $i <= $page + $nb_AvAp; $i++) {
                    $html .= renderPageLink($i, $page);
                }
            }
            // Case 4: Current page is in the middle
            elseif ($page - $nb_AvAp > 2 && $page < $nbDePages - 2) {
                // Display first page
               $html .= '<li><a href="?page=1">1</a></li>';
                
                // Display pages around current page
                for ($i = $page - $nb_AvAp; $i <= $page + $nb_AvAp && $i <= $nbDePages; $i++) {
                    $html .= renderPageLink($i, $page);
                }
            }
            // Case 5: Current page is near the end
            elseif ($page >= $nbDePages - 2) {
                // Display first page
                $html .='<li><a href="?page=1">1</a></li>';
                
                // Calculate offset for special case
                $offset = ($nb_AvAp == 1 && $page == $nbDePages - 2) ? 1 : 0;
                
                // Display last set of pages
                for ($i = $nbDePages - ($nb_AvAp * 2) - $offset; $i <= $page + $nb_AvAp && $i <= $nbDePages; $i++) {
                    $html .= renderPageLink($i, $page);
                }
            }
            
            // Display last page link if needed
            if ($page + $nb_AvAp < $nbDePages && $nbDePages > ($nb_AvAp * 2) + 2) {
                $html .='<li><a href="?page=' . $nbDePages . '">' . $nbDePages . '</a></li>';
            }
            
            // Next page link
            if ($page < $nbDePages) {
               $html .= '<li><a href="?page=' . ($page + 1) . '"><i class="fa fa-arrow-right"></i></a></li>';
            } else {
               $html .= '<li><span id="lien_off"><i class="fa fa-arrow-right"></i></span></li>';
            }
            
            // Close pagination container
            $html .= '</ul>';
            $html .= '</div>';
            return $html;
           
        }

           function renderPageLink($pageNum, $currentPage) {
            $html = "";
            if ($pageNum == $currentPage) {
                $html .= '<li><span>' . $pageNum . '</span></li>';
            } else {
                $html .= '<li><a href="?page=' . $pageNum . '">' . $pageNum . '</a></li>';
            }
            return $html;
        }



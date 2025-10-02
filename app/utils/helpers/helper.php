<?php
/**
 * Helper Functions
 * 
 * This file contains various helper functions used throughout the application
 */

    require_once __DIR__ . '/../../config/language.php';
    require_once __DIR__ . '/../../config/env.php';
    require_once __DIR__ . '/../../config/functions.php';
    
    // Default language from environment settings
    $LANG = $_ENV['LANG'];

    /**
     * Translate a given key using the current language settings
     *
     * @param string $key The key to translate
     * @return string The translated value
     */
    if (!function_exists('trans')) {
        function trans($key) {
            $LANG = $_ENV['LANG'];
            $url = $_SERVER['REQUEST_URI'];
            $url = explode('/', $url);
            if(isset($url[1])) {
                if($url[1] == 'es') {
                    $LANG = 'sp';
                }
            }
            $trans = loadLanguage($LANG);

            $keys = explode('.', $key);
            $val = '';
            $i = 0;
            foreach($keys as $k) {
                if($i == 0) {
                    $val = getValue($k, $trans);
                } else {
                    if(gettype($val) == "array") {
                        $val = getValue($k, $val);
                    }
                }
                $i += 1;
            }
            return $val;
        }
    }

    /**
     * Get a menu item by key
     *
     * @param string $key The key of the menu item
     * @return string The menu item value
     */
    if (!function_exists('menu')) {
        function menu($key) {
            $trans = loadConst('menu');
            return $trans[$key];
        }
    }

    if (!function_exists('getKeys')) {
        function getKeys($object) {
            return array_keys((array)$object);
        }
    }

    if (!function_exists('csrf_token')) {
        function csrf_token() {
            return bin2hex(random_bytes(32));

        }
    }

    /**
     * Get a value from an array by key
     *
     * @param string $key The key to retrieve
     * @param array $trans The array to retrieve from
     * @return string The retrieved value
     */
    if (!function_exists('getValue')) {
        function getValue($key, $trans) {
            if (array_key_exists($key, $trans)) {
                return $trans[$key];
            } else {
                return $key;
            }
        }
    }

    if (!function_exists('thumbnail')) {
        function thumbnail($path) {
            // img_685abe4b46e3a5.46675215.jpg
            $pathParts = explode('/', $path);
            $path = $pathParts[count($pathParts) - 1];
            $path = explode('.', $path);
            $thumb = '';
            for($i = 0; $i < count($path) - 1; $i++) {
                $thumb .= $path[$i].".";
            }
            $thumb = substr($thumb, 0, strlen($thumb) - 1) . '_thumbnail.webp';
            $pathParts[count($pathParts) - 1] = $thumb;
            return implode('/', $pathParts);
        }
    }

    /**
     * Generate a URL for a given path, considering language settings
     *
     * @param string $path The path to generate URL for
     * @return string The complete URL
     */
    if (!function_exists('url_admin')) {
        function url_admin($path) {
            return rtrim($_ENV['APP_URL'], '/') . '/' . $_ENV['PATH_ADMIN'] . '/' . ltrim($path, '/');
        }
    }

    /**
     * Generate a URL for a given path, considering language settings
     *
     * @param string $path The path to generate URL for
     * @return string The complete URL
     */
    if (!function_exists('route')) {
        function route($path) {
            // Check if current URL has language prefix
            $urlParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            
            // Add language prefix if needed
            if (!empty($urlParts[0]) && $urlParts[0] === 'es') {
                $path = 'es/' . ltrim($path, '/');
            }

            return rtrim($_ENV['APP_URL'], '/') . '/' . ltrim($path, '/');
        }
    }

    /**
     * Switch to a different language while maintaining the current page
     *
     * @param string $lang The language code to switch to
     * @return string The URL with updated language
     */
    if (!function_exists('switchTo')) {
        function switchTo($lang) {
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

            return rtrim($_ENV['APP_URL'], '/') . ($newPath ? '/' . $newPath : '');
        }
    }

    /**
     * Generate a URL for an asset
     *
     * @param string $path The path to the asset
     * @return string The complete asset URL
     */
    if (!function_exists('assets')) {
        function assets($path) {
            return  mainUrl() . '/assets/' . ltrim($path, '/');
        }
    }
    if (!function_exists('vendor')) {
        function vendor($path) {
            return mainUrl() . '/vendor/' . ltrim($path, '/');
        }
    }


    /**
     * Generate a URL for a vendor asset
     *
     * @param string $path The path to the vendor asset
     * @return string The complete vendor asset URL
     */
    if (!function_exists('vendors')) {
        function vendors($path) {
            return rtrim($_ENV['APP_URL'], '/') . '/assets/' . str_replace('assets', 'vendor', $path);
        }
    }


    if (!function_exists('idYoutubeVideo')) {
        function idYoutubeVideo($link) {
            ///https://www.youtube.com/watch?v=rEmaEklG0Ek
            $lnkParts = explode("?v=", $link);
            if (count($lnkParts) == 2) {
                return substr($lnkParts[1], 0, 11);
            } else {
                return 'invalide';
            }
        }
    }

    /**
     * Include an SVG file
     *
     * @param string $f The path to the SVG file
     */
    if (!function_exists('svg')) {
        function svg($f) {
            include $f;
        }
    }

    /**
     * Generate a relative URL for an asset based on current path depth
     *
     * @param string $path The path to the asset
     * @return string The relative asset URL
     */
    if (!function_exists('asset')) {
        function asset($path) {
            $baseUrlParts = explode('/', rtrim($_ENV['APP_URL'], '/'));
            $requestParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            
            // Calculate relative path depth
            $relativeDepth = 0;
            foreach($requestParts as $part) {
                if (!empty($part) && !in_array($part, $baseUrlParts)) {
                    $relativeDepth++;
                }
            }
            
            // Build relative path
            $relativePath = str_repeat('../', $relativeDepth);
            
            // Adjust for URLs without trailing slash
            if (substr($_SERVER['REQUEST_URI'], -1) != '/') {
                $relativePath = substr($relativePath, 0, max(0, strlen($relativePath) - 3));
            }
            
            return $relativePath . ltrim($path, '/');
        }
    }

    /**
     * Get the current page name from the URL
     *
     * @return string The current page name
     */
    if (!function_exists('namePage')) {
        function namePage() {
            $baseUrlParts = explode('/', rtrim($_ENV['APP_URL'], '/'));
            $requestParts = explode('/', trim(str_replace('/es', '', $_SERVER['REQUEST_URI']), '/'));
            
            // Extract page name by filtering out base URL parts
            $pageParts = [];
            foreach($requestParts as $part) {
                if (!empty($part) && !in_array($part, $baseUrlParts)) {
                    $pageParts[] = $part;
                }
            }
            
            // Return first part if available, otherwise empty string
            return !empty($pageParts) ? $pageParts[0] : '';
        }
    }

    /**
     * Get the current page URL in URL-encoded format
     *
     * @return string The URL-encoded current page URL
     */
    if (!function_exists('currentLink')) {
        function currentLink() {
            // Determine protocol (http or https)
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            
            // Build and encode the full URL
            $fullUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            
            // URL encode the slashes for special use cases
            return str_replace([':', '/', '?', '&'], ['%3A', '%2F', '%3F', '%26'], $fullUrl);
        }
    }

    if (!function_exists('currentPath')) {
        function currentPath() {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            
            // Build and encode the full URL
            $fullUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = str_replace($_ENV['APP_URL'], '', $fullUrl);
            $url = str_replace('/es', '', $url);
            return $url;
        }
    }
    
    if (!function_exists('isInCurrentPath')) {
        function isInCurrentPath($keys) {
            $isIn = false;
            $pathParts = explode("/", currentPath());
            $isIn = count(array_intersect($pathParts, $keys)) === count($keys);
            return $isIn;
        }
    }

    if (!function_exists('formatDateTime')) {
        function formatDateTime($date) {
            $date = new DateTime($date);
            return $date->format('l j F Y H:i');
        }
    }

    /**
     * Get the last segment of the current URL path
     *
     * @return string The last segment of the current URL
     */
    if (!function_exists('currentPage')) {
        function currentPage() {
            $urlParts = explode('%2F', currentLink());
            if (end($urlParts) == "es") {
                return "";
            } else {
                return end($urlParts);
            }
        }
    }
    
    /**
     * Get a formatted page name from the current URL
     * Converts underscores and hyphens to spaces and capitalizes the first letter
     *
     * @return string The formatted page name
     */
    if (!function_exists('nPage')) {
        function nPage() {
            // Get current page and format it
            $currentPage = currentPage();
            $name = str_replace(['_', '-'], ' ', $currentPage);
            $name = ucfirst($name);
            
            // Remove query parameters if present
            if (strpos($name, '?') !== false) {
                list($name) = explode('?', $name);
            }
            
            return $name;
        }
    }

    /**
     * Generate pagination HTML for the current page
     *
     * @param int $page Current page number
     * @param int $nbDePages Total number of pages
     * @return void Outputs HTML directly
     */
    if (!function_exists('pagination')) {
        function pagination($page, $nbDePages) {
            // Number of pages to display before and after the current page
            $nb_AvAp = 3;
            
            // Start pagination container
            echo '<div>';
            echo '<ul class="pagination">';
            
            // Previous page link
            if ($page > 1) {
                echo '<li><a href="?page=' . ($page - 1) . '"><i class="fa fa-arrow-left"></i></a></li>';
            } else {
                echo '<li><span id="lien_off"><i class="fa fa-arrow-left"></i></span></li>';
            }
                
            // Case 1: Small number of pages - show all page links
            if ($nbDePages <= ($nb_AvAp * 2) + 2) {
                // Display all page numbers
                for ($i = 1; $i <= $nbDePages; $i++) {
                    renderPageLink($i, $page);
                }
            }
            // Case 2: Current page is near the beginning
            elseif ($page <= $nb_AvAp + 1) {
                // Display first set of pages
                for ($i = 1; $i <= (($nb_AvAp * 2) + 1) && $i <= $nbDePages; $i++) {
                    renderPageLink($i, $page);
                }
            }
            // Case 3: Current page is right after the beginning section
            elseif ($page == $nb_AvAp + 2) {
                // Display first page
                echo '<li><a href="?page=1">1</a></li>';
                
                // Display pages around current page
                for ($i = $page - $nb_AvAp; $i <= $page + $nb_AvAp; $i++) {
                    renderPageLink($i, $page);
                }
            }
            // Case 4: Current page is in the middle
            elseif ($page - $nb_AvAp > 2 && $page < $nbDePages - 2) {
                // Display first page
                echo '<li><a href="?page=1">1</a></li>';
                
                // Display pages around current page
                for ($i = $page - $nb_AvAp; $i <= $page + $nb_AvAp && $i <= $nbDePages; $i++) {
                    renderPageLink($i, $page);
                }
            }
            // Case 5: Current page is near the end
            elseif ($page >= $nbDePages - 2) {
                // Display first page
                echo '<li><a href="?page=1">1</a></li>';
                
                // Calculate offset for special case
                $offset = ($nb_AvAp == 1 && $page == $nbDePages - 2) ? 1 : 0;
                
                // Display last set of pages
                for ($i = $nbDePages - ($nb_AvAp * 2) - $offset; $i <= $page + $nb_AvAp && $i <= $nbDePages; $i++) {
                    renderPageLink($i, $page);
                }
            }
            
            // Display last page link if needed
            if ($page + $nb_AvAp < $nbDePages && $nbDePages > ($nb_AvAp * 2) + 2) {
                echo '<li><a href="?page=' . $nbDePages . '">' . $nbDePages . '</a></li>';
            }
            
            // Next page link
            if ($page < $nbDePages) {
                echo '<li><a href="?page=' . ($page + 1) . '"><i class="fa fa-arrow-right"></i></a></li>';
            } else {
                echo '<li><span id="lien_off"><i class="fa fa-arrow-right"></i></span></li>';
            }
            
            // Close pagination container
            echo '</ul>';
            echo '</div>';
            
           
        }
    }

    if (!function_exists('renderPageLink')) {
         // Helper function to render a page link
         function renderPageLink($pageNum, $currentPage) {
            if ($pageNum == $currentPage) {
                echo '<li><span>' . $pageNum . '</span></li>';
            } else {
                echo '<li><a href="?page=' . $pageNum . '">' . $pageNum . '</a></li>';
            }
        }
    }
    if (!function_exists('scan_dir')) {
    function scan_dir($directory, &$results = []) {
        $dir = __DIR__ . $directory;
        $files = scandir($dir);
    
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!$path) continue;
    
            if (!is_dir($path)) {
                $results[] = $path;
            } elseif ($value != "." && $value != "..") {
                scan_dir($path, $results);
            }
        }
    
        return $results;
    }
}

    /*if (!function_exists('urlencode')) {
        function urlencode($path) {
            return urlencode($path);
        }
    }*/

    if(isset($_SESSION['lang'])) {
        $LANG = $_SESSION['lang'];
    }

?>
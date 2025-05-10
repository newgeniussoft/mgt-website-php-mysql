<?php
require_once __DIR__ . '/app/http/controllers/HomeController.php';

/**
 * Simple Router for MGT Website
 * 
 * Handles routing to different controller methods based on URL path
 */
class Router {
    private $controller;
    private $supportedPages = ['about'];
    private $supportedLanguages = ['es'];
    
    public function __construct() {
        $this->controller = new HomeController();
    }
    
    /**
     * Parse the current URL and route to the appropriate controller method
     */
    public function route() {
        // Parse the current URL path
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        
        // If path is empty, show homepage
        if (empty($pathParts[0])) {
            return $this->controller->index();
        }
        
        // Check if the first segment is a language code
        $language = null;
        $page = $pathParts[0];
        
        if (in_array($pathParts[0], $this->supportedLanguages)) {
            $language = array_shift($pathParts);
            $page = isset($pathParts[0]) ? $pathParts[0] : '';
        }
        
        // Route based on page name
        if (empty($page)) {
            return $this->controller->index();
        } elseif ($page === 'about') {
            return $this->controller->about();
        } else {
            return $this->send404();
        }
    }
    
    /**
     * Send a 404 Not Found response
     */
    private function send404() {
        http_response_code(404);
    }
}

// Initialize router and process the request
$router = new Router();
$router->route();
?>
<?php

require_once __DIR__ . '/../controllers/frontend/MainController.php';
require_once __DIR__ . '/../controllers/admin/AuthController.php';

class Router {
    private $supportedLanguages = ['es', 'en'];
    private $supportedPages;
    private $adminPath;
    
    public function __construct() {
        // Load admin path from environment
        require_once __DIR__ . '/../../config/env.php';
        $this->adminPath = $_ENV['PATH_ADMIN'] ?? 'admin-panel';
        
        $this->supportedPages = ['tours', 'reviews', 'welcome'];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        
        // Handle language detection
        $language = 'en';
        if (isset($pathParts[0]) && in_array($pathParts[0], $this->supportedLanguages)) {
            $language = $pathParts[0];
            array_shift($pathParts);
        }
        
        // Check if this is an admin route
        if (isset($pathParts[0]) && ($pathParts[0] === 'admin' || $pathParts[0] === $this->adminPath)) {
            $this->handleAdminRoutes($pathParts, $language);
            return;
        }
        
        // Handle frontend routes
        $this->handleFrontendRoutes($pathParts, $language);
    }
    
    /**
     * Handle admin routes
     */
    private function handleAdminRoutes($pathParts, $language) {
        // Remove 'admin' or admin path from parts
        array_shift($pathParts);
        
        $controller = new AuthController($language);
        
        // Default to login if no specific route
        if (empty($pathParts) || $pathParts[0] === '') {
            $controller->login();
            return;
        }
        
        $action = $pathParts[0];
        
        switch ($action) {
            case 'login':
                $controller->login();
                break;
                
            case 'logout':
                $controller->logout();
                break;
                
            case 'dashboard':
                $controller->dashboard();
                break;
                
            case 'profile':
                $controller->profile();
                break;
                
            default:
                // For other admin routes, check if method exists
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    $this->notFound();
                }
                break;
        }
    }
    
    /**
     * Handle frontend routes
     */
    private function handleFrontendRoutes($pathParts, $language) {
        $index = !isset($pathParts[0]) || $pathParts[0] == "";
        $controller = new MainController($language);
        
        if ($index) {
            $controller->index();
        } else {
            if (in_array($pathParts[0], $this->supportedPages)) {
                $controller->{$pathParts[0]}();
            } else {
                $this->notFound();
            }
        }
    }

    /**
     * 404 Not Found handler
     */
    function notFound() {
        http_response_code(404);
        echo '404 Not Found';
        exit;
    }
}

?>
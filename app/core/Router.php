<?php

require_once __DIR__ . '/../controllers/admin/AuthController.php';
require_once __DIR__ . '/../controllers/admin/PageController.php';
require_once __DIR__ . '/../controllers/frontend/MainController.php';
require_once __DIR__ . '/../models/Page.php';

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
        
        // Default to login if no specific route
        if (empty($pathParts) || $pathParts[0] === '') {
            $controller = new AuthController($language);
            $controller->login();
            return;
        }
        
        $action = $pathParts[0];
        
        // Handle page management routes
        if ($action === 'pages') {
            $this->handlePageRoutes($pathParts, $language);
            return;
        }
        
        // Handle auth routes
        $controller = new AuthController($language);
        
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
     * Handle page management routes
     */
    private function handlePageRoutes($pathParts, $language) {
        $controller = new PageController($language);
        
        // Remove 'pages' from path parts
        array_shift($pathParts);
        
        // Default to index if no specific action
        if (empty($pathParts) || $pathParts[0] === '') {
            $controller->index();
            return;
        }
        
        $action = $pathParts[0];
        
        switch ($action) {
            case 'create':
                $controller->create();
                break;
                
            case 'store':
                $controller->store();
                break;
                
            case 'edit':
                $controller->edit();
                break;
                
            case 'update':
                $controller->update();
                break;
                
            case 'delete':
                $controller->delete();
                break;
                
            case 'preview':
                $controller->preview();
                break;
                
            default:
                // Check if method exists
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
        $controller = new MainController($language);
        $pageModel = new Page();
        
        // Check if this is the homepage
        if (!isset($pathParts[0]) || $pathParts[0] == "") {
            // Try to get homepage from database
            $homepage = $pageModel->getHomepage();
            if ($homepage) {
                $controller->showPage($homepage);
            } else {
                $controller->index();
            }
            return;
        }
        
        $slug = $pathParts[0];
        
        // First check if it's a page from database
        $page = $pageModel->getBySlug($slug);
        if ($page && $page->status === 'published') {
            $controller->showPage($page);
            return;
        }
        
        // Fallback to static pages if they exist
        if (in_array($slug, $this->supportedPages)) {
            $controller->{$slug}();
        } else {
            $this->notFound();
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
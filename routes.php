<?php
require_once __DIR__ . '/app/http/controllers/MainController.php';  
require_once __DIR__ . '/app/http/controllers/AdminController.php';  

/**
 * Simple Router for MGT Website
 * 
 * Handles routing to different controller methods based on URL path
 */
class Router {
    private $mainController;
    private $adminController;
    private $supportedPages = ['about'];
    private $adminPages = ['dashboard', 'users', 'settings', 'login', 'logout'];
    private $supportedLanguages = ['es'];
    
    public function __construct() {
        $this->mainController = new MainController();
        $this->adminController = new AdminController();
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
            return $this->mainController->index();
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
            return $this->mainController->index();
        } else if ($page === 'access') {
            // Admin routes with 'access' prefix
            return $this->handleAdminRoutes($pathParts);
        } else {
            if (in_array($page, $this->supportedPages)) {
                return $this->mainController->$page();
            } else {
                return $this->send404();
            }
        }
    }
    
    /**
     * Handle admin routes with 'access' prefix
     * 
     * @param array $pathParts URL path parts
     * @return mixed
     */
    private function handleAdminRoutes($pathParts) {
        // Get the admin page from the URL (second segment after 'access')
        $adminPage = isset($pathParts[1]) ? $pathParts[1] : 'index';
        
        // Public admin routes (no authentication required)
        if ($adminPage === 'login') {
            return $this->adminController->login();
        }
        
        // For all other admin routes, the middleware in the AdminController methods
        // will handle authentication checks
        
        // Route to the appropriate admin controller method
        if ($adminPage === 'index' || empty($adminPage)) {
            return $this->adminController->index();
        } else if (in_array($adminPage, $this->adminPages)) {
            // Check if the method exists in the AdminController
            if (method_exists($this->adminController, $adminPage)) {
                return $this->adminController->$adminPage();
            }
        }
        
        // If no valid admin page is found, return 404
        return $this->send404();
    }
    
    /**
     * Check if the current user is authenticated as an admin
     * 
     * @return bool
     */
    private function isAdminAuthenticated() {
        // Implement your admin authentication logic here
        // For example, check session variables, cookies, etc.
        
        // Temporary implementation - replace with actual authentication logic
        if (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
            return true;
        }
        
        // For development purposes only - remove in production
        // Uncomment the line below to bypass authentication during development
        // return true;
        
        return false;
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
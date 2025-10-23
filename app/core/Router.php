<?php

require_once __DIR__ . '/../controllers/admin/AuthController.php';
require_once __DIR__ . '/../controllers/admin/PageController.php';
require_once __DIR__ . '/../controllers/admin/LayoutController.php';
require_once __DIR__ . '/../controllers/admin/PageTemplateController.php';
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
        
        // Handle layout management routes
        if ($action === 'layouts') {
            $this->handleLayoutRoutes($pathParts, $language);
            return;
        }
        
        // Handle page template management routes
        if ($action === 'page-templates') {
            $this->handlePageTemplateRoutes($pathParts, $language);
            return;
        }
        
        // Handle media management routes
        if ($action === 'media') {
            $this->handleMediaRoutes($pathParts, $language);
            return;
        }
        
        // Handle API routes
        if ($action === 'api') {
            $this->handleApiRoutes($pathParts, $language);
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
                
            case 'sections':
                $controller->sections();
                break;
                
            case 'section-builder':
                $controller->sectionBuilder();
                break;
                
            case 'add-section':
                $controller->addSection();
                break;
                
            case 'update-section-order':
                $controller->updateSectionOrder();
                break;
                
            case 'delete-section':
                $controller->deleteSection();
                break;
                
            case 'update-section':
                $controller->updateSection();
                break;
                
            case 'add-section-ajax':
                $controller->addSectionAjax();
                break;
                
            case 'delete-section-ajax':
                $controller->deleteSectionAjax();
                break;
                
            case 'get-section':
                $controller->getSection();
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
     * Handle layout management routes
     */
    private function handleLayoutRoutes($pathParts, $language) {
        $controller = new LayoutController($language);
        
        // Remove 'layouts' from path parts
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
                
            case 'duplicate':
                $controller->duplicate();
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
     * Handle page template management routes
     */
    private function handlePageTemplateRoutes($pathParts, $language) {
        $controller = new PageTemplateController($language);
        
        // Remove 'page-templates' from path parts
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
                
            case 'duplicate':
                $controller->duplicate();
                break;
                
            case 'preview':
                $controller->preview();
                break;
                
            case 'extract-variables':
                $controller->extractVariables();
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
     * Handle API routes
     */
    private function handleApiRoutes($pathParts, $language) {
        // Remove 'api' from path parts
        array_shift($pathParts);
        
        if (empty($pathParts) || $pathParts[0] === '') {
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
            return;
        }
        
        $endpoint = $pathParts[0];
        
        switch ($endpoint) {
            case 'section-templates':
                $controller = new PageController($language);
                $controller->getSectionTemplatesApi();
                break;
                
            default:
                http_response_code(404);
                echo json_encode(['error' => 'API endpoint not found']);
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
            // Try to get homepage from database for the current language
            $homepage = $pageModel->getHomepage($language);
            if ($homepage) {
                $controller->showPage($homepage);
            } else {
                // Fallback to English if no homepage in current language
                $homepage = $pageModel->getHomepage('en');
                if ($homepage) {
                    $controller->showPage($homepage);
                } else {
                    $controller->index();
                }
            }
            return;
        }
        
        $slug = $pathParts[0];
        
        // First check if it's a page from database for current language
        $page = $pageModel->getBySlug($slug, $language);
        if ($page && $page->status === 'published') {
            $controller->showPage($page);
            return;
        }
        
        // If not found in current language, try English as fallback
        if ($language !== 'en') {
            $page = $pageModel->getBySlug($slug, 'en');
            if ($page && $page->status === 'published') {
                $controller->showPage($page);
                return;
            }
        }
        
        // Fallback to static pages if they exist
        if (in_array($slug, $this->supportedPages)) {
            $controller->{$slug}();
        } else {
            $this->notFound();
        }
    }

    /**
     * Handle media management routes
     */
    private function handleMediaRoutes($pathParts, $language) {
        require_once __DIR__ . '/../controllers/admin/MediaController.php';
        $controller = new MediaController($language);
        
        // Remove 'media' from path parts
        array_shift($pathParts);
        
        if (empty($pathParts) || $pathParts[0] === '') {
            $controller->index();
            return;
        }
        
        $action = $pathParts[0];
        
        switch ($action) {
            case 'upload':
                $controller->upload();
                break;
                
            case 'edit':
                $controller->edit();
                break;
                
            case 'delete':
                $controller->delete();
                break;
                
            case 'folders':
                $controller->folders();
                break;
                
            case 'picker':
                $controller->picker();
                break;
                
            case 'download':
                $controller->download();
                break;
                
            default:
                $this->notFound();
                break;
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
<?php

require_once __DIR__ . '/../controllers/frontend/MainController.php';

class Router {
    private $supportedLanguages = ['es', 'en'];
    private $supportedPages; // Add your supported pages here
    public function __construct() {
        $this->supportedPages = ['tours', 'reviews', 'welcome'];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        
        $language = 'en';
        if (isset($pathParts[0]) && in_array($pathParts[0], $this->supportedLanguages)) {
            $language = $pathParts[0];
            array_shift($pathParts);
        }
        $index = !isset($pathParts[0]) || $pathParts[0] == "";
        $controller = new MainController($language);
        $controller->index();
        /*if ($index) {
            $controller->index();
        } else {
            if (in_array($pathParts[0], $this->supportedPages)) {
                $controller->{$pathParts[0]}();
            } else {
                $this->notFound();
            }
        }*/
    }   

    function notFound() {
        http_response_code(404);
        echo 'Reviews Path 404 Not Found';
        exit;
    }
}

?>
<?php

require_once __DIR__ . '/../controllers/frontend/HomeController.php';

class Router {
    private $supportedLanguages = ['es'];
    private $supportedPages; // Add your supported pages here
    public function __construct() {
        $supportedPages = [];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        $page = $pathParts[0];
        $controller = new HomeController();
        $controller->index();
    }

    function notFound() {
        http_response_code(404);
        echo 'Reviews Path 404 Not Found';
        exit;
    }
}

?>
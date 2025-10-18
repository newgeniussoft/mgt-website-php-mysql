<?php

class Router {
    private $supportedLanguages = ['es'];
    private $supportedPages; // Add your supported pages here
    public function __construct() {
        $supportedPages = [];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        $page = $pathParts[0];
    }
}

?>
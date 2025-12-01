<?php
namespace App\Core;

class Router
{
    public $routes = [];

    public function __construct()
    {
    }

    public function get($path, $controller)
    {
        
        $this->routes['get'][$path] = $controller;
    }

    public function post($path, $controller)
    {
        $this->routes['post'][$path] = $controller;
    }

    public function run()
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$path])) {
            $controller = $this->routes[$method][$path];
            $controller();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}

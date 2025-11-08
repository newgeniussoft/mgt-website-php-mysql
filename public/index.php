<?php

require_once __DIR__ . '/../bootstrap/app.php';

// Create Router instance
class Router {
    protected $routes = [];
    protected $groupStack = [];
    
    public function get($uri, $action) {
        return $this->addRoute('GET', $uri, $action);
    }
    
    public function post($uri, $action) {
        return $this->addRoute('POST', $uri, $action);
    }
    
    public function put($uri, $action) {
        return $this->addRoute('PUT', $uri, $action);
    }
    
    public function delete($uri, $action) {
        return $this->addRoute('DELETE', $uri, $action);
    }
    
    public function group($attributes, $callback) {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }
    
    protected function addRoute($method, $uri, $action) {
        $prefix = '';
        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
        }
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $prefix . $uri,
            'action' => $action,
        ];
        
        return $this;
    }
    
    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchUri($route['uri'], $uri, $params)) {
                return $this->callAction($route, $params);
            }
        }
        
        http_response_code(404);
        echo "404 - Page Not Found";
    }
    
    protected function matchUri($routeUri, $requestUri, &$params = []) {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $requestUri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        
        return false;
    }
    
    protected function callAction($route, $params) {
        if (is_callable($route['action'])) {
            return call_user_func_array($route['action'], $params);
        }
        
        list($controller, $method) = explode('@', $route['action']);
        $controllerInstance = new $controller();
        return call_user_func_array([$controllerInstance, $method], $params);
    }
}

$router = new Router();

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Dispatch
$router->dispatch();
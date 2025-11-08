<?php

require_once __DIR__ . '/../bootstrap/app.php';

use App\Localization\Lang;
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
        $middleware = [];
        
        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
            if (isset($group['middleware'])) {
                $middleware[] = $group['middleware'];
            }
        }
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $prefix . $uri,
            'action' => $action,
            'middleware' => $middleware,
        ];
        
        return $this;
    }
    
    public function dispatch() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    
    // Extract locale from URI
    $segments = explode('/', trim($uri, '/'));
    $supportedLocales = ['en', 'es'];
    
    if (!empty($segments[0]) && in_array($segments[0], $supportedLocales)) {
        $locale = array_shift($segments);
        $uri = '/' . implode('/', $segments);
        
        Lang::setLocale($locale);
    }
    
    // Continue with normal routing...
    foreach ($this->routes as $route) {
        if ($route['method'] === $method && $this->matchUri($route['uri'], $uri, $params)) {
            // Execute middleware
            if (!empty($route['middleware'])) {
                foreach ($route['middleware'] as $middlewareName) {
                    $this->executeMiddleware($middlewareName);
                }
            }
            return $this->callAction($route, $params);
        }
    }
    
    abort(404);
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
    
    protected function executeMiddleware($middlewareName) {
        $middlewareMap = [
            'auth' => 'App\\Http\\Middleware\\AuthMiddleware',
            'cors' => 'App\\Http\\Middleware\\CorsMiddleware',
            'locale' => 'App\\Http\\Middleware\\LocaleMiddleware',
        ];
        
        if (isset($middlewareMap[$middlewareName])) {
            $middlewareClass = $middlewareMap[$middlewareName];
            $middleware = new $middlewareClass();
            
            $middleware->handle($_REQUEST, function($request) {
                return true;
            });
        }
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
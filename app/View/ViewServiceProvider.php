<?php

namespace App\View;

class ViewServiceProvider {
    protected $viewPath;
    protected $cachePath;
    
    public function __construct($viewPath = null, $cachePath = null) {
        $this->viewPath = $viewPath ?: __DIR__ . '/../../resources/views';
        $this->cachePath = $cachePath ?: __DIR__ . '/../../storage/cache/views';
    }
    
    public function register() {
        // Create Blade Engine instance
        $blade = new BladeEngine($this->viewPath, $this->cachePath);
        
        // Set the engine in View factory
        View::setEngine($blade);
        
        // Share common data with all views
        View::share('app_name', config('app.name', 'My App'));
        View::share('app_url', config('app.url', 'http://localhost'));
        
        return $blade;
    }
    
    public function boot() {
        // Register global view composers
        // You can bind data to specific views here
        
        // Example:
        // View::composer('layouts.app', function($data) {
        //     $data['user'] = auth()->user();
        //     return $data;
        // });
    }
}
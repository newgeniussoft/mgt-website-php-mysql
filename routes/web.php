<?php


$router->get('/', 'App\Http\Controllers\HomeController@index');
$router->get('/about', 'App\Http\Controllers\HomeController@about');
$router->get('/contact', 'App\Http\Controllers\HomeController@contact');

$router->get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// User routes example
$router->group(['prefix' => 'api'], function($router) {
    $router->get('/users', 'App\Http\Controllers\UserController@index');
    $router->get('/users/{id}', 'App\Http\Controllers\UserController@show');
    $router->post('/users', 'App\Http\Controllers\UserController@store');
});


// Admin authentication routes (no middleware)
$router->group(['prefix' => $_ENV['APP_ADMIN_PREFIX']], function($router) {
    $router->get('/login', 'App\Http\Controllers\AdminAuthController@showLoginForm');
    $router->post('/login', 'App\Http\Controllers\AdminAuthController@login');
    $router->get('/logout', 'App\Http\Controllers\AdminAuthController@logout');
});

// Admin protected routes (with auth middleware)
$router->group(['prefix' => $_ENV['APP_ADMIN_PREFIX'], 'middleware' => 'auth'], function($router) {
    $router->get('/', 'App\Http\Controllers\AdminAuthController@dashboard');
    $router->get('/dashboard', 'App\Http\Controllers\AdminAuthController@dashboard');
    
    // Settings routes
    $router->get('/settings', 'App\Http\Controllers\SettingsController@index');
    $router->post('/settings/update', 'App\Http\Controllers\SettingsController@update');
    $router->post('/settings/reset', 'App\Http\Controllers\SettingsController@reset');
    
    // Media routes
    $router->get('/media', 'App\Http\Controllers\MediaController@index');
    $router->get('/media/upload', 'App\Http\Controllers\MediaController@create');
    $router->post('/media/store', 'App\Http\Controllers\MediaController@store');
    $router->get('/media/edit', 'App\Http\Controllers\MediaController@edit');
    $router->post('/media/update', 'App\Http\Controllers\MediaController@update');
    $router->post('/media/delete', 'App\Http\Controllers\MediaController@destroy');
    $router->get('/media/download', 'App\Http\Controllers\MediaController@download');
    
    // User management routes
    $router->get('/users', 'App\Http\Controllers\AdminController@index');
    $router->get('/users/{id}', 'App\Http\Controllers\AdminController@show');
    $router->post('/users', 'App\Http\Controllers\AdminController@store');
    $router->put('/users/{id}', 'App\Http\Controllers\AdminController@update');
    $router->delete('/users/{id}', 'App\Http\Controllers\AdminController@destroy');
});

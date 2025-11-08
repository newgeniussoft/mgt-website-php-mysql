<?php

$router->get('/', function() {
    echo "<h1>Welcome to My App!</h1>";
    echo "<p>Your Laravel-like PHP framework is running!</p>";
});

$router->get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// User routes example
$router->group(['prefix' => 'api'], function($router) {
    $router->get('/users', 'App\Http\Controllers\UserController@index');
    $router->get('/users/{id}', 'App\Http\Controllers\UserController@show');
    $router->post('/users', 'App\Http\Controllers\UserController@store');
});
<?php


$router->get('/', 'App\Http\Controllers\HomeController@index');

$router->get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// User routes example
$router->group(['prefix' => 'api'], function($router) {
    $router->get('/users', 'App\Http\Controllers\UserController@index');
    $router->get('/users/{id}', 'App\Http\Controllers\UserController@show');
    $router->post('/users', 'App\Http\Controllers\UserController@store');
});
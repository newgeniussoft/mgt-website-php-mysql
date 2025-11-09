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
    $router->post('/media/rename', 'App\Http\Controllers\MediaController@renameFile');
    $router->post('/media/move', 'App\Http\Controllers\MediaController@moveFile');
    $router->post('/media/bulk-action', 'App\Http\Controllers\MediaController@bulkAction');
    
    // Media folder routes
    $router->get('/media/folders', 'App\Http\Controllers\MediaController@folders');
    $router->post('/media/folder/create', 'App\Http\Controllers\MediaController@createFolder');
    $router->post('/media/folder/update', 'App\Http\Controllers\MediaController@updateFolder');
    $router->post('/media/folder/delete', 'App\Http\Controllers\MediaController@deleteFolder');
    
    // File Manager routes (real file system)
    $router->get('/filemanager', 'App\Http\Controllers\FileManagerController@index');
    $router->post('/filemanager/create-folder', 'App\Http\Controllers\FileManagerController@createFolder');
    $router->post('/filemanager/upload', 'App\Http\Controllers\FileManagerController@upload');
    $router->post('/filemanager/rename', 'App\Http\Controllers\FileManagerController@rename');
    $router->post('/filemanager/delete', 'App\Http\Controllers\FileManagerController@delete');
    $router->post('/filemanager/move', 'App\Http\Controllers\FileManagerController@move');
    $router->get('/filemanager/download', 'App\Http\Controllers\FileManagerController@download');
    $router->get('/filemanager/folder-tree', 'App\Http\Controllers\FileManagerController@getFolderTree');
    
    // Code Editor routes
    $router->get('/codeeditor', 'App\Http\Controllers\CodeEditorController@index');
    $router->get('/codeeditor/file-tree', 'App\Http\Controllers\CodeEditorController@getFileTree');
    $router->post('/codeeditor/save', 'App\Http\Controllers\CodeEditorController@save');
    $router->post('/codeeditor/create-file', 'App\Http\Controllers\CodeEditorController@createFile');
    $router->post('/codeeditor/create-folder', 'App\Http\Controllers\CodeEditorController@createFolder');
    $router->post('/codeeditor/delete-file', 'App\Http\Controllers\CodeEditorController@deleteFile');
    $router->post('/codeeditor/delete-folder', 'App\Http\Controllers\CodeEditorController@deleteFolder');
    $router->post('/codeeditor/rename', 'App\Http\Controllers\CodeEditorController@renameFile');
    $router->post('/codeeditor/move', 'App\Http\Controllers\CodeEditorController@moveFile');
    $router->post('/codeeditor/copy', 'App\Http\Controllers\CodeEditorController@copyFile');
    $router->get('/codeeditor/search', 'App\Http\Controllers\CodeEditorController@search');
    $router->get('/readfile', 'App\Http\Controllers\CodeEditorController@readFile');
    
    // User management routes
    $router->get('/users', 'App\Http\Controllers\AdminController@index');
    $router->get('/users/{id}', 'App\Http\Controllers\AdminController@show');
    $router->post('/users', 'App\Http\Controllers\AdminController@store');
    $router->put('/users/{id}', 'App\Http\Controllers\AdminController@update');
    $router->delete('/users/{id}', 'App\Http\Controllers\AdminController@destroy');
    
    // Database management routes
    $router->get('/database', 'App\Http\Controllers\DatabaseController@index');
    $router->get('/database/view-table', 'App\Http\Controllers\DatabaseController@viewTable');
    $router->get('/database/edit-row', 'App\Http\Controllers\DatabaseController@editRow');
    $router->post('/database/update-row', 'App\Http\Controllers\DatabaseController@updateRow');
    $router->get('/database/add-row', 'App\Http\Controllers\DatabaseController@addRow');
    $router->post('/database/insert-row', 'App\Http\Controllers\DatabaseController@insertRow');
    $router->post('/database/delete-row', 'App\Http\Controllers\DatabaseController@deleteRow');
    $router->get('/database/sql-query', 'App\Http\Controllers\DatabaseController@sqlQuery');
    $router->post('/database/sql-query', 'App\Http\Controllers\DatabaseController@sqlQuery');
    $router->get('/database/export-table', 'App\Http\Controllers\DatabaseController@exportTable');
    $router->post('/database/truncate-table', 'App\Http\Controllers\DatabaseController@truncateTable');
    $router->post('/database/drop-table', 'App\Http\Controllers\DatabaseController@dropTable');
});

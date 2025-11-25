<?php


// Frontend routes - Dynamic pages from database
$router->get('/', 'App\Http\Controllers\FrontendController@index');

// Model-driven detail pages (Option A)
/*$router->get('/tour/{slug}', 'App\Http\Controllers\FrontendController@showTour');
$router->get('/tours/{slug}', 'App\Http\Controllers\FrontendController@showTour');
// Blog/Post detail pages
$router->get('/post/{key}', 'App\Http\Controllers\FrontendController@showPost');
$router->get('/blog/{key}', 'App\Http\Controllers\FrontendController@showPost');*/

$router->get('/test-api', function() {
    return response()->json(['message' => 'API is working!']);
});

$router->get('/test', 'App\Http\Controllers\FrontendController@testPage');

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
    $router->get('', 'App\Http\Controllers\AdminAuthController@dashboard');
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
    $router->get('/database/add-column', 'App\Http\Controllers\DatabaseController@addColumn');
    $router->post('/database/insert-column', 'App\Http\Controllers\DatabaseController@insertColumn');
    $router->get('/database/edit-column', 'App\Http\Controllers\DatabaseController@editColumn');
    $router->post('/database/update-column', 'App\Http\Controllers\DatabaseController@updateColumn');
    $router->post('/database/delete-column', 'App\Http\Controllers\DatabaseController@deleteColumn');
    
    // CMS Page Management routes
    $router->get('/pages', 'App\Http\Controllers\PageController@index');
    $router->get('/pages/create', 'App\Http\Controllers\PageController@create');
    $router->post('/pages/store', 'App\Http\Controllers\PageController@store');
    $router->get('/pages/edit', 'App\Http\Controllers\PageController@edit');
    $router->post('/pages/update', 'App\Http\Controllers\PageController@update');
    $router->post('/pages/delete', 'App\Http\Controllers\PageController@destroy');
    $router->get('/pages/preview', 'App\Http\Controllers\PageController@preview');
    
    // Template Management routes
    $router->get('/templates', 'App\Http\Controllers\TemplateController@index');
    $router->get('/templates/create', 'App\Http\Controllers\TemplateController@create');
    $router->post('/templates/store', 'App\Http\Controllers\TemplateController@store');
    $router->get('/templates/edit', 'App\Http\Controllers\TemplateController@edit');
    $router->post('/templates/update', 'App\Http\Controllers\TemplateController@update');
    $router->post('/templates/delete', 'App\Http\Controllers\TemplateController@destroy');
    $router->get('/templates/preview', 'App\Http\Controllers\TemplateController@preview');
    $router->post('/templates/duplicate', 'App\Http\Controllers\TemplateController@duplicate');

    // Slide Management routes
    $router->get('/slides', 'App\Http\Controllers\SlideController@index');
    $router->get('/slides/create', 'App\Http\Controllers\SlideController@create');
    $router->post('/slides/store', 'App\Http\Controllers\SlideController@store');
    $router->get('/slides/edit', 'App\Http\Controllers\SlideController@edit');
    $router->post('/slides/update', 'App\Http\Controllers\SlideController@update');
    $router->post('/slides/delete', 'App\Http\Controllers\SlideController@destroy');

    // Gallery Management routes
    $router->get('/galleries', 'App\Http\Controllers\GalleryController@index');
    $router->get('/galleries/create', 'App\Http\Controllers\GalleryController@create');
    $router->post('/galleries/store', 'App\Http\Controllers\GalleryController@store');
    $router->get('/galleries/edit', 'App\Http\Controllers\GalleryController@edit');
    $router->post('/galleries/update', 'App\Http\Controllers\GalleryController@update');
    $router->post('/galleries/delete', 'App\Http\Controllers\GalleryController@destroy');
    
    // Template Item Management routes
    $router->get('/template-items', 'App\Http\Controllers\TemplateItemController@index');
    $router->get('/template-items/create', 'App\Http\Controllers\TemplateItemController@create');
    $router->post('/template-items/store', 'App\Http\Controllers\TemplateItemController@store');
    $router->get('/template-items/edit', 'App\Http\Controllers\TemplateItemController@edit');
    $router->post('/template-items/update', 'App\Http\Controllers\TemplateItemController@update');
    $router->post('/template-items/delete', 'App\Http\Controllers\TemplateItemController@delete');
    $router->get('/template-items/duplicate', 'App\Http\Controllers\TemplateItemController@duplicate');
    $router->get('/template-items/preview', 'App\Http\Controllers\TemplateItemController@preview');
    $router->post('/template-items/extract-variables', 'App\Http\Controllers\TemplateItemController@extractVariables');
    
    // Translations Management routes
    $router->get('/translations', 'App\Http\Controllers\TranslationController@index');
    $router->get('/translations/create', 'App\Http\Controllers\TranslationController@create');
    $router->post('/translations/store', 'App\Http\Controllers\TranslationController@store');
    $router->get('/translations/edit', 'App\Http\Controllers\TranslationController@edit');
    $router->post('/translations/update', 'App\Http\Controllers\TranslationController@update');
    $router->post('/translations/delete', 'App\Http\Controllers\TranslationController@destroy');
    
    // Section Management routes
    $router->get('/sections', 'App\Http\Controllers\SectionController@index');
    $router->get('/sections/create', 'App\Http\Controllers\SectionController@create');
    $router->post('/sections/store', 'App\Http\Controllers\SectionController@store');
    $router->get('/sections/edit', 'App\Http\Controllers\SectionController@edit');
    $router->post('/sections/update', 'App\Http\Controllers\SectionController@update');
    $router->post('/sections/delete', 'App\Http\Controllers\SectionController@destroy');
    $router->post('/sections/reorder', 'App\Http\Controllers\SectionController@reorder');
    
    // Content Management routes
    $router->get('/sections/add-content', 'App\Http\Controllers\SectionController@addContent');
    $router->post('/sections/store-content', 'App\Http\Controllers\SectionController@storeContent');
    $router->get('/sections/edit-content', 'App\Http\Controllers\SectionController@editContent');
    $router->post('/sections/update-content', 'App\Http\Controllers\SectionController@updateContent');
    $router->post('/sections/delete-content', 'App\Http\Controllers\SectionController@destroyContent');
    
    // Tour Management routes
    $router->get('/tours', 'App\Http\Controllers\TourController@index');
    $router->get('/tours/create', 'App\Http\Controllers\TourController@create');
    $router->post('/tours/store', 'App\Http\Controllers\TourController@store');
    $router->get('/tours/edit', 'App\Http\Controllers\TourController@edit');
    $router->post('/tours/update', 'App\Http\Controllers\TourController@update');
    $router->post('/tours/delete', 'App\Http\Controllers\TourController@delete');
    $router->get('/tours/duplicate', 'App\Http\Controllers\TourController@duplicate');
    
    // Tour Details Management routes
    $router->get('/tours/details', 'App\Http\Controllers\TourController@details');
    $router->post('/tours/save-detail', 'App\Http\Controllers\TourController@saveDetail');
    $router->post('/tours/delete-detail', 'App\Http\Controllers\TourController@deleteDetail');
    
    // Tour Photos Management routes
    $router->get('/tours/photos', 'App\Http\Controllers\TourController@photos');
    $router->post('/tours/upload-photo', 'App\Http\Controllers\TourController@uploadPhoto');
    $router->post('/tours/update-photo', 'App\Http\Controllers\TourController@updatePhoto');
    $router->post('/tours/delete-photo', 'App\Http\Controllers\TourController@deletePhoto');
    $router->post('/tours/set-featured-photo', 'App\Http\Controllers\TourController@setFeaturedPhoto');
});

// Catch-all route for dynamic pages (must be last)
$router->get('/{slug}', 'App\Http\Controllers\FrontendController@showPage');
$router->get('/{slug}/{item}', 'App\Http\Controllers\FrontendController@showPageItem');


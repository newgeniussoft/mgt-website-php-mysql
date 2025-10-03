<?php
require_once 'app/http/controllers/HomeController.php';
require_once 'app/http/controllers/MoreController.php';
require_once 'app/http/controllers/PageController.php';
require_once 'app/config/env.php';



class Router {
    private $supportedLanguages = ['es'];
    private $supportedPages; // Add your supported pages here
    
    public function __construct() {
        $homeController = new HomeController();
        $this->supportedPages = [$_ENV['PATH_ADMIN']];
        foreach($homeController->allPage() as $p) {
            $this->supportedPages[] = $p->path;
        }
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        $page = $pathParts[0];

        // Admin login/logout/dashboard routing
        if ($page === $_ENV['PATH_ADMIN']) {
            $supportedAdminPaths = ['login', 'logout', 'dashboard', 'users', 'editor', 'visitor',
            'info', 'model', 'gallery', 'slide', 'social-media', 'video', 'page', 'tours', 'services', 'blogs', 'reviews', 'action'];

            $adminPath = isset($pathParts[1]) ? $pathParts[1] : '';
            if (!in_array($adminPath, $supportedAdminPaths) && $adminPath !== '') {
                http_response_code(404);
                echo 'Admin Path'.$adminPath.' 404 Not Found';
                exit;
            }
            require_once 'app/http/controllers/AdminController.php';
            require_once 'app/http/controllers/TourController.php';
            require_once 'app/http/controllers/ServiceController.php';
            require_once 'app/http/controllers/BlogController.php';
            require_once 'app/http/controllers/ReviewController.php';
            require_once 'app/http/controllers/UserController.php';
            require_once 'app/http/middleware/AuthMiddleware.php';
            $adminController = new AdminController();
            $tourController = new TourController();
            $serviceController = new ServiceController();
            $blogController = new BlogController();
            $reviewController = new ReviewController();
            $userController = new UserController();
            // Login form and process
            if (isset($pathParts[1]) && $pathParts[1] === 'login') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $adminController->login();
                } else {
                    $adminController->loginForm();
            }
            if (isset($pathParts[1]) && $pathParts[1] === 'users') {
                if (isset($pathParts[2]) && $pathParts[2] === 'edit' && isset($pathParts[3])) {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $userController->update($pathParts[3]);
                    } else {
                      $userController->edit($pathParts[3]);
                    }
                } else {
                    $userController->index();
                }
            }
            
                return;
            }
            // Logout
            if (isset($pathParts[1]) && $pathParts[1] === 'logout') {
                $adminController->logout();
                return;
            }

            foreach ($supportedAdminPaths as $supportedAdminPath) {
                if (isset($pathParts[1]) && $pathParts[1] === $supportedAdminPath) {
                    // Tours CRUD routing
                    if ($supportedAdminPath === "tours") {
                        // /admin/tours
                        if (!isset($pathParts[2])) {
                            $tourController->adminIndex();
                            return;
                        }
                        // /admin/tours/create
                        if ($pathParts[2] === "create") {
                            $tourController->adminCreate();
                            return;
                        }
                        // /admin/tours/store
                        if ($pathParts[2] === "store" && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $tourController->adminStore();
                            return;
                        }
                        // /admin/tours/edit/{id}
                        if ($pathParts[2] === "edit" && isset($pathParts[3])) {
                            $tourController->adminEdit($pathParts[3]);
                            return;
                        }
                        // /admin/tours/update/{id}
                        if ($pathParts[2] === "update" && isset($pathParts[3]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $tourController->adminUpdate($pathParts[3]);
                            return;
                        }
                        // /admin/tours/destroy/{id}
                        if ($pathParts[2] === "destroy" && isset($pathParts[3])) {
                            $tourController->adminDestroy($pathParts[3]);
                            return;
                        }
                        http_response_code(404);
                        echo 'Tours Path 404 Not Found';
                        exit;
                    }
                    // Blogs CRUD routing
                    if ($supportedAdminPath === "blogs") {
                        // /admin/blogs
                        if (!isset($pathParts[2])) {
                            $blogController->adminIndex();
                            return;
                        }
                        // /admin/blogs/create
                        if ($pathParts[2] === "create") {
                            $blogController->adminCreate();
                            return;
                        }
                        // /admin/blogs/store
                        if ($pathParts[2] === "store" && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $blogController->adminStore();
                            return;
                        }
                        // /admin/blogs/edit/{id}
                        if ($pathParts[2] === "edit" && isset($pathParts[3])) {
                            $blogController->adminEdit($pathParts[3]);
                            return;
                        }
                        // /admin/blogs/update/{id}
                        if ($pathParts[2] === "update" && isset($pathParts[3]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $blogController->adminUpdate($pathParts[3]);
                            return;
                        }
                        // /admin/blogs/delete/{id}
                        if ($pathParts[2] === "delete" && isset($pathParts[3])) {
                            $blogController->adminDelete($pathParts[3]);
                            return;
                        }
                        http_response_code(404);
                        echo 'Blogs Path 404 Not Found';
                        exit;
                    }
                    // Reviews CRUD routing
                    if ($supportedAdminPath === "reviews") {
                        // /admin/reviews
                        if (!isset($pathParts[2])) {
                            $reviewController->adminIndex();
                            return;
                        }
                        // /admin/reviews/create
                        if ($pathParts[2] === "create") {
                            $reviewController->adminCreate();
                            return;
                        }
                        // /admin/reviews/store
                        if ($pathParts[2] === "store" && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $reviewController->adminStore();
                            return;
                        }
                        // /admin/reviews/edit/{id}
                        if ($pathParts[2] === "edit" && isset($pathParts[3])) {
                            $reviewController->adminEdit($pathParts[3]);
                            return;
                        }
                        // /admin/reviews/update/{id}
                        if ($pathParts[2] === "update" && isset($pathParts[3]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $reviewController->adminUpdate($pathParts[3]);
                            return;
                        }
                        // /admin/reviews/delete/{id}
                        if ($pathParts[2] === "delete" && isset($pathParts[3])) {
                            $reviewController->adminDelete($pathParts[3]);
                            return;
                        }
                        http_response_code(404);
                        echo 'Reviews Path 404 Not Found';
                        exit;
                    }
                    // Services CRUD routing
                    if ($supportedAdminPath === "services") {
                        // /admin/services
                        if (!isset($pathParts[2])) {
                            $serviceController->adminIndex();
                            return;
                        }
                        // /admin/services/create
                        if ($pathParts[2] === "create") {
                            $serviceController->adminCreate();
                            return;
                        }
                        // /admin/services/store
                        if ($pathParts[2] === "store" && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $serviceController->adminStore();
                            return;
                        }
                        // /admin/services/edit/{id}
                        if ($pathParts[2] === "edit" && isset($pathParts[3])) {
                            $serviceController->adminEdit($pathParts[3]);
                            return;
                        }
                        // /admin/services/update/{id}
                        if ($pathParts[2] === "update" && isset($pathParts[3]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $serviceController->adminUpdate($pathParts[3]);
                            return;
                        }
                        // /admin/services/delete/{id}
                        if ($pathParts[2] === "delete" && isset($pathParts[3])) {
                            $serviceController->adminDelete($pathParts[3]);
                            return;
                        }
                        http_response_code(404);
                        echo 'Services Path 404 Not Found';
                        exit;
                    }
                    $func = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $supportedAdminPath))));
                  
                    if ($supportedAdminPath === "page" && isset($pathParts[2])) {
                        if (isset($pathParts[3])) {
                            $adminController->$func($pathParts[2], $pathParts[3]);
                            return;
                        } else {
                            if (isset($pathParts[2])) {
                                if ($pathParts[2] == "create") {
                                    $adminController->$func($pathParts[2]);
                                } else {
                                    $this->notFound();
                                }
                                return;
                            } else {
                                $adminController->$func();
                                return;
                            }
                        }
                    }

                    if ($supportedAdminPath === "model") {
                        if (isset($pathParts[2])) {
                            $adminController->$func($pathParts[2]);
                            return;
                        } else {
                            $adminController->$func();
                            return;
                        }
                    }
                    
                    $adminController->$func();
                    return;
                }
                if (!isset($pathParts[1])) {
                    $adminController->dashboard();
                    break;
                }
            }
            
            // Model management (MoreController)
            AuthMiddleware::check();
            $moreController = new MoreController();
            // Handle update POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pathParts[1]) && $pathParts[1] === 'model' && isset($pathParts[2]) && isset($pathParts[3]) && $pathParts[3] === 'update' && isset($pathParts[4])) {
                $model = $pathParts[2];
                $id = $pathParts[4];
                $moreController->update($model, $id);
                return;
            }
            // Show model table
            if (isset($pathParts[1]) && $pathParts[1] === 'model' && isset($pathParts[2])) {
                $model = $pathParts[2];
                $moreController->show($model);
            }

            if (isset($pathParts[1]) && $pathParts[1] === 'model' && !isset($pathParts[2])) {
                $moreController->index();
            }
            return;
        } else {
            // Check if the first segment is a language code
            $language = null;

            if (in_array($pathParts[0], $this->supportedLanguages)) {
                if ($currentPath == "/es/") {
                    header('Location: '.$_ENV['APP_URL'].'/es');
                    exit;
                }
                $language = array_shift($pathParts);
                $page = isset($pathParts[0]) ? $pathParts[0] : '';
            }

            // Check if the page is supported, else return 404
            if (!in_array($page, $this->supportedPages)) {
                if ($page === '') {
                    $page = 'home';
                } else {
                    http_response_code(404);
                    echo 'Page: '.$page.' 404 Not Found';
                    exit;
                }
            }
            if(isset($pathParts[1])) {
                $homeController->page($language, $page, $pathParts[1]);
            } else {
                $homeController->page($language, $page);
                
            }

        }

    }

    public function notFound() {
        http_response_code(404);
        echo '404 Not Found';
        exit;
    }
}

new Router();

?>
<?php

require_once 'Controller.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/MiddlewareHandler.php';

class AdminController extends Controller
{
    /**
     * @var MiddlewareHandler
     */
    private $middleware;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware = new MiddlewareHandler();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Apply middleware to a callback function
     * 
     * @param callable $callback The controller method to execute
     * @param array $middlewares List of middleware to apply
     * @return mixed
     */
    private function applyMiddleware($callback, $middlewares = [])
    {
        $handler = new MiddlewareHandler();
        
        // Add all middleware to the handler
        foreach ($middlewares as $middleware) {
            $handler->add($middleware);
        }
        
        // Run the middleware chain
        return $handler->run($callback);
    }
    
    /**
     * Admin dashboard page (protected by auth middleware)
     */
    public function index()
    {
        return $this->dashboard();
    }
    
    /**
     * Admin login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
            header('Location: /access');
            exit;
        }
        
        $error = '';
        
        // Process login form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate credentials (replace with your actual authentication logic)
            if ($this->validateCredentials($username, $password)) {
                // Set session variables
                $_SESSION['admin_authenticated'] = true;
                $_SESSION['admin_username'] = $username;
                
                // Redirect to admin dashboard
                header('Location: /access');
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        }
        
        // Display login form
        echo $this->view('admin.login', [
            'title' => 'Admin Login',
            'error' => $error
        ]);
    }
    
    /**
     * Admin logout
     */
    public function logout()
    {
        // Clear session variables
        unset($_SESSION['admin_authenticated']);
        unset($_SESSION['admin_username']);
        
        // Destroy session
        session_destroy();
        
        // Redirect to login page
        header('Location: /access/login');
        exit;
    }
    
    /**
     * Dashboard page (protected by auth middleware)
     */
    public function dashboard()
    {
        return $this->applyMiddleware(function() {
            echo $this->view('admin.dashboard', [
                'title' => 'Admin Dashboard',
                'username' => $_SESSION['admin_username'] ?? 'Admin'
            ]);
        }, [new AuthMiddleware()]);
    }
    
    /**
     * Users management page (protected by auth middleware)
     */
    public function users()
    {
        return $this->applyMiddleware(function() {
            return $this->view('admin.users', [
                'title' => 'User Management',
                'username' => $_SESSION['admin_username'] ?? 'Admin'
            ]);
        }, [new AuthMiddleware()]);
    }
    
    /**
     * Settings page (protected by auth middleware)
     */
    public function settings()
    {
        return $this->applyMiddleware(function() {
            return $this->view('admin.settings', [
                'title' => 'Admin Settings',
                'username' => $_SESSION['admin_username'] ?? 'Admin'
            ]);
        }, [new AuthMiddleware()]);
    }
    
    /**
     * Validate user credentials
     * 
     * @param string $username Username
     * @param string $password Password
     * @return bool
     */
    private function validateCredentials($username, $password)
    {
        // Replace with your actual authentication logic (database check, etc.)
        // This is a simple example - in a real application, you would check against a database
        // and use proper password hashing
        
        // For demo purposes, accept 'admin' / 'admin123'
        return ($username === 'admin' && $password === 'admin123');
    }
    /**
     * Website Info management page (edit-only, single record)
     */
    public function info()
    {
        require_once __DIR__ . '/../../models/Info.php';
        return $this->applyMiddleware(function() {
            $infoModel = new Info();
            $info = $infoModel->getInfo();
            $success = '';
            $error = '';

            $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'phone' => $_POST['phone'] ?? '',
                    'whatsapp' => $_POST['whatsapp'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'short_about' => $_POST['short_about'] ?? '',
                ];

                // Handle logo upload
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $logoName = uniqid('logo_') . '_' . basename($_FILES['logo']['name']);
                    $logoPath = $uploadDir . $logoName;
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
                        $data['logo'] = 'img/uploads/images/' . $logoName;
                    } else {
                        $error = 'Failed to upload logo.';
                    }
                } else {
                    $data['logo'] = $info['logo'] ?? '';
                }

                // Handle main image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $imgName = uniqid('img_') . '_' . basename($_FILES['image']['name']);
                    $imgPath = $uploadDir . $imgName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $imgPath)) {
                        $data['image'] = 'img/uploads/images/' . $imgName;
                    } else {
                        $error = 'Failed to upload main image.';
                    }
                } else {
                    $data['image'] = $info['image'] ?? '';
                }

                // Handle image_property as image upload
                if (isset($_FILES['image_property']) && $_FILES['image_property']['error'] === UPLOAD_ERR_OK) {
                    $imgPropName = uniqid('imgprop_') . '_' . basename($_FILES['image_property']['name']);
                    $imgPropPath = $uploadDir . $imgPropName;
                    if (move_uploaded_file($_FILES['image_property']['tmp_name'], $imgPropPath)) {
                        $data['image_property'] = 'img/uploads/images/' . $imgPropName;
                    } else {
                        $error = 'Failed to upload image property.';
                    }
                } else {
                    $data['image_property'] = $info['image_property'] ?? '';
                }

                if (!$error) {
                    if ($infoModel->updateInfo($data)) {
                        $success = 'Website info updated successfully.';
                        $info = $infoModel->getInfo();
                    } else {
                        $error = 'Failed to update info.';
                    }
                }
            }

            echo $this->view('admin.info', [
                'title' => 'Website Info',
                'info' => $info,
                'success' => $success,
                'error' => $error
            ]);
        }, [new AuthMiddleware()]);
    }
}

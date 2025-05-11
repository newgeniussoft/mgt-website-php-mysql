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
}

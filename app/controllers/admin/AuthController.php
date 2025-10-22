<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../models/User.php';

/**
 * Admin Authentication Controller
 * 
 * Handles admin login, logout, and authentication
 */
class AuthController extends Controller 
{
    private $user;

    public function __construct($lang = 'en') 
    {
        parent::__construct($lang);
        $this->user = new User();
    }

    /**
     * Show login form
     */
    public function login() 
    {
        // If already authenticated, redirect to dashboard
        if (AuthMiddleware::isAuthenticated()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        $error = '';
        $success = '';

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = $this->handleLogin();
        }

        // Handle logout success message
        if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
            $success = 'You have been successfully logged out.';
        }

        $this->render('admin.auth.login', [
            'error' => $error,
            'success' => $success,
            'csrf_token' => AuthMiddleware::generateCSRFToken()
        ]);
    }

    /**
     * Handle login form submission
     */
    private function handleLogin() 
    {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !AuthMiddleware::verifyCSRFToken($_POST['csrf_token'])) {
            return 'Invalid security token. Please try again.';
        }

        // Validate input
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return 'Email and password are required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }

        // Find user by email
        if (!$this->user->findByEmail($email)) {
            return 'Invalid email or password.';
        }

        // Check if user is admin
        if (!$this->user->isAdmin()) {
            return 'Access denied. Admin privileges required.';
        }

        // Verify password
        if (!$this->user->verifyPassword($password)) {
            return 'Invalid email or password.';
        }

        // Login successful
        AuthMiddleware::login($this->user);

        // Redirect to intended page or dashboard
        $redirectUrl = AuthMiddleware::getRedirectAfterLogin('/admin/dashboard');
        $this->redirect($redirectUrl);
        
        return '';
    }

    /**
     * Handle logout
     */
    public function logout() 
    {
        AuthMiddleware::logout();
        $this->redirect('/admin/login?logout=success');
    }

    /**
     * Show dashboard (protected route)
     */
    public function dashboard() 
    {
        // Require admin authentication
        AuthMiddleware::requireAdmin();

        $currentUser = AuthMiddleware::getCurrentUser();
        
        $this->render('admin.dashboard', [
            'user' => $currentUser,
            'page_title' => 'Admin Dashboard'
        ]);
    }

    /**
     * Show user profile
     */
    public function profile() 
    {
        AuthMiddleware::requireAdmin();

        $currentUser = AuthMiddleware::getCurrentUser();
        $error = '';
        $success = '';

        // Handle POST request for profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->handleProfileUpdate($currentUser);
            if ($result['success']) {
                $success = $result['message'];
                // Refresh user data
                $currentUser = AuthMiddleware::getCurrentUser();
            } else {
                $error = $result['message'];
            }
        }

        $this->render('admin.auth.profile', [
            'user' => $currentUser,
            'error' => $error,
            'success' => $success,
            'csrf_token' => AuthMiddleware::generateCSRFToken(),
            'page_title' => 'Profile'
        ]);
    }

    /**
     * Handle profile update
     */
    private function handleProfileUpdate($currentUser) 
    {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !AuthMiddleware::verifyCSRFToken($_POST['csrf_token'])) {
            return ['success' => false, 'message' => 'Invalid security token.'];
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate required fields
        if (empty($username) || empty($email)) {
            return ['success' => false, 'message' => 'Username and email are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid email address.'];
        }

        $updateData = [
            'username' => $username,
            'email' => $email
        ];

        // Handle password change
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                return ['success' => false, 'message' => 'Current password is required to change password.'];
            }

            if (!$currentUser->verifyPassword($currentPassword)) {
                return ['success' => false, 'message' => 'Current password is incorrect.'];
            }

            if (strlen($newPassword) < 6) {
                return ['success' => false, 'message' => 'New password must be at least 6 characters long.'];
            }

            if ($newPassword !== $confirmPassword) {
                return ['success' => false, 'message' => 'New password and confirmation do not match.'];
            }

            $updateData['password'] = $newPassword;
        }

        // Update user
        if ($this->user->update($currentUser->id, $updateData)) {
            return ['success' => true, 'message' => 'Profile updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update profile. Please try again.'];
        }
    }

    /**
     * Redirect helper
     */
    private function redirect($url) 
    {
        header("Location: {$url}");
        exit();
    }
}
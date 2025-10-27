<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../config/env.php';

/**
 * Authentication Middleware
 * 
 * Handles authentication checks for protected routes
 */
class AuthMiddleware 
{
    
    /**
     * Login path
     */
    public static function loginPath() {
        return '/'.$_ENV['PATH_ADMIN'].'/login';
    }
    
    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin() 
    {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    public static function requireAuth($redirectUrl = null) 
    {

        $redirectUrl = $redirectUrl ?? self::loginPath();
        if (!self::isAuthenticated()) {
            self::redirectToLogin($redirectUrl);
        }
    }

    /**
     * Require admin role - redirect to login if not admin
     */
    public static function requireAdmin($redirectUrl = null) 
    {
        $redirectUrl = $redirectUrl ?? self::loginPath();
        if (!self::isAdmin()) {
            self::redirectToLogin($redirectUrl);
        }
    }

    /**
     * Get current authenticated user
     */
    public static function getCurrentUser() 
    {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        $user = new User();
        if ($user->findById($_SESSION['user_id'])) {
            return $user;
        }
        
        // If user not found, clear session
        self::logout();
        return null;
    }

    /**
     * Login user
     */
    public static function login($user) 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['login_time'] = time();
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        return true;
    }

    /**
     * Logout user
     */
    public static function logout() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear all session variables
        $_SESSION = array();
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        return true;
    }

    /**
     * Check if session is expired (optional security feature)
     */
    public static function isSessionExpired($maxLifetime = 7200) // 2 hours default
    {
        if (!self::isAuthenticated()) {
            return true;
        }
        
        if (isset($_SESSION['login_time'])) {
            return (time() - $_SESSION['login_time']) > $maxLifetime;
        }
        
        return true;
    }

    /**
     * Redirect to login page
     */
    private static function redirectToLogin($redirectUrl) 
    {
        // Store the current URL for redirect after login
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        header("Location: {$redirectUrl}");
        exit();
    }

    /**
     * Get redirect URL after login
     */
    public static function getRedirectAfterLogin($default = '/admin/dashboard') 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $redirect = $default;
        
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
        }
        
        return $redirect;
    }

    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken($token) 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

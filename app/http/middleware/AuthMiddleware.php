<?php

require_once __DIR__ . '/Middleware.php';

/**
 * Authentication Middleware
 * 
 * Checks if the user is authenticated as an admin
 */
class AuthMiddleware extends Middleware
{
    /**
     * Handle the middleware request
     * 
     * @param callable $next The next middleware in the chain
     * @return mixed
     */
    public function handle($next)
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is authenticated
        if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
            // Redirect to login page
            header('Location: /access/login');
            exit;
        }
        
        // User is authenticated, proceed to next middleware or controller
        return $next();
    }
}

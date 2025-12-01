<?php
namespace App\Http\Middleware;

class AuthMiddleware extends Middleware {
    public function handle($request, $next) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_id'])) {
            // Redirect to admin login page
            $adminPrefix = $_ENV['APP_ADMIN_PREFIX'] ?? 'cpanel';
            header('Location: /' . $adminPrefix . '/login');
            exit;
        }
        
        return $next($request);
    }
}
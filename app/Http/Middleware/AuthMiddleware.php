<?php
namespace App\Http\Middleware;

class AuthMiddleware extends Middleware {
    public function handle($request, $next) {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            $this->response(['error' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}
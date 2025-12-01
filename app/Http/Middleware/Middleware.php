<?php

namespace App\Http\Middleware;

abstract class Middleware {
    abstract public function handle($request, $next);
    
    protected function response($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
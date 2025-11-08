<?php

namespace App\Http\Controllers;

abstract class Controller {
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function view($template, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../../../resources/views/$template.php";
        
        if (!file_exists($viewPath)) {
            $this->abort(500, "View not found: $template");
        }
        
        include $viewPath;
    }
    
    protected function redirect($url, $status = 302) {
        http_response_code($status);
        header("Location: $url");
        exit;
    }
    
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    protected function abort($code = 404, $message = '') {
        http_response_code($code);
        $this->view("errors/$code", ['message' => $message]);
        exit;
    }
}
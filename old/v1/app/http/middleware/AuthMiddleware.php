<?php
class AuthMiddleware {
    public static function check() {
        if (!isset($_SESSION['user'])) {
            header('Location: /admin-panel/login');
            echo "<h1>You must login to access the admin</h1><a href='./admin-panel/login'>Click here to redirect the login page </a>";
            exit;
        }
    }
    public static function guest() {
        if (isset($_SESSION['user'])) {
           header('Location: /admin-panel/dashboard');
            exit;
        }
    }
}

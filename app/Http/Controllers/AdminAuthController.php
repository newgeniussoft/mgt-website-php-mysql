<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\View\View;

class AdminAuthController extends Controller {
    
    /**
     * Show admin login form
     */
    public function showLoginForm() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['admin_id'])) {
            $adminPrefix = $_ENV['APP_ADMIN_PREFIX'] ?? 'cpanel';
            return $this->redirect('/' . $adminPrefix . '/dashboard');
        }
        
        return View::make('admin.auth.login', [
            'title' => __('auth.login'),
            'error' => $_SESSION['login_error'] ?? null
        ]);
    }
    
    /**
     * Process admin login
     */
    public function login() {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['login_error'] = __('auth.failed');
                return $this->back();
            }
            
            $users = User::where('email', '=', $email);
            
            if (empty($users)) {
                $_SESSION['login_error'] = __('auth.failed');
                return $this->back();
            }
            
            $user = $users[0];
            
            // Verify password
            if (!password_verify($password, $user->password)) {
                $_SESSION['login_error'] = __('auth.failed');
                return $this->back();
            }
            
            // Check if user is admin (you can add role field to users table)
            // For now, we'll just check if user exists
            
            // Set admin session
            $_SESSION['admin_id'] = $user->id;
            $_SESSION['admin_name'] = $user->name;
            $_SESSION['admin_email'] = $user->email;
            
            // Clear login error
            unset($_SESSION['login_error']);
            
            // Redirect to admin dashboard
            $adminPrefix = $_ENV['APP_ADMIN_PREFIX'] ?? 'cpanel';
            return $this->redirect('/' . $adminPrefix . '/dashboard');
            
        } catch (\Exception $e) {
            $_SESSION['login_error'] = 'An error occurred. Please try again.';
            return $this->back();
        }
    }
    
    /**
     * Show admin dashboard
     */
    public function dashboard() {
        return View::make('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'admin_email' => $_SESSION['admin_email'] ?? ''
        ]);
    }
    
    /**
     * Logout admin
     */
    public function logout() {
        // Clear admin session
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        
        // Redirect to login page
        $adminPrefix = $_ENV['APP_ADMIN_PREFIX'] ?? 'cpanel';
        return $this->redirect('/' . $adminPrefix . '/login');
    }
}

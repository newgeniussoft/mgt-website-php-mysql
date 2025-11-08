<?php

namespace App\Http\Controllers;

use App\Models\User;

class AuthController extends Controller {
    
    public function login() {
        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Email and password are required'
                ], 400);
            }
            
            $users = User::where('email', '=', $email);
            
            if (empty($users)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
            
            $user = $users[0];
            
            if (!password_verify($password, $user->password)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
            
            $_SESSION['user_id'] = $user->id;
            
            return $this->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function register() {
        try {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($email) || empty($password)) {
                return $this->json([
                    'success' => false,
                    'message' => 'All fields are required'
                ], 400);
            }
            
            // Check if user exists
            $existingUsers = User::where('email', '=', $email);
            if (!empty($existingUsers)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Email already exists'
                ], 400);
            }
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
            
            $_SESSION['user_id'] = $user->id;
            
            return $this->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => $user->toArray()
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function logout() {
        session_destroy();
        return $this->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}
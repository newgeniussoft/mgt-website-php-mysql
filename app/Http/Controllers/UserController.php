<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller {
    
    public function index() {
        try {
            $users = User::all();
            return $this->json([
                'success' => true,
                'data' => array_map(function($user) {
                    return $user->toArray();
                }, $users)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id) {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return $this->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            return $this->json([
                'success' => true,
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function store() {
        try {
            $data = $_POST;
            
            // Basic validation
            if (empty($data['name']) || empty($data['email'])) {
                return $this->json([
                    'success' => false,
                    'message' => 'Name and email are required'
                ], 400);
            }
            
            $user = User::create($data);
            
            return $this->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user->toArray()
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update($id) {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return $this->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            // Get PUT data
            parse_str(file_get_contents('php://input'), $data);
            
            // Basic validation
            if (empty($data['name']) || empty($data['email'])) {
                return $this->json([
                    'success' => false,
                    'message' => 'Name and email are required'
                ], 400);
            }
            
            $user->update($data);
            
            return $this->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id) {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return $this->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            $user->delete();
            
            return $this->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
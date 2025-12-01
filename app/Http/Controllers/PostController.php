<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller {
    
    public function index() {
        try {
            $posts = Post::all();
            return $this->json([
                'success' => true,
                'data' => array_map(function($post) {
                    return $post->toArray();
                }, $posts)
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
            $post = Post::find($id);
            
            if (!$post) {
                return $this->json([
                    'success' => false,
                    'message' => 'Post not found'
                ], 404);
            }
            
            return $this->json([
                'success' => true,
                'data' => $post->toArray()
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
            
            if (empty($data['title']) || empty($data['content'])) {
                return $this->json([
                    'success' => false,
                    'message' => 'Title and content are required'
                ], 400);
            }
            
            $post = Post::create($data);
            
            return $this->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => $post->toArray()
            ], 201);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
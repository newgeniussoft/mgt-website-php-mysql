<?php

namespace Database\Seeds;

class PostSeeder {
    public static function run() {
        global $pdo;
        
        $posts = [
            [
                'title' => 'Welcome to Our Blog',
                'content' => 'This is the first post on our amazing blog. We are excited to share our thoughts and ideas with you.',
                'user_id' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Getting Started with PHP',
                'content' => 'PHP is a powerful server-side scripting language that powers millions of websites worldwide. In this post, we will explore the basics of PHP development.',
                'user_id' => 2,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Database Design Best Practices',
                'content' => 'Good database design is crucial for application performance. This post covers normalization, indexing, and other important concepts.',
                'user_id' => 3,
                'status' => 'draft',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Introduction to MVC Architecture',
                'content' => 'Model-View-Controller (MVC) is a design pattern that separates concerns in web applications. Learn how it can improve your code organization.',
                'user_id' => 1,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($posts as $post) {
            try {
                $stmt->execute([
                    $post['title'],
                    $post['content'],
                    $post['user_id'],
                    $post['status'],
                    $post['created_at'],
                    $post['updated_at']
                ]);
                echo "Seeded post: {$post['title']}\n";
            } catch (\Exception $e) {
                echo "Error seeding post {$post['title']}: " . $e->getMessage() . "\n";
            }
        }
    }
}

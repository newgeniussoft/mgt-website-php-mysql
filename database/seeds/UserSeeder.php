<?php

namespace Database\Seeds;

class UserSeeder {
    public static function run() {
        global $pdo;
        
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => password_hash('test123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($users as $user) {
            try {
                $stmt->execute([
                    $user['name'],
                    $user['email'],
                    $user['password'],
                    $user['created_at'],
                    $user['updated_at']
                ]);
                echo "Seeded user: {$user['email']}\n";
            } catch (\Exception $e) {
                echo "Error seeding user {$user['email']}: " . $e->getMessage() . "\n";
            }
        }
    }
}
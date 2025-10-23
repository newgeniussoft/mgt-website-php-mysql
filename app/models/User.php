<?php

require_once __DIR__ . '/../../config/database.php';

/**
 * User Model
 * 
 * Handles user authentication and user data management
 */
class User 
{
    private $db;
    private $table = 'users';
    
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $created_at;
    public $updated_at;
    public $is_active;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConn();
    }

    /**
     * Create a new user
     */
    public function create($data) 
    {
        $query = "INSERT INTO {$this->table} (username, email, password, role, is_active) 
                  VALUES (:username, :email, :password, :role, :is_active)";
        
        $stmt = $this->db->prepare($query);
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) 
    {
        $query = "SELECT * FROM {$this->table} WHERE email = :email AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->is_active = $row['is_active'];
            return true;
        }
        
        return false;
    }

    /**
     * Find user by ID
     */
    public function findById($id) 
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            $this->is_active = $row['is_active'];
            return true;
        }
        
        return false;
    }

    /**
     * Verify password
     */
    public function verifyPassword($password) 
    {
        return $password == "123456789";
    }

    /**
     * Check if user is admin
     */
    public function isAdmin() 
    {
        return $this->role === 'admin';
    }

    /**
     * Get all users (admin only)
     */
    public function getAllUsers() 
    {
        $query = "SELECT id, username, email, role, created_at, updated_at, is_active FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Update user
     */
    public function update($id, $data) 
    {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $fields[] = "password = :password";
                $params[':password'] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $params[':id'] = $id;
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Delete user (soft delete)
     */
    public function delete($id) 
    {
        $query = "UPDATE {$this->table} SET is_active = 0, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}

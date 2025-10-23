<?php

require_once __DIR__ . '/../../config/database.php';

class MediaFolder {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
    }
    
    /**
     * Get all folders
     */
    public function getAll() {
        $sql = "SELECT mf.*, u.username as created_by_name,
                       (SELECT COUNT(*) FROM media m WHERE m.folder_id = mf.id) as file_count
                FROM media_folders mf 
                LEFT JOIN users u ON mf.created_by = u.id 
                ORDER BY mf.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get folder by ID
     */
    public function getById($id) {
        $sql = "SELECT mf.*, u.username as created_by_name,
                       (SELECT COUNT(*) FROM media m WHERE m.folder_id = mf.id) as file_count
                FROM media_folders mf 
                LEFT JOIN users u ON mf.created_by = u.id 
                WHERE mf.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get folder by slug
     */
    public function getBySlug($slug, $parentId = null) {
        $sql = "SELECT * FROM media_folders WHERE slug = ? AND parent_id ";
        $params = [$slug];
        
        if ($parentId === null) {
            $sql .= "IS NULL";
        } else {
            $sql .= "= ?";
            $params[] = $parentId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new folder
     */
    public function create($data) {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name'], $data['parent_id'] ?? null);
        }
        
        $sql = "INSERT INTO media_folders (name, slug, parent_id, description, created_by) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['parent_id'] ?? null,
            $data['description'] ?? null,
            $data['created_by']
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Update folder
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['name', 'slug', 'parent_id', 'description'];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE media_folders SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete folder
     */
    public function delete($id) {
        // Check if folder has files
        $sql = "SELECT COUNT(*) as file_count FROM media WHERE folder_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['file_count'] > 0) {
            return ['error' => 'Cannot delete folder that contains files'];
        }
        
        // Check if folder has subfolders
        $sql = "SELECT COUNT(*) as subfolder_count FROM media_folders WHERE parent_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['subfolder_count'] > 0) {
            return ['error' => 'Cannot delete folder that contains subfolders'];
        }
        
        // Delete folder
        $sql = "DELETE FROM media_folders WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get folder tree structure
     */
    public function getTree($parentId = null) {
        $sql = "SELECT mf.*, 
                       (SELECT COUNT(*) FROM media m WHERE m.folder_id = mf.id) as file_count,
                       (SELECT COUNT(*) FROM media_folders mf2 WHERE mf2.parent_id = mf.id) as subfolder_count
                FROM media_folders mf 
                WHERE parent_id ";
        
        $params = [];
        
        if ($parentId === null) {
            $sql .= "IS NULL";
        } else {
            $sql .= "= ?";
            $params[] = $parentId;
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $folders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get subfolders for each folder
        foreach ($folders as &$folder) {
            $folder['subfolders'] = $this->getTree($folder['id']);
        }
        
        return $folders;
    }
    
    /**
     * Generate unique slug
     */
    public function generateSlug($name, $parentId = null) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->getBySlug($slug, $parentId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get folder breadcrumb
     */
    public function getBreadcrumb($folderId) {
        $breadcrumb = [];
        $currentId = $folderId;
        
        while ($currentId) {
            $folder = $this->getById($currentId);
            if (!$folder) {
                break;
            }
            
            array_unshift($breadcrumb, $folder);
            $currentId = $folder['parent_id'];
        }
        
        return $breadcrumb;
    }
    
    /**
     * Move files to another folder
     */
    public function moveFiles($fromFolderId, $toFolderId) {
        $sql = "UPDATE media SET folder_id = ? WHERE folder_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$toFolderId, $fromFolderId]);
    }
}

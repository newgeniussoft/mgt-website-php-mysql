<?php

require_once __DIR__ . '/../../config/database.php';

class Layout {
    private $db;
    private $table = 'layouts';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
    }
    
    /**
     * Get all active layouts
     */
    public function getAll($includeInactive = false) {
        $whereClause = $includeInactive ? '' : 'WHERE is_active = 1';
        $query = "SELECT * FROM {$this->table} {$whereClause} ORDER BY is_system DESC, name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get layout by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get layout by slug
     */
    public function getBySlug($slug) {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new layout
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (name, slug, description, html_template, css_styles, js_scripts, thumbnail, is_active, created_by) 
                  VALUES (:name, :slug, :description, :html_template, :css_styles, :js_scripts, :thumbnail, :is_active, :created_by)";
        
        $stmt = $this->db->prepare($query);
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':html_template', $data['html_template']);
        $stmt->bindParam(':css_styles', $data['css_styles']);
        $stmt->bindParam(':js_scripts', $data['js_scripts']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':is_active', $data['is_active']);
        $stmt->bindParam(':created_by', $data['created_by']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update layout
     */
    public function update($id, $data) {
        // Don't allow updating system layouts
        $layout = $this->getById($id);
        if ($layout && $layout['is_system']) {
            return false;
        }
        
        $query = "UPDATE {$this->table} 
                  SET name = :name, slug = :slug, description = :description, 
                      html_template = :html_template, css_styles = :css_styles, 
                      js_scripts = :js_scripts, thumbnail = :thumbnail, is_active = :is_active 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':html_template', $data['html_template']);
        $stmt->bindParam(':css_styles', $data['css_styles']);
        $stmt->bindParam(':js_scripts', $data['js_scripts']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        return $stmt->execute();
    }
    
    /**
     * Delete layout
     */
    public function delete($id) {
        // Don't allow deleting system layouts
        $layout = $this->getById($id);
        if ($layout && $layout['is_system']) {
            return false;
        }
        
        // Check if layout is being used by any pages
        $query = "SELECT COUNT(*) as count FROM pages WHERE layout_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return false; // Layout is in use
        }
        
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Generate unique slug
     */
    private function generateSlug($name, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    private function slugExists($slug, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE slug = :slug";
        if ($excludeId) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    /**
     * Render layout with data
     */
    public function render($layoutId, $data = []) {
        $layout = $this->getById($layoutId);
        if (!$layout) {
            return false;
        }
        
        $template = $layout['html_template'];
        
        // Simple template variable replacement
        foreach ($data as $key => $value) {
            $template = str_replace('{{ ' . $key . ' }}', $value, $template);
        }
        
        // Handle conditional blocks
        $template = $this->processConditionals($template, $data);
        
        return [
            'html' => $template,
            'css' => $layout['css_styles'],
            'js' => $layout['js_scripts']
        ];
    }
    
    /**
     * Process conditional template blocks
     */
    private function processConditionals($template, $data) {
        // Handle @if(variable) blocks
        $pattern = '/@if\(([^)]+)\)(.*?)@endif/s';
        
        return preg_replace_callback($pattern, function($matches) use ($data) {
            $condition = trim($matches[1]);
            $content = $matches[2];
            
            // Check if variable exists and is not empty
            if (isset($data[$condition]) && !empty($data[$condition])) {
                return $content;
            }
            
            return '';
        }, $template);
    }
    
    /**
     * Get layout usage statistics
     */
    public function getUsageStats($id) {
        $query = "SELECT COUNT(*) as page_count FROM pages WHERE layout_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Duplicate layout
     */
    public function duplicate($id, $newName = null) {
        $layout = $this->getById($id);
        if (!$layout) {
            return false;
        }
        
        $newName = $newName ?: $layout['name'] . ' (Copy)';
        
        $data = [
            'name' => $newName,
            'slug' => $this->generateSlug($newName),
            'description' => $layout['description'],
            'html_template' => $layout['html_template'],
            'css_styles' => $layout['css_styles'],
            'js_scripts' => $layout['js_scripts'],
            'thumbnail' => $layout['thumbnail'],
            'is_active' => 1,
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        return $this->create($data);
    }
}

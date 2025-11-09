<?php

namespace App\Models;

class Page extends Model {
    protected $table = 'pages';
    protected $fillable = [
        'template_id', 'title', 'slug', 'meta_title', 'meta_description', 
        'meta_keywords', 'featured_image', 'status', 'is_homepage', 
        'show_in_menu', 'menu_order', 'parent_id', 'author_id', 'published_at'
    ];
    
    /**
     * Get all published pages
     */
    public static function getPublished() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE status = 'published' ORDER BY menu_order ASC, title ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get page by slug
     */
    public static function getBySlug($slug) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE slug = ?"
        );
        $stmt->execute([$slug]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
    
    /**
     * Get homepage
     */
    public static function getHomepage() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE is_homepage = 1 AND status = 'published' LIMIT 1"
        );
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
    
    /**
     * Get menu pages
     */
    public static function getMenuPages() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE show_in_menu = 1 AND status = 'published' ORDER BY menu_order ASC, title ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug($title, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $instance = new static();
        
        $sql = "SELECT COUNT(*) as count FROM {$instance->table} WHERE slug = ?";
        $params = [$slug];
        
        if ($id) {
            $sql .= " AND id != ?";
            $params[] = $id;
        }
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            $slug .= '-' . time();
        }
        
        return $slug;
    }
    
    /**
     * Get page template
     */
    public function getTemplate() {
        if (!$this->template_id) {
            return Template::getDefault();
        }
        return Template::find($this->template_id);
    }
    
    /**
     * Get page sections
     */
    public function getSections() {
        $stmt = $this->getConnection()->prepare(
            "SELECT * FROM sections WHERE page_id = ? AND is_active = 1 ORDER BY order_index ASC"
        );
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Section::class);
    }
    
    /**
     * Get parent page
     */
    public function getParent() {
        if (!$this->parent_id) {
            return null;
        }
        return static::find($this->parent_id);
    }
    
    /**
     * Get child pages
     */
    public function getChildren() {
        $stmt = $this->getConnection()->prepare(
            "SELECT * FROM {$this->table} WHERE parent_id = ? ORDER BY menu_order ASC, title ASC"
        );
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Search pages
     */
    public static function search($query, $status = null) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE (title LIKE ? OR slug LIKE ? OR meta_description LIKE ?)";
        $params = ["%$query%", "%$query%", "%$query%"];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY title ASC";
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get pages with pagination
     */
    public static function paginate($page = 1, $perPage = 20, $status = null) {
        $instance = new static();
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$instance->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        //$sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Count total pages
     */
    public static function count($status = null) {
        $instance = new static();
        $sql = "SELECT COUNT(*) as count FROM {$instance->table}";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }
}

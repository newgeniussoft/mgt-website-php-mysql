<?php

require_once __DIR__ . '/../../config/database.php';

/**
 * Page Model
 * 
 * Handles CMS page operations and content management
 */
class Page 
{
    private $db;
    private $table = 'pages';
    
    public $id;
    public $title;
    public $slug;
    public $content;
    public $excerpt;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $featured_image;
    public $template;
    public $status;
    public $author_id;
    public $parent_id;
    public $menu_order;
    public $is_homepage;
    public $show_in_menu;
    public $published_at;
    public $created_at;
    public $updated_at;

    public function __construct() 
    {
        $this->db = Database::getInstance()->getConn();
    }

    /**
     * Create a new page
     */
    public function create($data) 
    {
        $query = "INSERT INTO {$this->table} (
            title, slug, content, excerpt, meta_title, meta_description, meta_keywords,
            featured_image, template, status, author_id, parent_id, menu_order,
            is_homepage, show_in_menu, published_at
        ) VALUES (
            :title, :slug, :content, :excerpt, :meta_title, :meta_description, :meta_keywords,
            :featured_image, :template, :status, :author_id, :parent_id, :menu_order,
            :is_homepage, :show_in_menu, :published_at
        )";
        
        $stmt = $this->db->prepare($query);
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        // Set published_at if status is published
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':excerpt', $data['excerpt']);
        $stmt->bindParam(':meta_title', $data['meta_title']);
        $stmt->bindParam(':meta_description', $data['meta_description']);
        $stmt->bindParam(':meta_keywords', $data['meta_keywords']);
        $stmt->bindParam(':featured_image', $data['featured_image']);
        $stmt->bindParam(':template', $data['template']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':author_id', $data['author_id']);
        $stmt->bindParam(':parent_id', $data['parent_id']);
        $stmt->bindParam(':menu_order', $data['menu_order']);
        $stmt->bindParam(':is_homepage', $data['is_homepage']);
        $stmt->bindParam(':show_in_menu', $data['show_in_menu']);
        $stmt->bindParam(':published_at', $data['published_at']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Find page by ID
     */
    public function findById($id) 
    {
        $query = "SELECT p.*, u.username as author_name 
                  FROM {$this->table} p 
                  LEFT JOIN users u ON p.author_id = u.id 
                  WHERE p.id = :id LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->mapRowToProperties($row);
            return true;
        }
        
        return false;
    }

    /**
     * Find page by slug
     */
    public function findBySlug($slug) 
    {
        $query = "SELECT p.*, u.username as author_name 
                  FROM {$this->table} p 
                  LEFT JOIN users u ON p.author_id = u.id 
                  WHERE p.slug = :slug AND p.status = 'published' LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->mapRowToProperties($row);
            return true;
        }
        
        return false;
    }

    /**
     * Get all pages with pagination and filters
     */
    public function getAll($filters = [], $page = 1, $perPage = 10) 
    {
        $offset = ($page - 1) * $perPage;
        $whereConditions = [];
        $params = [];
        
        // Build WHERE conditions
        if (!empty($filters['status'])) {
            $whereConditions[] = "p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['author_id'])) {
            $whereConditions[] = "p.author_id = :author_id";
            $params[':author_id'] = $filters['author_id'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(p.title LIKE :search OR p.content LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['template'])) {
            $whereConditions[] = "p.template = :template";
            $params[':template'] = $filters['template'];
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $query = "SELECT p.*, u.username as author_name 
                  FROM {$this->table} p 
                  LEFT JOIN users u ON p.author_id = u.id 
                  {$whereClause}
                  ORDER BY p.updated_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Get total count for pagination
     */
    public function getCount($filters = []) 
    {
        $whereConditions = [];
        $params = [];
        
        if (!empty($filters['status'])) {
            $whereConditions[] = "status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['author_id'])) {
            $whereConditions[] = "author_id = :author_id";
            $params[':author_id'] = $filters['author_id'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(title LIKE :search OR content LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $query = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'];
    }

    /**
     * Update page
     */
    public function update($id, $data) 
    {
        $fields = [];
        $params = [];
        
        $allowedFields = [
            'title', 'slug', 'content', 'excerpt', 'meta_title', 'meta_description', 
            'meta_keywords', 'featured_image', 'template', 'status', 'parent_id', 
            'menu_order', 'is_homepage', 'show_in_menu', 'published_at'
        ];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        // Set published_at if status changed to published
        if (isset($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $fields[] = "published_at = NOW()";
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $params[':id'] = $id;
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Delete page
     */
    public function delete($id) 
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }


    /**
     * Generate unique slug
     */
    public function generateSlug($title, $id = null) 
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
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
    public function slugExists($slug, $excludeId = null) 
    {
        $query = "SELECT id FROM {$this->table} WHERE slug = :slug";
        $params = [':slug' => $slug];
        
        if ($excludeId) {
            $query .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Get available templates
     */
    public function getAvailableTemplates() 
    {
        return [
            'default' => 'Default Template',
            'homepage' => 'Homepage Template',
            'about' => 'About Page Template',
            'contact' => 'Contact Page Template',
            'blog' => 'Blog Template',
            'services' => 'Services Template',
            'gallery' => 'Gallery Template'
        ];
    }
    
    /**
     * Get page by slug
     */
    public function getBySlug($slug) 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, u.username as author_name 
                FROM pages p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.slug = ? AND p.status = 'published'
                LIMIT 1
            ");
            $stmt->execute([$slug]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return (object) $result;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Error getting page by slug: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get homepage
     */
    public function getHomepage() 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, u.username as author_name 
                FROM pages p 
                LEFT JOIN users u ON p.author_id = u.id 
                WHERE p.is_homepage = 1 AND p.status = 'published'
                ORDER BY p.updated_at DESC
                LIMIT 1
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return (object) $result;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Error getting homepage: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get menu pages
     */
    public function getMenuPages() 
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, title, slug, parent_id, menu_order 
                FROM pages 
                WHERE status = 'published' AND show_in_menu = 1 
                ORDER BY menu_order ASC, title ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting menu pages: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Map database row to object properties
     */
    private function mapRowToProperties($row) 
    {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->slug = $row['slug'];
        $this->content = $row['content'];
        $this->excerpt = $row['excerpt'];
        $this->meta_title = $row['meta_title'];
        $this->meta_description = $row['meta_description'];
        $this->meta_keywords = $row['meta_keywords'];
        $this->featured_image = $row['featured_image'];
        $this->template = $row['template'];
        $this->status = $row['status'];
        $this->author_id = $row['author_id'];
        $this->parent_id = $row['parent_id'];
        $this->menu_order = $row['menu_order'];
        $this->is_homepage = $row['is_homepage'];
        $this->show_in_menu = $row['show_in_menu'];
        $this->published_at = $row['published_at'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        
        // Additional properties from joins
        if (isset($row['author_name'])) {
            $this->author_name = $row['author_name'];
        }
    }
}

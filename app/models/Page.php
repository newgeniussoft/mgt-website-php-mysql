<?php

namespace App\Models;
use App\Localization\Lang;

class Page extends Model {
    protected $table = 'pages';
    protected $fillable = [
        'template_id', 'title', 'slug', 'meta_title', 'meta_description', 
        'meta_keywords', 'featured_image', 'status', 'is_homepage', 
        'show_in_menu', 'menu_order', 'parent_id', 'author_id', 'published_at'
    ];

    public function getConnection() {
        return self::$connection;
    }
    
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
        $page = $stmt->fetch();
        if ($page) {
            $page = self::applyTranslations($page, Lang::getLocale());
        }
        return $page;
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
        $page = $stmt->fetch();
        if ($page) {
            $page = self::applyTranslations($page, Lang::getLocale());
        }
        return $page;
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
        $pages = $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        $locale = Lang::getLocale();
        foreach ($pages as $p) {
            self::applyTranslations($p, $locale);
        }
        return $pages;
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
        
        $sql .= " ORDER BY created_at DESC LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;
        
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

    protected static function applyTranslations($page, $locale)
    {
        if (!$page || !$locale) {
            if ($page) {
                $page->page_title = $page->title ?? null;
            }
            return $page;
        }
        try {
            $instance = new static();
            $stmt = $instance->getConnection()->prepare(
                "SELECT title, meta_title, meta_description FROM page_translations WHERE page_id = ? AND locale = ? LIMIT 1"
            );
            $stmt->execute([$page->id, $locale]);
            $t = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($t) {
                if (!empty($t['title'])) {
                    $page->title = $t['title'];
                }
                if (!empty($t['meta_title'])) {
                    $page->meta_title = $t['meta_title'];
                }
                if (!empty($t['meta_description'])) {
                    $page->meta_description = $t['meta_description'];
                }
            }
        } catch (\Throwable $e) {
            // Ignore if translations table doesn't exist yet
        }
        $page->page_title = $page->title ?? null;
        return $page;
    }

    public static function upsertTranslation($pageId, $locale, $title = null, $metaTitle = null, $metaDescription = null)
    {
        $instance = new static();
        $sql = "INSERT INTO page_translations (page_id, locale, title, meta_title, meta_description)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    title = VALUES(title),
                    meta_title = VALUES(meta_title),
                    meta_description = VALUES(meta_description)";
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute([$pageId, $locale, $title, $metaTitle, $metaDescription]);
    }
}

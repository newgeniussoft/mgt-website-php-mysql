<?php

namespace App\Models;

class Template extends Model {
    protected $table = 'templates';
    protected $fillable = [
        'name', 'slug', 'description', 'html_content', 'css_content', 
        'js_content', 'thumbnail', 'is_default', 'status', 'created_by'
    ];
    
    /**
     * Get all active templates
     */
    public static function getActive() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE status = 'active' ORDER BY name ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get template by slug
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
     * Get default template
     */
    public static function getDefault() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE is_default = 1 LIMIT 1"
        );
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug($name, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
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
     * Get pages using this template
     */
    public function getPages() {
        $stmt = $this->getConnection()->prepare(
            "SELECT * FROM pages WHERE template_id = ? ORDER BY title ASC"
        );
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }
    
    /**
     * Count pages using this template
     */
    public function countPages() {
        $stmt = $this->getConnection()->prepare(
            "SELECT COUNT(*) as count FROM pages WHERE template_id = ?"
        );
        $stmt->execute([$this->id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Render template with variables
     */
    public function render($variables = []) {
        $html = $this->html_content;
        
        // Replace variables
        foreach ($variables as $key => $value) {
            $html = str_replace('{{ ' . $key . ' }}', $value, $html);
        }
        
        // Inject CSS
        if ($this->css_content) {
            $css = '<style>' . $this->css_content . '</style>';
            $html = str_replace('{{ custom_css }}', $css, $html);
        }
        
        // Inject JS
        if ($this->js_content) {
            $js = '<script>' . $this->js_content . '</script>';
            $html = str_replace('{{ custom_js }}', $js, $html);
        }
        
        return $html;
    }
}

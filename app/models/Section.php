<?php

namespace App\Models;

class Section extends Model {
    protected $table = 'sections';
    protected $fillable = [
        'page_id', 'name', 'slug', 'type', 'html_template', 
        'css_styles', 'js_scripts', 'settings', 'order_index', 'is_active'
    ];
    
    /**
     * Get sections by page
     */
    public static function getByPage($pageId, $activeOnly = true) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE page_id = ?";
        $params = [$pageId];
        
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        
        $sql .= " ORDER BY order_index ASC";
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get section by slug
     */
    public static function getBySlug($slug, $pageId = null) {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE slug = ?";
        $params = [$slug];
        
        if ($pageId) {
            $sql .= " AND page_id = ?";
            $params[] = $pageId;
        }
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug($name, $pageId, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $instance = new static();
        
        $sql = "SELECT COUNT(*) as count FROM {$instance->table} WHERE slug = ? AND page_id = ?";
        $params = [$slug, $pageId];
        
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
     * Get section contents
     */
    public function getContents() {
        $stmt = $this->getConnection()->prepare(
            "SELECT * FROM contents WHERE section_id = ? AND is_active = 1 ORDER BY order_index ASC"
        );
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Content::class);
    }
    
    /**
     * Get page
     */
    public function getPage() {
        return Page::find($this->page_id);
    }
    
    /**
     * Update order
     */
    public static function updateOrder($sectionId, $newOrder) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "UPDATE {$instance->table} SET order_index = ? WHERE id = ?"
        );
        return $stmt->execute([$newOrder, $sectionId]);
    }
    
    /**
     * Reorder sections
     */
    public static function reorder($pageId, $sectionIds) {
    if (is_string($sectionIds)) {
        $sectionIds = json_decode($sectionIds, true);
    }

        $instance = new static();
        $conn = $instance->getConnection();
        
        try {
            $conn->beginTransaction();
            
            foreach ($sectionIds as $index => $sectionId) {
                $stmt = $conn->prepare(
                    "UPDATE {$instance->table} SET order_index = ? WHERE id = ? AND page_id = ?"
                );
                $stmt->execute([$index, $sectionId, $pageId]);
            }
            
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
    
    /**
     * Render section with content
     */
    public function render($variables = []) {
        $html = $this->html_template ?: '';
        
        // Get contents
        $contents = $this->getContents();
        $contentHtml = '';
        
        foreach ($contents as $content) {
            $contentHtml .= $content->content;
        }
        
        // Replace content placeholder
        $html = str_replace('{{ content }}', $contentHtml, $html);
        
        // Replace other variables
        foreach ($variables as $key => $value) {
            $html = str_replace('{{ ' . $key . ' }}', $value, $html);
        }
        
        // Wrap with styles and scripts
        $output = '';
        
        if ($this->css_styles) {
            $output .= '<style>' . $this->css_styles . '</style>';
        }
        
        $output .= '<div class="section section-' . $this->slug . '" data-section-id="' . $this->id . '">';
        $output .= $html;
        $output .= '</div>';
        
        if ($this->js_scripts) {
            $output .= '<script>' . $this->js_scripts . '</script>';
        }
        
        return $output;
    }
}

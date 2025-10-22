<?php

require_once __DIR__ . '/../../config/database.php';

class SectionLayoutTemplate {
    private $db;
    private $table = 'section_layout_templates';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
    }
    
    /**
     * Get all active section layout templates
     */
    public function getAll($category = null, $includeInactive = false) {
        $whereClause = [];
        $params = [];
        
        if (!$includeInactive) {
            $whereClause[] = 'is_active = 1';
        }
        
        if ($category) {
            $whereClause[] = 'category = :category';
            $params['category'] = $category;
        }
        
        $where = !empty($whereClause) ? 'WHERE ' . implode(' AND ', $whereClause) : '';
        $query = "SELECT * FROM {$this->table} {$where} ORDER BY is_system DESC, category ASC, name ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get template by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get template by slug
     */
    public function getBySlug($slug) {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }
    
    /**
     * Get templates by category
     */
    public function getByCategory($category) {
        $query = "SELECT * FROM {$this->table} WHERE category = :category AND is_active = 1 ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get available categories
     */
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM {$this->table} WHERE is_active = 1 ORDER BY category ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return array_column($stmt->fetchAll(), 'category');
    }
    
    /**
     * Create new section layout template
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} (
            name, slug, description, category, html_template, css_template, 
            js_template, thumbnail, variables, is_system, is_active
        ) VALUES (
            :name, :slug, :description, :category, :html_template, :css_template,
            :js_template, :thumbnail, :variables, :is_system, :is_active
        )";
        
        $stmt = $this->db->prepare($query);
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Encode variables as JSON
        if (isset($data['variables']) && is_array($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }
        
        $result = $stmt->execute([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? '',
            'category' => $data['category'] ?? 'general',
            'html_template' => $data['html_template'],
            'css_template' => $data['css_template'] ?? '',
            'js_template' => $data['js_template'] ?? '',
            'thumbnail' => $data['thumbnail'] ?? null,
            'variables' => $data['variables'] ?? '{}',
            'is_system' => $data['is_system'] ?? 0,
            'is_active' => $data['is_active'] ?? 1
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Update section layout template
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
            name = :name,
            slug = :slug,
            description = :description,
            category = :category,
            html_template = :html_template,
            css_template = :css_template,
            js_template = :js_template,
            thumbnail = :thumbnail,
            variables = :variables,
            is_active = :is_active,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }
        
        // Encode variables as JSON
        if (isset($data['variables']) && is_array($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? '',
            'category' => $data['category'] ?? 'general',
            'html_template' => $data['html_template'],
            'css_template' => $data['css_template'] ?? '',
            'js_template' => $data['js_template'] ?? '',
            'thumbnail' => $data['thumbnail'] ?? null,
            'variables' => $data['variables'] ?? '{}',
            'is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    /**
     * Delete section layout template
     */
    public function delete($id) {
        // Don't allow deletion of system templates
        $template = $this->getById($id);
        if ($template && $template['is_system']) {
            return false;
        }
        
        $query = "DELETE FROM {$this->table} WHERE id = :id AND is_system = 0";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Render section with template
     */
    public function renderSection($templateId, $data = []) {
        $template = $this->getById($templateId);
        if (!$template) {
            return '';
        }
        
        // Parse template variables
        $variables = json_decode($template['variables'], true) ?? [];
        
        // Replace template variables in HTML
        $html = $this->replaceTemplateVariables($template['html_template'], $data, $variables);
        $css = $this->replaceTemplateVariables($template['css_template'], $data, $variables);
        $js = $this->replaceTemplateVariables($template['js_template'], $data, $variables);
        
        return [
            'html' => $html,
            'css' => $css,
            'js' => $js,
            'template' => $template
        ];
    }
    
    /**
     * Replace template variables
     */
    private function replaceTemplateVariables($content, $data, $defaultVariables = []) {
        if (empty($content)) {
            return $content;
        }
        
        // Replace {{ variable|default }} patterns
        $content = preg_replace_callback('/\{\{\s*(\w+)\|([^}]+)\s*\}\}/', function($matches) use ($data) {
            $variable = $matches[1];
            $default = $matches[2];
            return $data[$variable] ?? $default;
        }, $content);
        
        // Replace {{ variable }} patterns
        $content = preg_replace_callback('/\{\{\s*(\w+)\s*\}\}/', function($matches) use ($data) {
            $variable = $matches[1];
            return $data[$variable] ?? '';
        }, $content);
        
        // Handle @if conditions
        $content = preg_replace_callback('/@if\((\w+)\)(.*?)@endif/s', function($matches) use ($data) {
            $variable = $matches[1];
            $content = $matches[2];
            return !empty($data[$variable]) ? $content : '';
        }, $content);
        
        // Handle @foreach loops (basic implementation)
        $content = preg_replace_callback('/@foreach\((\w+)\s+as\s+(\w+)\)(.*?)@endforeach/s', function($matches) use ($data) {
            $arrayVar = $matches[1];
            $itemVar = $matches[2];
            $loopContent = $matches[3];
            
            if (!isset($data[$arrayVar]) || !is_array($data[$arrayVar])) {
                return '';
            }
            
            $result = '';
            foreach ($data[$arrayVar] as $item) {
                $itemContent = $loopContent;
                if (is_array($item)) {
                    foreach ($item as $key => $value) {
                        $itemContent = str_replace("{{ {$itemVar}.{$key} }}", $value, $itemContent);
                        $itemContent = str_replace("{{ {$itemVar}.{$key}|", "{{ {$value}|", $itemContent);
                    }
                }
                $result .= $itemContent;
            }
            
            return $result;
        }, $content);
        
        return $content;
    }
    
    /**
     * Generate unique slug
     */
    private function generateSlug($name, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    private function slugExists($slug, $excludeId = null) {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];
        
        if ($excludeId) {
            $query .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Get template usage statistics
     */
    public function getUsageStats($templateId) {
        $query = "SELECT COUNT(*) as usage_count FROM page_sections WHERE layout_template = (
            SELECT slug FROM {$this->table} WHERE id = :template_id
        )";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['template_id' => $templateId]);
        $result = $stmt->fetch();
        return $result['usage_count'] ?? 0;
    }
}

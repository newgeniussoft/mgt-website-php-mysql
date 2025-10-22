<?php

require_once __DIR__ . '/../../config/database.php';

class PageTemplate {
    private $db;
    private $table = 'page_templates';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
    }
    
    /**
     * Get all active templates
     */
    public function getAll($includeInactive = false) {
        $whereClause = $includeInactive ? '' : 'WHERE is_active = 1';
        $query = "SELECT * FROM {$this->table} {$whereClause} ORDER BY is_system DESC, name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get template by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get template by slug
     */
    public function getBySlug($slug) {
        $query = "SELECT * FROM {$this->table} WHERE slug = :slug AND is_active = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get templates by type
     */
    public function getByType($type) {
        $query = "SELECT * FROM {$this->table} WHERE template_type = :type AND is_active = 1 ORDER BY name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new template
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (name, slug, description, template_type, html_template, css_styles, js_scripts, variables, thumbnail, is_active, created_by) 
                  VALUES (:name, :slug, :description, :template_type, :html_template, :css_styles, :js_scripts, :variables, :thumbnail, :is_active, :created_by)";
        
        $stmt = $this->db->prepare($query);
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Convert variables array to JSON if needed
        if (isset($data['variables']) && is_array($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':template_type', $data['template_type']);
        $stmt->bindParam(':html_template', $data['html_template']);
        $stmt->bindParam(':css_styles', $data['css_styles']);
        $stmt->bindParam(':js_scripts', $data['js_scripts']);
        $stmt->bindParam(':variables', $data['variables']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':is_active', $data['is_active']);
        $stmt->bindParam(':created_by', $data['created_by']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update template
     */
    public function update($id, $data) {
        // Don't allow updating system templates
        $template = $this->getById($id);
        if ($template && $template['is_system']) {
            return false;
        }
        
        $query = "UPDATE {$this->table} 
                  SET name = :name, slug = :slug, description = :description, 
                      template_type = :template_type, html_template = :html_template, 
                      css_styles = :css_styles, js_scripts = :js_scripts, 
                      variables = :variables, thumbnail = :thumbnail, is_active = :is_active 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Convert variables array to JSON if needed
        if (isset($data['variables']) && is_array($data['variables'])) {
            $data['variables'] = json_encode($data['variables']);
        }
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':template_type', $data['template_type']);
        $stmt->bindParam(':html_template', $data['html_template']);
        $stmt->bindParam(':css_styles', $data['css_styles']);
        $stmt->bindParam(':js_scripts', $data['js_scripts']);
        $stmt->bindParam(':variables', $data['variables']);
        $stmt->bindParam(':thumbnail', $data['thumbnail']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        return $stmt->execute();
    }
    
    /**
     * Delete template
     */
    public function delete($id) {
        // Don't allow deleting system templates
        $template = $this->getById($id);
        if ($template && $template['is_system']) {
            return false;
        }
        
        // Check if template is being used by any pages
        $query = "SELECT COUNT(*) as count FROM pages WHERE template_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return false; // Template is in use
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
     * Duplicate template
     */
    public function duplicate($id, $newName = null) {
        $template = $this->getById($id);
        if (!$template) {
            return false;
        }
        
        $newName = $newName ?: $template['name'] . ' (Copy)';
        
        $data = [
            'name' => $newName,
            'slug' => $this->generateSlug($newName),
            'description' => $template['description'],
            'template_type' => $template['template_type'],
            'html_template' => $template['html_template'],
            'css_styles' => $template['css_styles'],
            'js_scripts' => $template['js_scripts'],
            'variables' => $template['variables'],
            'thumbnail' => $template['thumbnail'],
            'is_active' => 1,
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        return $this->create($data);
    }
    
    /**
     * Get template usage statistics
     */
    public function getUsageStats($id) {
        $query = "SELECT COUNT(*) as page_count FROM pages WHERE template_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Render template with data
     */
    public function render($templateId, $data = []) {
        $template = $this->getById($templateId);
        if (!$template) {
            return false;
        }
        
        $htmlTemplate = $template['html_template'];
        
        // Process variables with default values support
        $htmlTemplate = preg_replace_callback('/\{\{\s*([^}|]+)(\|([^}]*))?\s*\}\}/', function($matches) use ($data) {
            $variable = trim($matches[1]);
            $defaultValue = isset($matches[3]) ? trim($matches[3]) : '';
            
            if (isset($data[$variable]) && $data[$variable] !== '') {
                return $data[$variable];
            }
            
            return $defaultValue;
        }, $htmlTemplate);
        
        // Handle conditional blocks
        $htmlTemplate = $this->processConditionals($htmlTemplate, $data);
        
        return [
            'html' => $htmlTemplate,
            'css' => $template['css_styles'],
            'js' => $template['js_scripts']
        ];
    }
    
    /**
     * Process conditional template blocks
     */
    private function processConditionals($template, $data) {
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
     * Get template variables from template content
     */
    public function extractVariables($htmlTemplate, $cssStyles = '', $jsScripts = '') {
        $variables = [];
        $content = $htmlTemplate . ' ' . $cssStyles . ' ' . $jsScripts;
        
        // Find all {{ variable }} patterns
        preg_match_all('/\{\{\s*([^}|]+)(\|([^}]*))?\s*\}\}/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $variable) {
                $variable = trim($variable);
                $defaultValue = isset($matches[3][$index]) ? trim($matches[3][$index]) : '';
                
                if (!in_array($variable, ['custom_css', 'custom_js'])) {
                    $variables[$variable] = $defaultValue;
                }
            }
        }
        
        // Find conditional variables
        preg_match_all('/@if\(([^)]+)\)/', $content, $conditionalMatches);
        if (!empty($conditionalMatches[1])) {
            foreach ($conditionalMatches[1] as $variable) {
                $variable = trim($variable);
                if (!isset($variables[$variable])) {
                    $variables[$variable] = '';
                }
            }
        }
        
        return $variables;
    }
}

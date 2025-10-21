<?php

require_once __DIR__ . '/../../config/database.php';

class PageSection {
    private $db;
    private $table = 'page_sections';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
    }
    
    /**
     * Get all sections for a page
     */
    public function getByPageId($pageId, $activeOnly = true) {
        $whereClause = $activeOnly ? 'AND is_active = 1' : '';
        $query = "SELECT * FROM {$this->table} 
                  WHERE page_id = :page_id {$whereClause} 
                  ORDER BY sort_order ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':page_id', $pageId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get section by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new section
     */
    public function create($data) {
        // Get next sort order
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $this->getNextSortOrder($data['page_id']);
        }
        
        $query = "INSERT INTO {$this->table} 
                  (page_id, section_type, title, content, settings, sort_order, is_active) 
                  VALUES (:page_id, :section_type, :title, :content, :settings, :sort_order, :is_active)";
        
        $stmt = $this->db->prepare($query);
        
        $settings = isset($data['settings']) ? json_encode($data['settings']) : '{}';
        $isActive = isset($data['is_active']) ? $data['is_active'] : 1;
        
        $stmt->bindParam(':page_id', $data['page_id']);
        $stmt->bindParam(':section_type', $data['section_type']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':settings', $settings);
        $stmt->bindParam(':sort_order', $data['sort_order']);
        $stmt->bindParam(':is_active', $isActive);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update section
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET section_type = :section_type, title = :title, content = :content, 
                      settings = :settings, sort_order = :sort_order, is_active = :is_active 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $settings = isset($data['settings']) ? json_encode($data['settings']) : '{}';
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':section_type', $data['section_type']);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':settings', $settings);
        $stmt->bindParam(':sort_order', $data['sort_order']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        return $stmt->execute();
    }
    
    /**
     * Delete section
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Update sort order for multiple sections
     */
    public function updateSortOrder($sections) {
        $this->db->beginTransaction();
        
        try {
            $query = "UPDATE {$this->table} SET sort_order = :sort_order WHERE id = :id";
            $stmt = $this->db->prepare($query);
            
            foreach ($sections as $section) {
                $stmt->bindParam(':id', $section['id']);
                $stmt->bindParam(':sort_order', $section['sort_order']);
                $stmt->execute();
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Get next sort order for a page
     */
    private function getNextSortOrder($pageId) {
        $query = "SELECT MAX(sort_order) as max_order FROM {$this->table} WHERE page_id = :page_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':page_id', $pageId);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['max_order'] ?? 0) + 1;
    }
    
    /**
     * Duplicate section
     */
    public function duplicate($id, $newPageId = null) {
        $section = $this->getById($id);
        if (!$section) {
            return false;
        }
        
        $pageId = $newPageId ?: $section['page_id'];
        
        $data = [
            'page_id' => $pageId,
            'section_type' => $section['section_type'],
            'title' => $section['title'] . ' (Copy)',
            'content' => $section['content'],
            'settings' => json_decode($section['settings'], true),
            'is_active' => $section['is_active']
        ];
        
        return $this->create($data);
    }
    
    /**
     * Render section with template
     */
    public function render($sectionId, $templateEngine = null) {
        $section = $this->getById($sectionId);
        if (!$section) {
            return '';
        }
        
        $settings = json_decode($section['settings'], true) ?: [];
        
        // Get section template
        $template = $this->getSectionTemplate($section['section_type']);
        
        if (!$template) {
            // Fallback to basic rendering
            return $this->renderBasicSection($section);
        }
        
        // Prepare template data
        $data = [
            'title' => $section['title'],
            'content' => $section['content']
        ];
        
        // Merge settings into data
        $data = array_merge($data, $settings);
        
        // Render template
        return $this->processTemplate($template['template_html'], $data, $template['template_css']);
    }
    
    /**
     * Get section template
     */
    private function getSectionTemplate($sectionType) {
        $query = "SELECT * FROM section_templates WHERE section_type = :section_type AND is_active = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':section_type', $sectionType);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Process template with data
     */
    private function processTemplate($template, $data, $css = '') {
        // Replace variables in template
        foreach ($data as $key => $value) {
            $template = str_replace('{{ ' . $key . ' }}', $value, $template);
        }
        
        // Process CSS variables
        if ($css) {
            foreach ($data as $key => $value) {
                $css = str_replace('{{ ' . $key . ' }}', $value, $css);
            }
        }
        
        // Handle conditional blocks
        $template = $this->processConditionals($template, $data);
        
        return [
            'html' => $template,
            'css' => $css
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
     * Render basic section without template
     */
    private function renderBasicSection($section) {
        $html = '<div class="page-section section-' . $section['section_type'] . '">';
        
        if (!empty($section['title'])) {
            $html .= '<h3 class="section-title">' . htmlspecialchars($section['title']) . '</h3>';
        }
        
        if (!empty($section['content'])) {
            $html .= '<div class="section-content">' . $section['content'] . '</div>';
        }
        
        $html .= '</div>';
        
        return [
            'html' => $html,
            'css' => '.page-section { margin-bottom: 2rem; } .section-title { color: #198754; margin-bottom: 1rem; }'
        ];
    }
    
    /**
     * Get available section types
     */
    public function getSectionTypes() {
        return [
            'text' => 'Text Block',
            'image' => 'Image',
            'gallery' => 'Image Gallery',
            'video' => 'Video',
            'cta' => 'Call to Action',
            'testimonial' => 'Testimonial',
            'features' => 'Features Grid',
            'contact' => 'Contact Form',
            'html' => 'Custom HTML',
            'custom' => 'Custom Section'
        ];
    }
}

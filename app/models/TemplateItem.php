<?php

namespace App\Models;

class TemplateItem extends Model {
    protected $table = 'template_items';
    protected $fillable = [
        'name', 
        'slug',
        'description',
        'model_name',
        'html_template', 
        'css_styles',
        'js_code',
        'variables', 
        'default_keys',
        'thumbnail',
        'is_default',
        'status'
    ];
    protected $timestamps = true;

    /**
     * Get all active template items
     */
    public static function getActive() {
        $instance = new static();
        $pdo = $instance->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `{$instance->table}` WHERE `status` = ? ORDER BY `name` ASC");
        $stmt->execute(['active']);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    /**
     * Get template item by slug
     */
    public static function getBySlug($slug) {
        $instance = new static();
        $pdo = $instance->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `{$instance->table}` WHERE `slug` = ? AND `status` = 'active' LIMIT 1");
        $stmt->execute([$slug]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }

    /**
     * Get template item by model name
     */
    public static function getByModel($modelName) {
        $instance = new static();
        $pdo = $instance->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `{$instance->table}` WHERE `model_name` = ? AND `status` = 'active'");
        $stmt->execute([$modelName]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    /**
     * Get default template for a model
     */
    public static function getDefaultForModel($modelName) {
        $instance = new static();
        $pdo = $instance->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `{$instance->table}` WHERE `model_name` = ? AND `is_default` = 1 AND `status` = 'active' LIMIT 1");
        $stmt->execute([$modelName]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }

    /**
     * Generate unique slug from name
     */
    public static function generateSlug($name, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $instance = new static();
            $pdo = $instance->getConnection();
            if ($id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$instance->table}` WHERE `slug` = ? AND `id` != ?");
                $stmt->execute([$slug, $id]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$instance->table}` WHERE `slug` = ?");
                $stmt->execute([$slug]);
            }
            $exists = (int)$stmt->fetchColumn() > 0;
            if (!$exists) break;
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get variables as array
     */
    public function getVariablesArray() {
        if (empty($this->variables)) {
            return [];
        }
        
        $variables = json_decode($this->variables, true);
        return is_array($variables) ? $variables : [];
    }

    /**
     * Set variables from array
     */
    public function setVariablesArray($variablesArray) {
        $this->variables = json_encode($variablesArray);
    }

    /**
     * Add a variable
     */
    public function addVariable($key, $label, $type = 'text', $defaultValue = '') {
        $variables = $this->getVariablesArray();
        
        $variables[] = [
            'key' => $key,
            'label' => $label,
            'type' => $type,
            'default' => $defaultValue
        ];
        
        $this->setVariablesArray($variables);
    }

    /**
     * Remove a variable by key
     */
    public function removeVariable($key) {
        $variables = $this->getVariablesArray();
        $variables = array_filter($variables, function($var) use ($key) {
            return $var['key'] !== $key;
        });
        
        $this->setVariablesArray(array_values($variables));
    }

    /**
     * Get default keys as array
     */
    public function getDefaultKeysArray() {
        if (empty($this->default_keys)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->default_keys));
    }

    /**
     * Extract variables from HTML template
     */
    public function extractVariablesFromTemplate() {
        if (empty($this->html_template)) {
            return [];
        }

        $pattern = '/\{\{\s*\$item\.([a-zA-Z0-9_]+)\s*\}\}/i';
        preg_match_all($pattern, $this->html_template, $matches);
        
        $variables = [];
        if (!empty($matches[1])) {
            foreach (array_unique($matches[1]) as $var) {
                $variables[] = [
                    'key' => $var,
                    'label' => ucwords(str_replace('_', ' ', $var)),
                    'type' => 'text',
                    'default' => ''
                ];
            }
        }
        
        return $variables;
    }

    /**
     * Render template with item data
     */
    public function render($item, $options = []) {
        $html = $this->html_template;
        
        // Get variables
        $variables = $this->getVariablesArray();
        
        // Replace {{ $item.key }} with actual values
        foreach ($variables as $var) {
            $key = $var['key'];
            $value = '';
            
            // Get value from item
            if (is_object($item) && isset($item->$key)) {
                $value = $item->$key;
            } elseif (is_array($item) && isset($item[$key])) {
                $value = $item[$key];
            } else {
                $value = $var['default'] ?? '';
            }
            
            // Replace in template
            $html = str_replace('{{ $item.' . $key . ' }}', htmlspecialchars($value), $html);
            $html = str_replace('{{$item.' . $key . '}}', htmlspecialchars($value), $html);
        }
        
        // Add CSS if available
        $css = '';
        if (!empty($this->css_styles)) {
            $css = '<style>' . $this->css_styles . '</style>';
        }
        
        // Add JS if available
        $js = '';
        if (!empty($this->js_code)) {
            $js = '<script>' . $this->js_code . '</script>';
        }
        
        return $css . $html . $js;
    }

    /**
     * Get usage statistics
     */
    public function getUsageCount() {
        // This would track how many times this template is used
        // For now, return 0 - can be enhanced later
        return 0;
    }

    /**
     * Duplicate this template
     */
    public function duplicate() {
        $newTemplate = new self();
        $newTemplate->name = $this->name . ' (Copy)';
        $newTemplate->slug = self::generateSlug($this->name . ' Copy');
        $newTemplate->description = $this->description;
        $newTemplate->model_name = $this->model_name;
        $newTemplate->html_template = $this->html_template;
        $newTemplate->css_styles = $this->css_styles;
        $newTemplate->js_code = $this->js_code;
        $newTemplate->variables = $this->variables;
        $newTemplate->default_keys = $this->default_keys;
        $newTemplate->is_default = 0;
        $newTemplate->status = 'draft';
        $newTemplate->save();
        
        return $newTemplate;
    }
}
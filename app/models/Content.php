<?php

namespace App\Models;

class Content extends Model {
    protected $table = 'contents';
    protected $fillable = [
        'section_id', 'title', 'content', 'content_type', 
        'language', 'order_index', 'is_active'
    ];
    
    /**
     * Get contents by section
     */
    public static function getBySection($sectionId, $activeOnly = true, $language = 'en') {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE section_id = ? AND language = ?";
        $params = [$sectionId, $language];
        
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        
        $sql .= " ORDER BY order_index ASC";
        
        $stmt = $instance->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get section
     */
    public function getSection() {
        return Section::find($this->section_id);
    }
    
    /**
     * Update order
     */
    public static function updateOrder($contentId, $newOrder) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "UPDATE {$instance->table} SET order_index = ? WHERE id = ?"
        );
        return $stmt->execute([$newOrder, $contentId]);
    }
    
    /**
     * Reorder contents
     */
    public static function reorder($sectionId, $contentIds) {
        $instance = new static();
        $conn = $instance->getConnection();
        
        try {
            $conn->beginTransaction();
            
            foreach ($contentIds as $index => $contentId) {
                $stmt = $conn->prepare(
                    "UPDATE {$instance->table} SET order_index = ? WHERE id = ? AND section_id = ?"
                );
                $stmt->execute([$index, $contentId, $sectionId]);
            }
            
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
    
    /**
     * Get available languages
     */
    public static function getLanguages() {
        return [
            'en' => 'English',
            'fr' => 'Français',
            'es' => 'Español',
            'de' => 'Deutsch'
        ];
    }
}

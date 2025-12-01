<?php

namespace App\Models;

class MediaFolder extends Model {
    protected $table = 'media_folders';
    protected $fillable = ['name', 'slug', 'parent_id', 'path', 'description', 'order'];
    
    /**
     * Get all root folders
     */
    public static function getRootFolders() {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} WHERE `parent_id` IS NULL ORDER BY `order`, `name`"
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get child folders
     */
    public function getChildren() {
        return static::where('parent_id', '=', $this->id);
    }
    
    /**
     * Get parent folder
     */
    public function getParent() {
        if ($this->parent_id) {
            return static::find($this->parent_id);
        }
        return null;
    }
    
    /**
     * Get all media in this folder
     */
    public function getMedia() {
        return Media::getByFolder($this->id);
    }
    
    /**
     * Get media count
     */
    public function getMediaCount() {
        $stmt = $this->getConnection()->prepare(
            "SELECT COUNT(*) as count FROM media WHERE `folder_id` = ?"
        );
        $stmt->execute([$this->id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
    
    /**
     * Generate unique slug
     */
    public static function generateSlug($name, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $originalSlug = $slug;
        $counter = 1;
        
        $instance = new static();
        while (true) {
            $stmt = $instance->getConnection()->prepare(
                "SELECT COUNT(*) as count FROM {$instance->table} WHERE `slug` = ?" . ($id ? " AND `id` != ?" : "")
            );
            $params = $id ? [$slug, $id] : [$slug];
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($result['count'] == 0) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get breadcrumb path
     */
    public function getBreadcrumb() {
        $breadcrumb = [$this];
        $parent = $this->getParent();
        
        while ($parent) {
            array_unshift($breadcrumb, $parent);
            $parent = $parent->getParent();
        }
        
        return $breadcrumb;
    }
}

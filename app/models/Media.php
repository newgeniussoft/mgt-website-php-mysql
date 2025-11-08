<?php

namespace App\Models;

class Media extends Model {
    protected $table = 'media';
    protected $fillable = [
        'filename', 'original_filename', 'path', 'url', 'mime_type', 
        'extension', 'size', 'width', 'height', 'title', 'alt_text', 
        'description', 'folder_id', 'user_id', 'type', 'is_public', 'downloads'
    ];
    
    /**
     * Get media by type
     */
    public static function getByType($type) {
        return static::where('type', '=', $type);
    }
    
    /**
     * Get media by folder
     */
    public static function getByFolder($folderId) {
        if ($folderId === null) {
            $instance = new static();
            $stmt = $instance->getConnection()->prepare("SELECT * FROM {$instance->table} WHERE `folder_id` IS NULL ORDER BY `created_at` DESC");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        }
        return static::where('folder_id', '=', $folderId);
    }
    
    /**
     * Search media
     */
    public static function search($query) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} 
             WHERE `filename` LIKE ? 
             OR `original_filename` LIKE ? 
             OR `title` LIKE ? 
             OR `alt_text` LIKE ? 
             ORDER BY `created_at` DESC"
        );
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get recent media
     */
    public static function getRecent($limit = 10) {
        $instance = new static();
        $stmt = $instance->getConnection()->prepare(
            "SELECT * FROM {$instance->table} ORDER BY `created_at` DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Get media statistics
     */
    public static function getStats() {
        $instance = new static();
        $stmt = $instance->getConnection()->query(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN `type` = 'image' THEN 1 ELSE 0 END) as images,
                SUM(CASE WHEN `type` = 'video' THEN 1 ELSE 0 END) as videos,
                SUM(CASE WHEN `type` = 'audio' THEN 1 ELSE 0 END) as audio,
                SUM(CASE WHEN `type` = 'document' THEN 1 ELSE 0 END) as documents,
                SUM(`size`) as total_size
             FROM {$instance->table}"
        );
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Increment download count
     */
    public function incrementDownloads() {
        $this->downloads = ($this->downloads ?? 0) + 1;
        return $this->save();
    }
    
    /**
     * Get formatted file size
     */
    public function getFormattedSize() {
        $bytes = $this->size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Check if media is an image
     */
    public function isImage() {
        return $this->type === 'image';
    }
    
    /**
     * Check if media is a video
     */
    public function isVideo() {
        return $this->type === 'video';
    }
    
    /**
     * Check if media is audio
     */
    public function isAudio() {
        return $this->type === 'audio';
    }
    
    /**
     * Check if media is a document
     */
    public function isDocument() {
        return $this->type === 'document';
    }
    
    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrl() {
        if ($this->isImage()) {
            return $this->url;
        }
        
        // Return icon based on type
        $icons = [
            'video' => '/assets/icons/video.svg',
            'audio' => '/assets/icons/audio.svg',
            'document' => '/assets/icons/document.svg',
            'other' => '/assets/icons/file.svg'
        ];
        
        return $icons[$this->type] ?? $icons['other'];
    }
    
    /**
     * Delete media file from disk
     */
    public function deleteFile() {
        $filePath = __DIR__ . '/../../public' . $this->path;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}

<?php

require_once __DIR__ . '/../../config/database.php';

class Media {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConn();
    }
    
    /**
     * Get all media files with optional filtering
     */
    public function getAll($filters = []) {
        $sql = "SELECT m.*, mf.name as folder_name, u.username as uploaded_by_name 
                FROM media m 
                LEFT JOIN media_folders mf ON m.folder_id = mf.id 
                LEFT JOIN users u ON m.uploaded_by = u.id 
                WHERE 1=1";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['type'])) {
            $sql .= " AND m.file_type = ?";
            $params[] = $filters['type'];
        }
        
        if (!empty($filters['folder_id'])) {
            $sql .= " AND m.folder_id = ?";
            $params[] = $filters['folder_id'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (m.original_name LIKE ? OR m.title LIKE ? OR m.alt_text LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (isset($filters['is_public'])) {
            $sql .= " AND m.is_public = ?";
            $params[] = $filters['is_public'];
        }
        
        // Add ordering
        $sql .= " ORDER BY m.created_at DESC";
        
        // Add pagination if specified
        if (!empty($filters['limit'])) {
            $limit = (int)$filters['limit'];
            $sql .= " LIMIT $limit";
            
            if (!empty($filters['offset'])) {
                $offset = (int)$filters['offset'];
                $sql .= " OFFSET $offset";
            }
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get media by ID
     */
    public function getById($id) {
        $sql = "SELECT m.*, mf.name as folder_name, u.username as uploaded_by_name 
                FROM media m 
                LEFT JOIN media_folders mf ON m.folder_id = mf.id 
                LEFT JOIN users u ON m.uploaded_by = u.id 
                WHERE m.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new media entry
     */
    public function create($data) {
        $sql = "INSERT INTO media (filename, original_name, file_path, file_size, mime_type, file_type, 
                alt_text, title, description, width, height, thumbnail_path, uploaded_by, folder_id, is_public) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['filename'],
            $data['original_name'],
            $data['file_path'],
            $data['file_size'],
            $data['mime_type'],
            $data['file_type'],
            $data['alt_text'] ?? null,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['width'] ?? null,
            $data['height'] ?? null,
            $data['thumbnail_path'] ?? null,
            $data['uploaded_by'],
            $data['folder_id'] ?? null,
            $data['is_public'] ?? 1
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Update media entry
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        $allowedFields = ['alt_text', 'title', 'description', 'folder_id', 'is_public'];
        
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE media SET " . implode(', ', $fields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Delete media entry and file
     */
    public function delete($id) {
        // Get media info first
        $media = $this->getById($id);
        if (!$media) {
            return false;
        }
        
        // Delete from database
        $sql = "DELETE FROM media WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$id]);
        
        if ($result) {
            // Delete physical files
            $this->deletePhysicalFile($media['file_path']);
            if ($media['thumbnail_path']) {
                $this->deletePhysicalFile($media['thumbnail_path']);
            }
        }
        
        return $result;
    }
    
    /**
     * Get media statistics
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total_files,
                    SUM(file_size) as total_size,
                    COUNT(CASE WHEN file_type = 'image' THEN 1 END) as images,
                    COUNT(CASE WHEN file_type = 'video' THEN 1 END) as videos,
                    COUNT(CASE WHEN file_type = 'audio' THEN 1 END) as audio,
                    COUNT(CASE WHEN file_type = 'document' THEN 1 END) as documents,
                    COUNT(CASE WHEN file_type = 'other' THEN 1 END) as other
                FROM media";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get media by type
     */
    public function getByType($type, $limit = null) {
        $sql = "SELECT * FROM media WHERE file_type = ? ORDER BY created_at DESC";
        
        if ($limit) {
            $limit = (int)$limit;
            $sql .= " LIMIT $limit";
        }
        
        $stmt = $this->db->prepare($sql);
        $params = [$type];
        
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Increment download count
     */
    public function incrementDownloadCount($id) {
        $sql = "UPDATE media SET download_count = download_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Generate unique filename
     */
    public function generateUniqueFilename($originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $basename = preg_replace('/[^a-zA-Z0-9\-_]/', '', $basename);
        
        return $basename . '_' . time() . '_' . uniqid() . '.' . $extension;
    }
    
    /**
     * Determine file type from mime type
     */
    public function getFileTypeFromMime($mimeType) {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'video';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'audio';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv'
        ])) {
            return 'document';
        } else {
            return 'other';
        }
    }
    
    /**
     * Get allowed file types and extensions
     */
    public function getAllowedFileTypes() {
        return [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv']
        ];
    }
    
    /**
     * Validate file upload
     */
    public function validateFile($file, $maxSize = 10485760) { // 10MB default
        $errors = [];
        
        // Check if file was uploaded
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload error: ' . $this->getUploadErrorMessage($file['error']);
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size (' . $this->formatFileSize($maxSize) . ')';
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedTypes = $this->getAllowedFileTypes();
        $allowedExtensions = array_merge(...array_values($allowedTypes));
        
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedExtensions);
        }
        
        // Check mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp',
            'video/mp4', 'video/avi', 'video/quicktime', 'video/x-msvideo', 'video/x-flv', 'video/webm',
            'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/aac', 'audio/flac',
            'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain', 'text/csv'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            $errors[] = 'Invalid file type detected';
        }
        
        return $errors;
    }
    
    /**
     * Format file size for display
     */
    public function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Get upload error message
     */
    private function getUploadErrorMessage($error) {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
                return 'File exceeds upload_max_filesize directive';
            case UPLOAD_ERR_FORM_SIZE:
                return 'File exceeds MAX_FILE_SIZE directive';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Delete physical file
     */
    private function deletePhysicalFile($filePath) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $filePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    
    /**
     * Create thumbnail for image
     */
    public function createThumbnail($sourcePath, $thumbnailPath, $width = 300, $height = 300) {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Calculate thumbnail dimensions maintaining aspect ratio
        $ratio = min($width / $sourceWidth, $height / $sourceHeight);
        $thumbWidth = round($sourceWidth * $ratio);
        $thumbHeight = round($sourceHeight * $ratio);
        
        // Create source image
        switch ($mimeType) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }
        
        // Create thumbnail
        $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $thumbWidth, $thumbHeight, $transparent);
        }
        
        // Resize image
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight);
        
        // Save thumbnail
        $result = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $result = imagejpeg($thumbnail, $thumbnailPath, 85);
                break;
            case 'image/png':
                $result = imagepng($thumbnail, $thumbnailPath, 8);
                break;
            case 'image/gif':
                $result = imagegif($thumbnail, $thumbnailPath);
                break;
        }
        
        // Clean up
        imagedestroy($source);
        imagedestroy($thumbnail);
        
        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\View\View;

class FileManagerController extends Controller {
    
    private $baseUploadPath;
    private $baseUploadDir;
    
    public function __construct() {
        $this->baseUploadPath = '/uploads';
        $this->baseUploadDir = __DIR__ . '/../../../public/uploads';
        
        // Create base uploads directory if it doesn't exist
        if (!is_dir($this->baseUploadDir)) {
            mkdir($this->baseUploadDir, 0755, true);
        }
    }
    
    /**
     * Display file manager
     */
    public function index() {
        $currentPath = $_GET['path'] ?? '';
        $currentPath = $this->sanitizePath($currentPath);
        
        $fullPath = $this->baseUploadDir . '/' . $currentPath;
        
        // Security check
        if (!$this->isPathSafe($fullPath)) {
            $_SESSION['media_error'] = 'Invalid path';
            $currentPath = '';
            $fullPath = $this->baseUploadDir;
        }
        
        // Get directory contents
        $items = $this->getDirectoryContents($fullPath, $currentPath);
        
        // Get breadcrumb
        $breadcrumb = $this->getBreadcrumb($currentPath);
        
        // Get statistics
        $stats = $this->getDirectoryStats($fullPath);
        
        return View::make('admin.filemanager.index', [
            'title' => 'File Manager',
            'items' => $items,
            'currentPath' => $currentPath,
            'breadcrumb' => $breadcrumb,
            'stats' => $stats,
            'success' => $_SESSION['media_success'] ?? null,
            'error' => $_SESSION['media_error'] ?? null
        ]);
    }
    
    /**
     * Get directory contents
     */
    private function getDirectoryContents($fullPath, $relativePath) {
        if (!is_dir($fullPath)) {
            return [];
        }
        
        $items = [];
        $files = scandir($fullPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $itemPath = $fullPath . '/' . $file;
            $itemRelativePath = $relativePath ? $relativePath . '/' . $file : $file;
            
            $isDir = is_dir($itemPath);
            
            $item = [
                'name' => $file,
                'path' => $itemRelativePath,
                'is_dir' => $isDir,
                'size' => $isDir ? $this->getDirectorySize($itemPath) : filesize($itemPath),
                'modified' => filemtime($itemPath),
                'extension' => $isDir ? null : strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                'type' => $isDir ? 'folder' : $this->getFileType($file),
                'url' => $isDir ? null : $this->baseUploadPath . '/' . $itemRelativePath,
            ];
            
            // Get image dimensions if it's an image
            if (!$isDir && $item['type'] === 'image') {
                $imageInfo = @getimagesize($itemPath);
                if ($imageInfo) {
                    $item['width'] = $imageInfo[0];
                    $item['height'] = $imageInfo[1];
                }
            }
            
            $items[] = $item;
        }
        
        // Sort: folders first, then files
        usort($items, function($a, $b) {
            if ($a['is_dir'] === $b['is_dir']) {
                return strcasecmp($a['name'], $b['name']);
            }
            return $b['is_dir'] - $a['is_dir'];
        });
        
        return $items;
    }
    
    /**
     * Get directory size recursively
     */
    private function getDirectorySize($path) {
        $size = 0;
        
        if (!is_dir($path)) {
            return filesize($path);
        }
        
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $filePath = $path . '/' . $file;
            if (is_dir($filePath)) {
                $size += $this->getDirectorySize($filePath);
            } else {
                $size += filesize($filePath);
            }
        }
        
        return $size;
    }
    
    /**
     * Get directory statistics
     */
    private function getDirectoryStats($path) {
        $stats = [
            'total_files' => 0,
            'total_folders' => 0,
            'total_size' => 0,
            'images' => 0,
            'documents' => 0,
            'videos' => 0,
            'audio' => 0,
        ];
        
        if (!is_dir($path)) {
            return $stats;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $stats['total_folders']++;
            } else {
                $stats['total_files']++;
                $stats['total_size'] += $item->getSize();
                
                $type = $this->getFileType($item->getFilename());
                if (isset($stats[$type . 's'])) {
                    $stats[$type . 's']++;
                } elseif ($type === 'image') {
                    $stats['images']++;
                } elseif ($type === 'document') {
                    $stats['documents']++;
                } elseif ($type === 'video') {
                    $stats['videos']++;
                } elseif ($type === 'audio') {
                    $stats['audio']++;
                }
            }
        }
        
        return $stats;
    }
    
    /**
     * Get breadcrumb trail
     */
    private function getBreadcrumb($path) {
        if (empty($path)) {
            return [];
        }
        
        $parts = explode('/', $path);
        $breadcrumb = [];
        $currentPath = '';
        
        foreach ($parts as $part) {
            $currentPath .= ($currentPath ? '/' : '') . $part;
            $breadcrumb[] = [
                'name' => $part,
                'path' => $currentPath
            ];
        }
        
        return $breadcrumb;
    }
    
    /**
     * Create new folder
     */
    public function createFolder() {
        try {
            $folderName = $_POST['folder_name'] ?? '';
            $currentPath = $_POST['current_path'] ?? '';
            $currentPath = $this->sanitizePath($currentPath);
            
            if (empty($folderName)) {
                throw new \Exception('Folder name is required');
            }
            
            // Sanitize folder name
            $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $folderName);
            
            $fullPath = $this->baseUploadDir . '/' . $currentPath;
            $newFolderPath = $fullPath . '/' . $folderName;
            
            if (!$this->isPathSafe($newFolderPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (file_exists($newFolderPath)) {
                throw new \Exception('Folder already exists');
            }
            
            if (!mkdir($newFolderPath, 0755, true)) {
                throw new \Exception('Failed to create folder');
            }
            
            $_SESSION['media_success'] = "Folder '{$folderName}' created successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->redirect(admin_url('filemanager?path=' . urlencode($currentPath)));
    }
    
    /**
     * Rename file or folder
     */
    public function rename() {
        try {
            $oldName = $_POST['old_name'] ?? '';
            $newName = $_POST['new_name'] ?? '';
            $currentPath = $_POST['current_path'] ?? '';
            $currentPath = $this->sanitizePath($currentPath);
            
            if (empty($oldName) || empty($newName)) {
                throw new \Exception('Names are required');
            }
            
            // Sanitize new name
            $newName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $newName);
            
            $basePath = $this->baseUploadDir . '/' . $currentPath;
            $oldPath = $basePath . '/' . $oldName;
            $newPath = $basePath . '/' . $newName;
            
            if (!$this->isPathSafe($oldPath) || !$this->isPathSafe($newPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($oldPath)) {
                throw new \Exception('File or folder not found');
            }
            
            if (file_exists($newPath)) {
                throw new \Exception('A file or folder with this name already exists');
            }
            
            if (!rename($oldPath, $newPath)) {
                throw new \Exception('Failed to rename');
            }
            
            $_SESSION['media_success'] = "Renamed to '{$newName}' successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->redirect(admin_url('filemanager?path=' . urlencode($currentPath)));
    }
    
    /**
     * Delete file or folder
     */
    public function delete() {
        try {
            $itemName = $_POST['item_name'] ?? '';
            $currentPath = $_POST['current_path'] ?? '';
            $currentPath = $this->sanitizePath($currentPath);
            
            if (empty($itemName)) {
                throw new \Exception('Item name is required');
            }
            
            $fullPath = $this->baseUploadDir . '/' . $currentPath . '/' . $itemName;
            
            if (!$this->isPathSafe($fullPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($fullPath)) {
                throw new \Exception('File or folder not found');
            }
            
            if (is_dir($fullPath)) {
                if (!$this->deleteDirectory($fullPath)) {
                    throw new \Exception('Failed to delete folder');
                }
            } else {
                if (!unlink($fullPath)) {
                    throw new \Exception('Failed to delete file');
                }
            }
            
            $_SESSION['media_success'] = "'{$itemName}' deleted successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->redirect(admin_url('filemanager?path=' . urlencode($currentPath)));
    }
    
    /**
     * Move file or folder
     */
    public function move() {
        try {
            $itemName = $_POST['item_name'] ?? '';
            $sourcePath = $_POST['source_path'] ?? '';
            $destPath = $_POST['dest_path'] ?? '';
            
            $sourcePath = $this->sanitizePath($sourcePath);
            $destPath = $this->sanitizePath($destPath);
            
            if (empty($itemName)) {
                throw new \Exception('Item name is required');
            }
            
            $sourceFullPath = $this->baseUploadDir . '/' . $sourcePath . '/' . $itemName;
            $destFullPath = $this->baseUploadDir . '/' . $destPath . '/' . $itemName;
            
            if (!$this->isPathSafe($sourceFullPath) || !$this->isPathSafe($destFullPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($sourceFullPath)) {
                throw new \Exception('Source not found');
            }
            
            if (file_exists($destFullPath)) {
                throw new \Exception('Destination already exists');
            }
            
            $destDir = dirname($destFullPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            if (!rename($sourceFullPath, $destFullPath)) {
                throw new \Exception('Failed to move');
            }
            
            $_SESSION['media_success'] = "'{$itemName}' moved successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->redirect(admin_url('filemanager?path=' . urlencode($sourcePath)));
    }
    
    /**
     * Upload files
     */
    public function upload() {
        try {
            if (empty($_FILES['files'])) {
                throw new \Exception('No files uploaded');
            }
            
            $currentPath = $_POST['current_path'] ?? '';
            $currentPath = $this->sanitizePath($currentPath);
            
            $uploadDir = $this->baseUploadDir . '/' . $currentPath;
            
            if (!$this->isPathSafe($uploadDir)) {
                throw new \Exception('Invalid path');
            }
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $files = $_FILES['files'];
            $uploadedCount = 0;
            
            $fileCount = is_array($files['name']) ? count($files['name']) : 1;
            
            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => is_array($files['name']) ? $files['name'][$i] : $files['name'],
                    'tmp_name' => is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'],
                    'error' => is_array($files['error']) ? $files['error'][$i] : $files['error'],
                    'size' => is_array($files['size']) ? $files['size'][$i] : $files['size']
                ];
                
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    continue;
                }
                
                // Validate file size
                $maxSize = 50 * 1024 * 1024; // 50MB
                if ($file['size'] > $maxSize) {
                    throw new \Exception("File {$file['name']} is too large (max 50MB)");
                }
                
                // Sanitize filename
                $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
                $uploadPath = $uploadDir . '/' . $filename;
                
                // Handle duplicate filenames
                $counter = 1;
                $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                
                while (file_exists($uploadPath)) {
                    $filename = $nameWithoutExt . '_' . $counter . '.' . $extension;
                    $uploadPath = $uploadDir . '/' . $filename;
                    $counter++;
                }
                
                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    throw new \Exception("Failed to upload {$file['name']}");
                }
                
                $uploadedCount++;
            }
            
            $_SESSION['media_success'] = "{$uploadedCount} file(s) uploaded successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->redirect(admin_url('filemanager?path=' . urlencode($currentPath)));
    }
    
    /**
     * Download file
     */
    public function download() {
        $itemPath = $_GET['path'] ?? '';
        $itemPath = $this->sanitizePath($itemPath);
        
        $fullPath = $this->baseUploadDir . '/' . $itemPath;
        
        if (!$this->isPathSafe($fullPath) || !file_exists($fullPath) || is_dir($fullPath)) {
            $this->abort(404, 'File not found');
        }
        
        $filename = basename($fullPath);
        $mimeType = mime_content_type($fullPath);
        
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }
    
    /**
     * Get folder tree for move operation
     */
    public function getFolderTree() {
        $currentPath = $_GET['current_path'] ?? '';
        $currentPath = $this->sanitizePath($currentPath);
        
        $tree = $this->buildFolderTree('', $currentPath);
        
        return $this->json(['success' => true, 'tree' => $tree]);
    }
    
    /**
     * Build folder tree recursively
     */
    private function buildFolderTree($path, $excludePath = '', $level = 0) {
        if ($level > 5) { // Prevent too deep recursion
            return [];
        }
        
        $fullPath = $this->baseUploadDir . '/' . $path;
        
        if (!is_dir($fullPath)) {
            return [];
        }
        
        $folders = [];
        $files = scandir($fullPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $itemPath = $fullPath . '/' . $file;
            $relativePath = $path ? $path . '/' . $file : $file;
            
            if (is_dir($itemPath) && $relativePath !== $excludePath) {
                $folders[] = [
                    'name' => $file,
                    'path' => $relativePath,
                    'level' => $level,
                    'children' => $this->buildFolderTree($relativePath, $excludePath, $level + 1)
                ];
            }
        }
        
        return $folders;
    }
    
    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
    
    /**
     * Sanitize path
     */
    private function sanitizePath($path) {
        // Remove any directory traversal attempts
        $path = str_replace(['../', '..\\', '\\'], '', $path);
        $path = trim($path, '/');
        return $path;
    }
    
    /**
     * Check if path is safe (within uploads directory)
     */
    private function isPathSafe($path) {
        $realBase = realpath($this->baseUploadDir);
        $realPath = realpath($path);
        
        // If path doesn't exist yet, check parent
        if ($realPath === false) {
            $realPath = realpath(dirname($path));
            if ($realPath === false) {
                return false;
            }
        }
        
        return strpos($realPath, $realBase) === 0;
    }
    
    /**
     * Get file type from filename
     */
    private function getFileType($filename) {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $types = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a'],
            'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'rtf'],
            'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
            'code' => ['php', 'js', 'css', 'html', 'json', 'xml', 'sql']
        ];
        
        foreach ($types as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }
        
        return 'other';
    }
}

<?php

namespace App\Http\Controllers;

use App\View\View;

class CodeEditorController extends Controller {
    
    private $projectRoot;
    private $allowedExtensions = [
        'php', 'js', 'css', 'html', 'json', 'xml', 'sql', 'md', 'txt',
        'blade.php', 'env', 'htaccess', 'yml', 'yaml', 'ini', 'conf'
    ];
    
    public function __construct() {
        $this->projectRoot = realpath(__DIR__ . '/../../..');
    }
    
    /**
     * Display code editor
     */
    public function index() {
        $filePath = $_GET['file'] ?? '';
        $filePath = $this->sanitizePath($filePath);
        
        $fileContent = '';
        $fileInfo = null;
        $mode = 'text/plain';
        
        if ($filePath && $this->isPathSafe($filePath)) {
            $fullPath = $this->projectRoot . '/' . $filePath;
            
            if (file_exists($fullPath) && is_file($fullPath)) {
                $fileContent = file_get_contents($fullPath);
                $fileInfo = $this->getFileInfo($fullPath, $filePath);
                $mode = $this->getEditorMode($filePath);
            }
        }
        
        return View::make('admin.codeeditor.index', [
            'title' => 'Code Editor',
            'filePath' => $filePath,
            'fileContent' => $fileContent,
            'fileInfo' => $fileInfo,
            'mode' => $mode,
            'success' => $_SESSION['editor_success'] ?? null,
            'error' => $_SESSION['editor_error'] ?? null
        ]);
    }
    
    /**
     * Get file explorer tree
     */
    public function getFileTree() {
        $path = $_GET['path'] ?? '';
        $path = $this->sanitizePath($path);
        
        $fullPath = $this->projectRoot . '/' . $path;
        
        if (!$this->isPathSafe($fullPath)) {
            return $this->json(['success' => false, 'error' => 'Invalid path']);
        }
        
        $tree = $this->buildFileTree($path);
        
        return $this->json(['success' => true, 'tree' => $tree]);
    }
    
    /**
     * Build file tree recursively
     */
    private function buildFileTree($path, $level = 0) {
        if ($level > 5) { // Prevent too deep recursion
            return [];
        }
        
        $fullPath = $this->projectRoot . '/' . $path;
        
        if (!is_dir($fullPath)) {
            return [];
        }
        
        $items = [];
        $files = scandir($fullPath);
        
        // Directories to skip
        $skipDirs = ['vendor', 'node_modules', '.git', '.idea', 'old', 'cache', 'logs'];
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            // Skip hidden files and excluded directories
            if (substr($file, 0, 1) === '.' && $file !== '.env' && $file !== '.htaccess') {
                continue;
            }
            
            if (in_array($file, $skipDirs)) {
                continue;
            }
            
            $itemPath = $fullPath . '/' . $file;
            $relativePath = $path ? $path . '/' . $file : $file;
            
            $isDir = is_dir($itemPath);
            
            $item = [
                'name' => $file,
                'path' => $relativePath,
                'is_dir' => $isDir,
                'level' => $level
            ];
            
            if ($isDir) {
                $item['children'] = $this->buildFileTree($relativePath, $level + 1);
            } else {
                $item['extension'] = pathinfo($file, PATHINFO_EXTENSION);
                $item['editable'] = $this->isEditable($file);
            }
            
            $items[] = $item;
        }
        
        // Sort: directories first, then files
        usort($items, function($a, $b) {
            if ($a['is_dir'] === $b['is_dir']) {
                return strcasecmp($a['name'], $b['name']);
            }
            return $b['is_dir'] - $a['is_dir'];
        });
        
        return $items;
    }
    
    /**
     * Save file content
     */
    public function save() {
        try {
            $filePath = $_POST['file_path'] ?? '';
            $content = $_POST['content'] ?? '';
            
            $filePath = $this->sanitizePath($filePath);
            
            if (empty($filePath)) {
                throw new \Exception('File path is required');
            }
            
            $fullPath = $this->projectRoot . '/' . $filePath;
            
            if (!$this->isPathSafe($fullPath)) {
                throw new \Exception('Invalid file path');
            }
            
            if (!$this->isEditable(basename($filePath))) {
                throw new \Exception('This file type cannot be edited');
            }
            
            // Create backup
            if (file_exists($fullPath)) {
                $backupPath = $fullPath . '.backup';
                copy($fullPath, $backupPath);
            }
            
            // Save file
            $result = file_put_contents($fullPath, $content);
            
            if ($result === false) {
                throw new \Exception('Failed to save file');
            }
            
            $_SESSION['editor_success'] = "File saved successfully! ({$filePath})";
            unset($_SESSION['editor_error']);
            
            return $this->json([
                'success' => true,
                'message' => 'File saved successfully',
                'bytes' => $result
            ]);
            
        } catch (\Exception $e) {
            $_SESSION['editor_error'] = $e->getMessage();
            unset($_SESSION['editor_success']);
            
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Create new file
     */
    public function createFile() {
        try {
            $filePath = $_POST['file_path'] ?? '';
            $fileName = $_POST['file_name'] ?? '';
            
            $filePath = $this->sanitizePath($filePath);
            
            if (empty($fileName)) {
                throw new \Exception('File name is required');
            }
            
            // Sanitize filename
            $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
            
            $fullPath = $this->projectRoot . '/' . $filePath . '/' . $fileName;
            
            if (!$this->isPathSafe($fullPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (file_exists($fullPath)) {
                throw new \Exception('File already exists');
            }
            
            // Create file
            if (file_put_contents($fullPath, '') === false) {
                throw new \Exception('Failed to create file');
            }
            
            $_SESSION['editor_success'] = "File '{$fileName}' created successfully!";
            unset($_SESSION['editor_error']);
            
            $newPath = $filePath ? $filePath . '/' . $fileName : $fileName;
            
            return $this->json([
                'success' => true,
                'message' => 'File created successfully',
                'path' => $newPath
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Create new folder
     */
    public function createFolder() {
        try {
            $folderPath = $_POST['folder_path'] ?? '';
            $folderName = $_POST['folder_name'] ?? '';
            
            $folderPath = $this->sanitizePath($folderPath);
            
            if (empty($folderName)) {
                throw new \Exception('Folder name is required');
            }
            
            // Sanitize folder name
            $folderName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $folderName);
            
            $fullPath = $this->projectRoot . '/' . $folderPath . '/' . $folderName;
            
            if (!$this->isPathSafe($fullPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (file_exists($fullPath)) {
                throw new \Exception('Folder already exists');
            }
            
            // Create folder
            if (!mkdir($fullPath, 0755, true)) {
                throw new \Exception('Failed to create folder');
            }
            
            $_SESSION['editor_success'] = "Folder '{$folderName}' created successfully!";
            unset($_SESSION['editor_error']);
            
            return $this->json([
                'success' => true,
                'message' => 'Folder created successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete file
     */
    public function deleteFile() {
        try {
            $filePath = $_POST['file_path'] ?? '';
            $filePath = $this->sanitizePath($filePath);
            
            if (empty($filePath)) {
                throw new \Exception('File path is required');
            }
            
            $fullPath = $this->projectRoot . '/' . $filePath;
            
            if (!$this->isPathSafe($fullPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($fullPath)) {
                throw new \Exception('File not found');
            }
            
            // Create backup before delete
            $backupPath = $fullPath . '.deleted';
            copy($fullPath, $backupPath);
            
            if (!unlink($fullPath)) {
                throw new \Exception('Failed to delete file');
            }
            
            $_SESSION['editor_success'] = "File deleted successfully!";
            unset($_SESSION['editor_error']);
            
            return $this->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Rename file
     */
    public function renameFile() {
        try {
            $oldPath = $_POST['old_path'] ?? '';
            $newName = $_POST['new_name'] ?? '';
            
            $oldPath = $this->sanitizePath($oldPath);
            
            if (empty($oldPath) || empty($newName)) {
                throw new \Exception('Path and name are required');
            }
            
            // Sanitize new name
            $newName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $newName);
            
            $fullOldPath = $this->projectRoot . '/' . $oldPath;
            $directory = dirname($fullOldPath);
            $fullNewPath = $directory . '/' . $newName;
            
            if (!$this->isPathSafe($fullOldPath) || !$this->isPathSafe($fullNewPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($fullOldPath)) {
                throw new \Exception('File not found');
            }
            
            if (file_exists($fullNewPath)) {
                throw new \Exception('A file with this name already exists');
            }
            
            if (!rename($fullOldPath, $fullNewPath)) {
                throw new \Exception('Failed to rename file');
            }
            
            $_SESSION['editor_success'] = "File renamed successfully!";
            unset($_SESSION['editor_error']);
            
            $newPath = dirname($oldPath) . '/' . $newName;
            if (dirname($oldPath) === '.') {
                $newPath = $newName;
            }
            
            return $this->json([
                'success' => true,
                'message' => 'File renamed successfully',
                'path' => $newPath
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Search files
     */
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            return $this->json(['success' => false, 'error' => 'Query too short']);
        }
        
        $results = $this->searchFiles($query);
        
        return $this->json(['success' => true, 'results' => $results]);
    }
    
    /**
     * Search files recursively
     */
    private function searchFiles($query, $path = '', $level = 0) {
        if ($level > 5) {
            return [];
        }
        
        $fullPath = $this->projectRoot . '/' . $path;
        
        if (!is_dir($fullPath)) {
            return [];
        }
        
        $results = [];
        $files = scandir($fullPath);
        $skipDirs = ['vendor', 'node_modules', '.git', '.idea', 'old', 'cache', 'logs'];
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || in_array($file, $skipDirs)) {
                continue;
            }
            
            $itemPath = $fullPath . '/' . $file;
            $relativePath = $path ? $path . '/' . $file : $file;
            
            if (is_dir($itemPath)) {
                $results = array_merge($results, $this->searchFiles($query, $relativePath, $level + 1));
            } else {
                if (stripos($file, $query) !== false) {
                    $results[] = [
                        'name' => $file,
                        'path' => $relativePath,
                        'extension' => pathinfo($file, PATHINFO_EXTENSION)
                    ];
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Get file info
     */
    private function getFileInfo($fullPath, $relativePath) {
        return [
            'name' => basename($fullPath),
            'path' => $relativePath,
            'size' => filesize($fullPath),
            'modified' => filemtime($fullPath),
            'extension' => pathinfo($fullPath, PATHINFO_EXTENSION),
            'readable' => is_readable($fullPath),
            'writable' => is_writable($fullPath),
            'lines' => count(file($fullPath))
        ];
    }
    
    /**
     * Get CodeMirror mode for file
     */
    private function getEditorMode($filePath) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Check for blade.php
        if (strpos($filePath, '.blade.php') !== false) {
            return 'application/x-httpd-php';
        }
        
        $modes = [
            'php' => 'application/x-httpd-php',
            'js' => 'text/javascript',
            'json' => 'application/json',
            'css' => 'text/css',
            'html' => 'text/html',
            'xml' => 'application/xml',
            'sql' => 'text/x-sql',
            'md' => 'text/x-markdown',
            'yml' => 'text/x-yaml',
            'yaml' => 'text/x-yaml',
            'sh' => 'text/x-sh',
            'py' => 'text/x-python',
        ];
        
        return $modes[$extension] ?? 'text/plain';
    }
    
    /**
     * Check if file is editable
     */
    private function isEditable($filename) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Check for blade.php
        if (strpos($filename, '.blade.php') !== false) {
            return true;
        }
        
        return in_array($extension, $this->allowedExtensions);
    }
    
    /**
     * Sanitize path
     */
    private function sanitizePath($path) {
        $path = str_replace(['../', '..\\', '\\'], '', $path);
        $path = trim($path, '/');
        return $path;
    }
    
    /**
     * Check if path is safe
     */
    private function isPathSafe($path) {
        $realRoot = realpath($this->projectRoot);
        $realPath = realpath($path);
        
        if ($realPath === false) {
            $realPath = realpath(dirname($path));
            if ($realPath === false) {
                return false;
            }
        }
        
        return strpos($realPath, $realRoot) === 0;
    }
}

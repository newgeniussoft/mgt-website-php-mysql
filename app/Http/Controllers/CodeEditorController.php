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
        
        // Debug info
        $debug = [];
        $debug['original_file'] = $_GET['file'] ?? 'NOT SET';
        $debug['sanitized_path'] = $filePath;
        $debug['project_root'] = $this->projectRoot;
        
        if ($filePath) {
            $fullPath = $this->projectRoot . '/' . $filePath;
            $debug['full_path'] = $fullPath;
            $debug['file_exists'] = file_exists($fullPath) ? 'YES' : 'NO';
            $debug['is_file'] = is_file($fullPath) ? 'YES' : 'NO';
            $debug['is_readable'] = is_readable($fullPath) ? 'YES' : 'NO';
            
            // Check if path is safe
            if ($this->isPathSafe($fullPath)) {
                $debug['path_safe'] = 'YES';
                
                if (file_exists($fullPath) && is_file($fullPath)) {
                    $fileContent = file_get_contents($fullPath);
                    $debug['content_length'] = strlen($fileContent);
                    $debug['first_100_chars'] = substr($fileContent, 0, 100);
                    
                    $fileInfo = $this->getFileInfo($fullPath, $filePath);
                    $mode = $this->getEditorMode($filePath);
                } else {
                    $fileContent = '// File not found: ' . $filePath;
                    $debug['error'] = 'File not found or not a file';
                }
            } else {
                $fileContent = '// Invalid or unsafe file path';
                $debug['path_safe'] = 'NO';
                $debug['error'] = 'Path is not safe';
            }
        } else {
            $debug['error'] = 'No file path provided';
        }
        
       
        
        return View::make('admin.codeeditor.index', [
            'title' => 'Code Editor',
            'filePath' => $filePath,
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
     * Get file content
     */
    public function readFile() {
        $path = $_GET['file'] ?? '';
        $path = $this->sanitizePath($path);
        
        $fullPath = $this->projectRoot . '/' . $path;

// Check if the file exists
if (file_exists($fullPath)) {
    // Set the correct header to display plain text
    header('Content-Type: text/plain; charset=utf-8');

    // Read and output the file contents
    readfile($fullPath);
} else {
    // If file not found, show an error
    header('HTTP/1.0 404 Not Found');
    echo "File not found.";
}
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
     * Move file or folder
     */
    public function moveFile() {
        try {
            $sourcePath = $_POST['source_path'] ?? '';
            $targetPath = $_POST['target_path'] ?? '';
            
            $sourcePath = $this->sanitizePath($sourcePath);
            $targetPath = $this->sanitizePath($targetPath);
            
            if (empty($sourcePath) || empty($targetPath)) {
                throw new \Exception('Source and target paths are required');
            }
            
            $fullSourcePath = $this->projectRoot . '/' . $sourcePath;
            $fullTargetPath = $this->projectRoot . '/' . $targetPath;
            
            if (!$this->isPathSafe($fullSourcePath) || !$this->isPathSafe($fullTargetPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($fullSourcePath)) {
                throw new \Exception('Source file/folder not found');
            }
            
            // If target is a directory, move source into it
            if (is_dir($fullTargetPath)) {
                $fileName = basename($fullSourcePath);
                $fullTargetPath = $fullTargetPath . '/' . $fileName;
            }
            
            if (file_exists($fullTargetPath)) {
                throw new \Exception('Target already exists');
            }
            
            // Create target directory if it doesn't exist
            $targetDir = dirname($fullTargetPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            if (!rename($fullSourcePath, $fullTargetPath)) {
                throw new \Exception('Failed to move file/folder');
            }
            
            $_SESSION['editor_success'] = "Moved successfully!";
            unset($_SESSION['editor_error']);
            
            return $this->json([
                'success' => true,
                'message' => 'Moved successfully',
                'path' => str_replace($this->projectRoot . '/', '', $fullTargetPath)
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Copy file or folder
     */
    public function copyFile() {
        try {
            $sourcePath = $_POST['source_path'] ?? '';
            $targetPath = $_POST['target_path'] ?? '';
            
            $sourcePath = $this->sanitizePath($sourcePath);
            $targetPath = $this->sanitizePath($targetPath);
            
            if (empty($sourcePath) || empty($targetPath)) {
                throw new \Exception('Source and target paths are required');
            }
            
            $fullSourcePath = $this->projectRoot . '/' . $sourcePath;
            $fullTargetPath = $this->projectRoot . '/' . $targetPath;
            
            if (!$this->isPathSafe($fullSourcePath) || !$this->isPathSafe($fullTargetPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($fullSourcePath)) {
                throw new \Exception('Source file/folder not found');
            }
            
            // If target is a directory, copy source into it
            if (is_dir($fullTargetPath)) {
                $fileName = basename($fullSourcePath);
                $fullTargetPath = $fullTargetPath . '/' . $fileName;
            }
            
            if (file_exists($fullTargetPath)) {
                throw new \Exception('Target already exists');
            }
            
            // Create target directory if it doesn't exist
            $targetDir = dirname($fullTargetPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            // Copy file or directory
            if (is_dir($fullSourcePath)) {
                if (!$this->copyDirectory($fullSourcePath, $fullTargetPath)) {
                    throw new \Exception('Failed to copy folder');
                }
            } else {
                if (!copy($fullSourcePath, $fullTargetPath)) {
                    throw new \Exception('Failed to copy file');
                }
            }
            
            $_SESSION['editor_success'] = "Copied successfully!";
            unset($_SESSION['editor_error']);
            
            return $this->json([
                'success' => true,
                'message' => 'Copied successfully',
                'path' => str_replace($this->projectRoot . '/', '', $fullTargetPath)
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Copy directory recursively
     */
    private function copyDirectory($source, $target) {
        if (!is_dir($source)) {
            return false;
        }
        
        if (!mkdir($target, 0755, true)) {
            return false;
        }
        
        $items = scandir($source);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $sourcePath = $source . '/' . $item;
            $targetPath = $target . '/' . $item;
            
            if (is_dir($sourcePath)) {
                if (!$this->copyDirectory($sourcePath, $targetPath)) {
                    return false;
                }
            } else {
                if (!copy($sourcePath, $targetPath)) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Delete folder recursively
     */
    public function deleteFolder() {
        try {
            $folderPath = $_POST['folder_path'] ?? '';
            $folderPath = $this->sanitizePath($folderPath);
            
            if (empty($folderPath)) {
                throw new \Exception('Folder path is required');
            }
            
            $fullPath = $this->projectRoot . '/' . $folderPath;
            
            if (!$this->isPathSafe($fullPath)) {
                throw new \Exception('Invalid path');
            }
            
            if (!file_exists($fullPath)) {
                throw new \Exception('Folder not found');
            }
            
            if (!is_dir($fullPath)) {
                throw new \Exception('Not a folder');
            }
            
            // Create backup before delete
            $backupPath = $fullPath . '.deleted';
            $this->copyDirectory($fullPath, $backupPath);
            
            if (!$this->deleteDirectory($fullPath)) {
                throw new \Exception('Failed to delete folder');
            }
            
            $_SESSION['editor_success'] = "Folder deleted successfully!";
            unset($_SESSION['editor_error']);
            
            return $this->json([
                'success' => true,
                'message' => 'Folder deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $path = $dir . '/' . $item;
            
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
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
        // Remove directory traversal attempts
        $path = str_replace(['../', '..\\'], '', $path);
        // Normalize path separators to forward slashes
        $path = str_replace('\\', '/', $path);
        // Remove leading/trailing slashes
        $path = trim($path, '/');
        return $path;
    }
    
    /**
     * Check if path is safe (within project root)
     */
    private function isPathSafe($path) {
        $realRoot = realpath($this->projectRoot);
        
        if ($realRoot === false) {
            return false;
        }
        
        // Normalize path separators for comparison
        $realRoot = str_replace('\\', '/', $realRoot);
        
        // If path doesn't exist, check if parent directory is safe
        if (!file_exists($path)) {
            $parentDir = dirname($path);
            $realPath = realpath($parentDir);
            
            if ($realPath === false) {
                return false;
            }
        } else {
            $realPath = realpath($path);
            
            if ($realPath === false) {
                return false;
            }
        }
        
        // Normalize path separators for comparison
        $realPath = str_replace('\\', '/', $realPath);
        
        // Check if path starts with project root
        return strpos($realPath, $realRoot) === 0;
    }
}

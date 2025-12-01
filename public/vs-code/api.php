<?php
header('Content-Type: application/json');

// Set the root directory (same as in index.php)
define('ROOT_DIR', __DIR__ . '../../../');

// Security: Prevent directory traversal
function sanitizePath($path) {
    $path = str_replace(['../', '..\\'], '', $path);
    return $path;
}

function getFullPath($relativePath) {
    $sanitized = sanitizePath($relativePath);
    return ROOT_DIR . '/' . $sanitized;
}

// Read directory recursively
function readDirectory($dir, $basePath = '') {
    $result = [];
    
    if (!is_dir($dir)) {
        return $result;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $fullPath = $dir . '/' . $item;
        $relativePath = $basePath ? $basePath . '/' . $item : $item;
        
        if (is_dir($fullPath)) {
            $result[] = [
                'name' => $item,
                'path' => $relativePath,
                'type' => 'folder',
                'children' => readDirectory($fullPath, $relativePath)
            ];
        } else {
            $result[] = [
                'name' => $item,
                'path' => $relativePath,
                'type' => 'file',
                'size' => filesize($fullPath),
                'modified' => filemtime($fullPath)
            ];
        }
    }
    
    // Sort: folders first, then files
    usort($result, function($a, $b) {
        if ($a['type'] === $b['type']) {
            return strcmp($a['name'], $b['name']);
        }
        return $a['type'] === 'folder' ? -1 : 1;
    });
    
    return $result;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'listFiles':
            $files = readDirectory(ROOT_DIR);
            echo json_encode(['success' => true, 'files' => $files]);
            break;
            
        case 'readFile':
            $path = $_POST['path'] ?? '';
            $fullPath = getFullPath($path);
            
            if (!file_exists($fullPath) || !is_file($fullPath)) {
                throw new Exception('File not found');
            }
            
            $content = file_get_contents($fullPath);
            echo json_encode(['success' => true, 'content' => $content]);
            break;
            
        case 'saveFile':
            $path = $_POST['path'] ?? '';
            $content = $_POST['content'] ?? '';
            $fullPath = getFullPath($path);
            
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            if (file_put_contents($fullPath, $content) === false) {
                throw new Exception('Failed to save file');
            }
            
            echo json_encode(['success' => true, 'message' => 'File saved successfully']);
            break;
            
        case 'createFile':
            $path = $_POST['path'] ?? '';
            $fullPath = getFullPath($path);
            
            if (file_exists($fullPath)) {
                throw new Exception('File already exists');
            }
            
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            if (file_put_contents($fullPath, '') === false) {
                throw new Exception('Failed to create file');
            }
            
            echo json_encode(['success' => true, 'message' => 'File created successfully']);
            break;
            
        case 'createFolder':
            $path = $_POST['path'] ?? '';
            $fullPath = getFullPath($path);
            
            if (file_exists($fullPath)) {
                throw new Exception('Folder already exists');
            }
            
            if (!mkdir($fullPath, 0755, true)) {
                throw new Exception('Failed to create folder');
            }
            
            echo json_encode(['success' => true, 'message' => 'Folder created successfully']);
            break;
            
        case 'rename':
            $oldPath = $_POST['oldPath'] ?? '';
            $newPath = $_POST['newPath'] ?? '';
            $fullOldPath = getFullPath($oldPath);
            $fullNewPath = getFullPath($newPath);
            
            if (!file_exists($fullOldPath)) {
                throw new Exception('Item not found');
            }
            
            if (file_exists($fullNewPath)) {
                throw new Exception('An item with that name already exists');
            }
            
            if (!rename($fullOldPath, $fullNewPath)) {
                throw new Exception('Failed to rename');
            }
            
            echo json_encode(['success' => true, 'message' => 'Renamed successfully']);
            break;
            
        case 'move':
            $sourcePath = $_POST['sourcePath'] ?? '';
            $destPath = $_POST['destPath'] ?? '';
            $fullSourcePath = getFullPath($sourcePath);
            $fullDestPath = getFullPath($destPath);
            
            if (!file_exists($fullSourcePath)) {
                throw new Exception('Source not found');
            }
            
            $destFile = $fullDestPath . '/' . basename($fullSourcePath);
            if (file_exists($destFile)) {
                throw new Exception('Destination already exists');
            }
            
            if (!is_dir($fullDestPath)) {
                mkdir($fullDestPath, 0755, true);
            }
            
            if (!rename($fullSourcePath, $destFile)) {
                throw new Exception('Failed to move');
            }
            
            echo json_encode(['success' => true, 'message' => 'Moved successfully']);
            break;
            
        case 'copy':
            $sourcePath = $_POST['sourcePath'] ?? '';
            $destPath = $_POST['destPath'] ?? '';
            $fullSourcePath = getFullPath($sourcePath);
            $fullDestPath = getFullPath($destPath);
            
            if (!file_exists($fullSourcePath)) {
                throw new Exception('Source not found');
            }
            
            $destFile = $fullDestPath . '/' . basename($fullSourcePath);
            
            if (!is_dir($fullDestPath)) {
                mkdir($fullDestPath, 0755, true);
            }
            
            function copyRecursive($source, $dest) {
                if (is_dir($source)) {
                    if (!is_dir($dest)) {
                        mkdir($dest, 0755, true);
                    }
                    $items = scandir($source);
                    foreach ($items as $item) {
                        if ($item === '.' || $item === '..') continue;
                        copyRecursive($source . '/' . $item, $dest . '/' . $item);
                    }
                } else {
                    copy($source, $dest);
                }
            }
            
            copyRecursive($fullSourcePath, $destFile);
            
            echo json_encode(['success' => true, 'message' => 'Copied successfully']);
            break;
            
        case 'upload':
            if (!isset($_FILES['file'])) {
                throw new Exception('No file uploaded');
            }
            
            $uploadPath = $_POST['uploadPath'] ?? '';
            $fullUploadPath = getFullPath($uploadPath);
            
            if (!is_dir($fullUploadPath)) {
                mkdir($fullUploadPath, 0755, true);
            }
            
            $file = $_FILES['file'];
            $targetPath = $fullUploadPath . '/' . basename($file['name']);
            
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                throw new Exception('Failed to upload file');
            }
            
            echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
            break;
            
        case 'download':
            $path = $_GET['path'] ?? '';
            $fullPath = getFullPath($path);
            
            if (!file_exists($fullPath)) {
                throw new Exception('File not found');
            }
            
            if (is_dir($fullPath)) {
                // Create ZIP for directory download
                $zipName = basename($fullPath) . '.zip';
                $zipPath = sys_get_temp_dir() . '/' . $zipName;
                
                $zip = new ZipArchive();
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                    throw new Exception('Failed to create ZIP');
                }
                
                function addToZip($zip, $dir, $base = '') {
                    $items = scandir($dir);
                    foreach ($items as $item) {
                        if ($item === '.' || $item === '..') continue;
                        $path = $dir . '/' . $item;
                        $zipPath = $base ? $base . '/' . $item : $item;
                        if (is_dir($path)) {
                            $zip->addEmptyDir($zipPath);
                            addToZip($zip, $path, $zipPath);
                        } else {
                            $zip->addFile($path, $zipPath);
                        }
                    }
                }
                
                addToZip($zip, $fullPath);
                $zip->close();
                
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipName . '"');
                header('Content-Length: ' . filesize($zipPath));
                readfile($zipPath);
                unlink($zipPath);
                exit;
            } else {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
                header('Content-Length: ' . filesize($fullPath));
                readfile($fullPath);
                exit;
            }
            break;
            
        case 'compress':
            $sourcePath = $_POST['sourcePath'] ?? '';
            $zipName = $_POST['zipName'] ?? 'archive.zip';
            $fullSourcePath = getFullPath($sourcePath);
            
            if (!file_exists($fullSourcePath)) {
                throw new Exception('Source not found');
            }
            
            $zipPath = dirname($fullSourcePath) . '/' . $zipName;
            
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new Exception('Failed to create ZIP');
            }
            
            if (is_dir($fullSourcePath)) {
                function addDirToZip($zip, $dir, $base = '') {
                    $items = scandir($dir);
                    foreach ($items as $item) {
                        if ($item === '.' || $item === '..') continue;
                        $path = $dir . '/' . $item;
                        $zipPath = $base ? $base . '/' . $item : $item;
                        if (is_dir($path)) {
                            $zip->addEmptyDir($zipPath);
                            addDirToZip($zip, $path, $zipPath);
                        } else {
                            $zip->addFile($path, $zipPath);
                        }
                    }
                }
                addDirToZip($zip, $fullSourcePath, basename($fullSourcePath));
            } else {
                $zip->addFile($fullSourcePath, basename($fullSourcePath));
            }
            
            $zip->close();
            
            echo json_encode(['success' => true, 'message' => 'Compressed successfully']);
            break;
            
        case 'extract':
            $zipPath = $_POST['zipPath'] ?? '';
            $fullZipPath = getFullPath($zipPath);
            
            if (!file_exists($fullZipPath)) {
                throw new Exception('ZIP file not found');
            }
            
            $extractPath = dirname($fullZipPath) . '/' . pathinfo($fullZipPath, PATHINFO_FILENAME);
            
            $zip = new ZipArchive();
            if ($zip->open($fullZipPath) !== true) {
                throw new Exception('Failed to open ZIP file');
            }
            
            if (!$zip->extractTo($extractPath)) {
                $zip->close();
                throw new Exception('Failed to extract ZIP');
            }
            
            $zip->close();
            
            echo json_encode(['success' => true, 'message' => 'Extracted successfully']);
            break;
            
        case 'delete':
            $path = $_POST['path'] ?? '';
            $fullPath = getFullPath($path);
            
            if (!file_exists($fullPath)) {
                throw new Exception('Item not found');
            }
            
            if (is_dir($fullPath)) {
                function deleteDirectory($dir) {
                    if (!is_dir($dir)) {
                        return unlink($dir);
                    }
                    
                    $items = scandir($dir);
                    foreach ($items as $item) {
                        if ($item === '.' || $item === '..') {
                            continue;
                        }
                        
                        $path = $dir . '/' . $item;
                        if (is_dir($path)) {
                            deleteDirectory($path);
                        } else {
                            unlink($path);
                        }
                    }
                    
                    return rmdir($dir);
                }
                
                if (!deleteDirectory($fullPath)) {
                    throw new Exception('Failed to delete folder');
                }
            } else {
                if (!unlink($fullPath)) {
                    throw new Exception('Failed to delete file');
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
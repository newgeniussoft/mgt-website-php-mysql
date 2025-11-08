<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaFolder;
use App\View\View;

class MediaController extends Controller {
    
    /**
     * Display media library
     */
    public function index() {
        $folderId = $_GET['folder'] ?? null;
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        
        // Get media
        if ($search) {
            $mediaItems = Media::search($search);
        } elseif ($type) {
            $mediaItems = Media::getByType($type);
        } elseif ($folderId) {
            $mediaItems = Media::getByFolder($folderId);
        } else {
            $mediaItems = Media::all();
        }
        
        // Get folders
        $folders = MediaFolder::getRootFolders();
        $currentFolder = $folderId ? MediaFolder::find($folderId) : null;
        
        // Get statistics
        $stats = Media::getStats();
        
        return View::make('admin.media.index', [
            'title' => 'Media Library',
            'mediaItems' => $mediaItems,
            'folders' => $folders,
            'currentFolder' => $currentFolder,
            'stats' => $stats,
            'search' => $search,
            'type' => $type,
            'success' => $_SESSION['media_success'] ?? null,
            'error' => $_SESSION['media_error'] ?? null
        ]);
    }
    
    /**
     * Show upload form
     */
    public function create() {
        $folders = MediaFolder::getRootFolders();
        $folderId = $_GET['folder'] ?? null;
        
        return View::make('admin.media.upload', [
            'title' => 'Upload Media',
            'folders' => $folders,
            'selectedFolder' => $folderId
        ]);
    }
    
    /**
     * Handle file upload
     */
    public function store() {
        try {
            if (empty($_FILES['files'])) {
                throw new \Exception('No files uploaded');
            }
            
            $files = $_FILES['files'];
            $folderId = $_POST['folder_id'] ?? null;
            $uploadedCount = 0;
            
            // Handle multiple files
            $fileCount = is_array($files['name']) ? count($files['name']) : 1;
            
            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => is_array($files['name']) ? $files['name'][$i] : $files['name'],
                    'type' => is_array($files['type']) ? $files['type'][$i] : $files['type'],
                    'tmp_name' => is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'],
                    'error' => is_array($files['error']) ? $files['error'][$i] : $files['error'],
                    'size' => is_array($files['size']) ? $files['size'][$i] : $files['size']
                ];
                
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    continue;
                }
                
                // Validate file
                $maxSize = 10 * 1024 * 1024; // 10MB
                if ($file['size'] > $maxSize) {
                    throw new \Exception("File {$file['name']} is too large (max 10MB)");
                }
                
                // Determine file type
                $mimeType = $file['type'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $type = $this->determineFileType($mimeType, $extension);
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $extension;
                $uploadDir = __DIR__ . '/../../../public/uploads/media/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $uploadPath = $uploadDir . $filename;
                $urlPath = '/uploads/media/' . $filename;
                
                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    throw new \Exception("Failed to upload {$file['name']}");
                }
                
                // Get image dimensions if it's an image
                $width = null;
                $height = null;
                if ($type === 'image') {
                    $imageInfo = getimagesize($uploadPath);
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                    }
                }
                
                // Create media record
                Media::create([
                    'filename' => $filename,
                    'original_filename' => $file['name'],
                    'path' => $urlPath,
                    'url' => $urlPath,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'size' => $file['size'],
                    'width' => $width,
                    'height' => $height,
                    'type' => $type,
                    'folder_id' => $folderId ?: null,
                    'user_id' => $_SESSION['admin_id'] ?? null,
                    'is_public' => 1
                ]);
                
                $uploadedCount++;
            }
            
            $_SESSION['media_success'] = "{$uploadedCount} file(s) uploaded successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->redirect(admin_url('media' . ($folderId ? '?folder=' . $folderId : '')));
    }
    
    /**
     * Show edit form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['media_error'] = 'Media ID is required';
            return $this->back();
        }
        
        $media = Media::find($id);
        
        if (!$media) {
            $_SESSION['media_error'] = 'Media not found';
            return $this->back();
        }
        
        $folders = MediaFolder::getRootFolders();
        
        return View::make('admin.media.edit', [
            'title' => 'Edit Media',
            'media' => $media,
            'folders' => $folders
        ]);
    }
    
    /**
     * Update media
     */
    public function update() {
        try {
            $id = $_POST['id'] ?? null;
            
            if (!$id) {
                throw new \Exception('Media ID is required');
            }
            
            $media = Media::find($id);
            
            if (!$media) {
                throw new \Exception('Media not found');
            }
            
            // Update fields
            $media->title = $_POST['title'] ?? '';
            $media->alt_text = $_POST['alt_text'] ?? '';
            $media->description = $_POST['description'] ?? '';
            $media->folder_id = $_POST['folder_id'] ?: null;
            $media->is_public = isset($_POST['is_public']) ? 1 : 0;
            
            $media->save();
            
            $_SESSION['media_success'] = 'Media updated successfully!';
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Delete media
     */
    public function destroy() {
        try {
            $id = $_POST['id'] ?? null;
            
            if (!$id) {
                throw new \Exception('Media ID is required');
            }
            
            $media = Media::find($id);
            
            if (!$media) {
                throw new \Exception('Media not found');
            }
            
            // Delete file from disk
            $media->deleteFile();
            
            // Delete database record
            $media->delete();
            
            $_SESSION['media_success'] = 'Media deleted successfully!';
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Download media file
     */
    public function download() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $this->abort(404, 'Media not found');
        }
        
        $media = Media::find($id);
        
        if (!$media) {
            $this->abort(404, 'Media not found');
        }
        
        $filePath = __DIR__ . '/../../../public' . $media->path;
        
        if (!file_exists($filePath)) {
            $this->abort(404, 'File not found');
        }
        
        // Increment download count
        $media->incrementDownloads();
        
        // Send file
        header('Content-Type: ' . $media->mime_type);
        header('Content-Disposition: attachment; filename="' . $media->original_filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
    
    /**
     * Determine file type from MIME type
     */
    protected function determineFileType($mimeType, $extension) {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'video';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'audio';
        } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'])) {
            return 'document';
        }
        return 'other';
    }
    
    /**
     * Get media via API
     */
    public function getMedia() {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                return $this->json(['success' => false, 'message' => 'ID required'], 400);
            }
            
            $media = Media::find($id);
            
            if (!$media) {
                return $this->json(['success' => false, 'message' => 'Media not found'], 404);
            }
            
            return $this->json([
                'success' => true,
                'data' => $media->toArray()
            ]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Show folder management page
     */
    public function folders() {
        $folders = MediaFolder::all();
        $rootFolders = MediaFolder::getRootFolders();
        
        return View::make('admin.media.folders', [
            'title' => 'Manage Folders',
            'folders' => $folders,
            'rootFolders' => $rootFolders,
            'success' => $_SESSION['media_success'] ?? null,
            'error' => $_SESSION['media_error'] ?? null
        ]);
    }
    
    /**
     * Create new folder
     */
    public function createFolder() {
        try {
            $name = $_POST['name'] ?? '';
            $parentId = $_POST['parent_id'] ?? null;
            $description = $_POST['description'] ?? '';
            
            if (empty($name)) {
                throw new \Exception('Folder name is required');
            }
            
            // Generate slug
            $slug = MediaFolder::generateSlug($name);
            
            // Create folder
            MediaFolder::create([
                'name' => $name,
                'slug' => $slug,
                'parent_id' => $parentId ?: null,
                'description' => $description,
                'order' => 0
            ]);
            
            $_SESSION['media_success'] = "Folder '{$name}' created successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Update folder
     */
    public function updateFolder() {
        try {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
            $parentId = $_POST['parent_id'] ?? null;
            $description = $_POST['description'] ?? '';
            
            if (!$id) {
                throw new \Exception('Folder ID is required');
            }
            
            if (empty($name)) {
                throw new \Exception('Folder name is required');
            }
            
            $folder = MediaFolder::find($id);
            
            if (!$folder) {
                throw new \Exception('Folder not found');
            }
            
            // Update slug if name changed
            if ($folder->name !== $name) {
                $folder->slug = MediaFolder::generateSlug($name, $id);
            }
            
            $folder->name = $name;
            $folder->parent_id = $parentId ?: null;
            $folder->description = $description;
            $folder->save();
            
            $_SESSION['media_success'] = "Folder '{$name}' updated successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Delete folder
     */
    public function deleteFolder() {
        try {
            $id = $_POST['id'] ?? null;
            
            if (!$id) {
                throw new \Exception('Folder ID is required');
            }
            
            $folder = MediaFolder::find($id);
            
            if (!$folder) {
                throw new \Exception('Folder not found');
            }
            
            // Check if folder has media
            $mediaCount = $folder->getMediaCount();
            if ($mediaCount > 0) {
                throw new \Exception("Cannot delete folder with {$mediaCount} file(s). Move or delete files first.");
            }
            
            // Check if folder has children
            $children = $folder->getChildren();
            if (count($children) > 0) {
                throw new \Exception("Cannot delete folder with subfolders. Delete subfolders first.");
            }
            
            $folder->delete();
            
            $_SESSION['media_success'] = 'Folder deleted successfully!';
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Move file to folder
     */
    public function moveFile() {
        try {
            $id = $_POST['id'] ?? null;
            $folderId = $_POST['folder_id'] ?? null;
            
            if (!$id) {
                throw new \Exception('Media ID is required');
            }
            
            $media = Media::find($id);
            
            if (!$media) {
                throw new \Exception('Media not found');
            }
            
            // Validate folder exists if provided
            if ($folderId) {
                $folder = MediaFolder::find($folderId);
                if (!$folder) {
                    throw new \Exception('Folder not found');
                }
            }
            
            $media->folder_id = $folderId ?: null;
            $media->save();
            
            $folderName = $folderId ? MediaFolder::find($folderId)->name : 'Root';
            $_SESSION['media_success'] = "File moved to '{$folderName}' successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Rename file
     */
    public function renameFile() {
        try {
            $id = $_POST['id'] ?? null;
            $newName = $_POST['new_name'] ?? '';
            
            if (!$id) {
                throw new \Exception('Media ID is required');
            }
            
            if (empty($newName)) {
                throw new \Exception('New filename is required');
            }
            
            $media = Media::find($id);
            
            if (!$media) {
                throw new \Exception('Media not found');
            }
            
            // Get file extension
            $extension = pathinfo($media->filename, PATHINFO_EXTENSION);
            $newNameWithExt = $newName;
            
            // Add extension if not provided
            if (!preg_match('/\.' . preg_quote($extension, '/') . '$/i', $newName)) {
                $newNameWithExt .= '.' . $extension;
            }
            
            // Sanitize filename
            $newNameWithExt = preg_replace('/[^a-zA-Z0-9._-]/', '_', $newNameWithExt);
            
            // Rename physical file
            $oldPath = __DIR__ . '/../../../public' . $media->path;
            $newPath = dirname($oldPath) . '/' . $newNameWithExt;
            
            if (file_exists($newPath)) {
                throw new \Exception('A file with this name already exists');
            }
            
            if (file_exists($oldPath)) {
                if (!rename($oldPath, $newPath)) {
                    throw new \Exception('Failed to rename file on disk');
                }
            }
            
            // Update database
            $media->filename = $newNameWithExt;
            $media->original_filename = $newNameWithExt;
            $media->path = dirname($media->path) . '/' . $newNameWithExt;
            $media->url = dirname($media->url) . '/' . $newNameWithExt;
            $media->save();
            
            $_SESSION['media_success'] = "File renamed to '{$newNameWithExt}' successfully!";
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
    
    /**
     * Bulk operations
     */
    public function bulkAction() {
        try {
            $action = $_POST['action'] ?? '';
            $ids = $_POST['ids'] ?? [];
            
            if (empty($action)) {
                throw new \Exception('Action is required');
            }
            
            if (empty($ids) || !is_array($ids)) {
                throw new \Exception('No files selected');
            }
            
            $count = 0;
            
            switch ($action) {
                case 'delete':
                    foreach ($ids as $id) {
                        $media = Media::find($id);
                        if ($media) {
                            $media->deleteFile();
                            $media->delete();
                            $count++;
                        }
                    }
                    $_SESSION['media_success'] = "{$count} file(s) deleted successfully!";
                    break;
                    
                case 'move':
                    $folderId = $_POST['folder_id'] ?? null;
                    foreach ($ids as $id) {
                        $media = Media::find($id);
                        if ($media) {
                            $media->folder_id = $folderId ?: null;
                            $media->save();
                            $count++;
                        }
                    }
                    $folderName = $folderId ? MediaFolder::find($folderId)->name : 'Root';
                    $_SESSION['media_success'] = "{$count} file(s) moved to '{$folderName}' successfully!";
                    break;
                    
                default:
                    throw new \Exception('Invalid action');
            }
            
            unset($_SESSION['media_error']);
            
        } catch (\Exception $e) {
            $_SESSION['media_error'] = $e->getMessage();
            unset($_SESSION['media_success']);
        }
        
        return $this->back();
    }
}

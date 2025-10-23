<?php

require_once __DIR__ . '/../../models/Media.php';
require_once __DIR__ . '/../../models/MediaFolder.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class MediaController extends Controller {
    private $media;
    private $mediaFolder;
    
    public function __construct($language = 'en') {
        parent::__construct($language);
        $this->media = new Media();
        $this->mediaFolder = new MediaFolder();
        
        // Check authentication for all methods
        AuthMiddleware::requireAuth();
    }
    
    /**
     * Media library index page
     */
    public function index() {
        $filters = [
            'type' => $_GET['type'] ?? '',
            'folder_id' => $_GET['folder'] ?? '',
            'search' => $_GET['search'] ?? '',
            'limit' => 20,
            'offset' => ($_GET['page'] ?? 1 - 1) * 20
        ];
        
        $media = $this->media->getAll($filters);
        $folders = $this->mediaFolder->getAll();
        $stats = $this->media->getStats();
        
        // Get current folder info if specified
        $currentFolder = null;
        $breadcrumb = [];
        if (!empty($filters['folder_id'])) {
            $currentFolder = $this->mediaFolder->getById($filters['folder_id']);
            $breadcrumb = $this->mediaFolder->getBreadcrumb($filters['folder_id']);
        }
        
        $data = [
            'media' => $media,
            'folders' => $folders,
            'stats' => $stats,
            'filters' => $filters,
            'currentFolder' => $currentFolder,
            'breadcrumb' => $breadcrumb,
            'title' => 'Media Library',
            'page' => 'media'
        ];
        
        $this->render('admin/media/index', $data);
    }
    
    /**
     * Upload media files
     */
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpload();
            return;
        }
        
        $folders = $this->mediaFolder->getAll();
        
        $data = [
            'folders' => $folders,
            'title' => 'Upload Media',
            'page' => 'media'
        ];
        
        $this->render('admin/media/upload', $data);
    }
    
    /**
     * Handle file upload
     */
    private function handleUpload() {
        // CSRF protection
        if (!AuthMiddleware::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/media/upload');
            exit;
        }
        
        if (!isset($_FILES['files'])) {
            $_SESSION['error'] = 'No files uploaded';
            header('Location: /admin/media/upload');
            exit;
        }
        
        $files = $_FILES['files'];
        $folderId = $_POST['folder_id'] ?? null;
        $uploadedFiles = [];
        $errors = [];
        
        // Handle multiple files
        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $result = $this->processFileUpload($file, $folderId);
                if ($result['success']) {
                    $uploadedFiles[] = $result['file'];
                } else {
                    $errors[] = $result['error'];
                }
            }
        } else {
            $result = $this->processFileUpload($files, $folderId);
            if ($result['success']) {
                $uploadedFiles[] = $result['file'];
            } else {
                $errors[] = $result['error'];
            }
        }
        
        // Set session messages
        if (!empty($uploadedFiles)) {
            $_SESSION['success'] = count($uploadedFiles) . ' file(s) uploaded successfully';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
        }
        
        header('Location: /admin/media');
        exit;
    }
    
    /**
     * Process single file upload
     */
    private function processFileUpload($file, $folderId = null) {
        // Validate file
        $validationErrors = $this->media->validateFile($file);
        if (!empty($validationErrors)) {
            return ['success' => false, 'error' => implode(', ', $validationErrors)];
        }
        
        // Generate unique filename
        $filename = $this->media->generateUniqueFilename($file['name']);
        
        // Determine file type and upload path
        $mimeType = mime_content_type($file['tmp_name']);
        $fileType = $this->media->getFileTypeFromMime($mimeType);
        
        // Create upload directory structure
        $uploadDir = '/uploads/' . $fileType . 's/';
        $fullUploadDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
        
        if (!is_dir($fullUploadDir)) {
            mkdir($fullUploadDir, 0755, true);
        }
        
        $filePath = $uploadDir . $filename;
        $fullFilePath = $_SERVER['DOCUMENT_ROOT'] . $filePath;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $fullFilePath)) {
            return ['success' => false, 'error' => 'Failed to move uploaded file'];
        }
        
        // Get image dimensions if it's an image
        $width = null;
        $height = null;
        $thumbnailPath = null;
        
        if ($fileType === 'image') {
            $imageInfo = getimagesize($fullFilePath);
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                
                // Create thumbnail
                $thumbnailDir = '/uploads/thumbnails/';
                $fullThumbnailDir = $_SERVER['DOCUMENT_ROOT'] . $thumbnailDir;
                
                if (!is_dir($fullThumbnailDir)) {
                    mkdir($fullThumbnailDir, 0755, true);
                }
                
                $thumbnailPath = $thumbnailDir . 'thumb_' . $filename;
                $fullThumbnailPath = $_SERVER['DOCUMENT_ROOT'] . $thumbnailPath;
                
                if (!$this->media->createThumbnail($fullFilePath, $fullThumbnailPath)) {
                    $thumbnailPath = null;
                }
            }
        }
        
        // Save to database
        $mediaData = [
            'filename' => $filename,
            'original_name' => $file['name'],
            'file_path' => $filePath,
            'file_size' => $file['size'],
            'mime_type' => $mimeType,
            'file_type' => $fileType,
            'width' => $width,
            'height' => $height,
            'thumbnail_path' => $thumbnailPath,
            'uploaded_by' => $_SESSION['user_id'],
            'folder_id' => $folderId,
            'is_public' => 1
        ];
        
        $mediaId = $this->media->create($mediaData);
        
        if (!$mediaId) {
            // Delete uploaded file if database insert failed
            unlink($fullFilePath);
            if ($thumbnailPath) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $thumbnailPath);
            }
            return ['success' => false, 'error' => 'Failed to save file information'];
        }
        
        return ['success' => true, 'file' => $mediaData];
    }
    
    /**
     * Edit media details
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Media ID not provided';
            header('Location: /admin/media');
            exit;
        }
        
        $mediaItem = $this->media->getById($id);
        if (!$mediaItem) {
            $_SESSION['error'] = 'Media not found';
            header('Location: /admin/media');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
            return;
        }
        
        $folders = $this->mediaFolder->getAll();
        
        $data = [
            'media' => $mediaItem,
            'folders' => $folders,
            'title' => 'Edit Media',
            'page' => 'media'
        ];
        
        $this->render('admin/media/edit', $data);
    }
    
    /**
     * Handle media edit
     */
    private function handleEdit($id) {
        // CSRF protection
        if (!AuthMiddleware::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/media/edit?id=' . $id);
            exit;
        }
        
        $updateData = [
            'title' => trim($_POST['title'] ?? ''),
            'alt_text' => trim($_POST['alt_text'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'folder_id' => $_POST['folder_id'] ?? null,
            'is_public' => isset($_POST['is_public']) ? 1 : 0
        ];
        
        if ($this->media->update($id, $updateData)) {
            $_SESSION['success'] = 'Media updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update media';
        }
        
        header('Location: /admin/media');
        exit;
    }
    
    /**
     * Delete media
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/media');
            exit;
        }
        
        // CSRF protection
        if (!AuthMiddleware::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/media');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Media ID not provided';
            header('Location: /admin/media');
            exit;
        }
        
        if ($this->media->delete($id)) {
            $_SESSION['success'] = 'Media deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete media';
        }
        
        header('Location: /admin/media');
        exit;
    }
    
    /**
     * Manage folders
     */
    public function folders() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleFolderAction();
            return;
        }
        
        $folders = $this->mediaFolder->getTree();
        
        $data = [
            'folders' => $folders,
            'title' => 'Manage Folders',
            'page' => 'media'
        ];
        
        $this->render('admin/media/folders', $data);
    }
    
    /**
     * Handle folder actions (create, edit, delete)
     */
    private function handleFolderAction() {
        // CSRF protection
        if (!AuthMiddleware::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/media/folders');
            exit;
        }
        
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'create':
                $this->createFolder();
                break;
            case 'edit':
                $this->editFolder();
                break;
            case 'delete':
                $this->deleteFolder();
                break;
            default:
                $_SESSION['error'] = 'Invalid action';
        }
        
        header('Location: /admin/media/folders');
        exit;
    }
    
    /**
     * Create new folder
     */
    private function createFolder() {
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $_SESSION['error'] = 'Folder name is required';
            return;
        }
        
        $folderData = [
            'name' => $name,
            'description' => trim($_POST['description'] ?? ''),
            'parent_id' => $_POST['parent_id'] ?? null,
            'created_by' => $_SESSION['user_id']
        ];
        
        if ($this->mediaFolder->create($folderData)) {
            $_SESSION['success'] = 'Folder created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create folder';
        }
    }
    
    /**
     * Edit folder
     */
    private function editFolder() {
        $id = $_POST['folder_id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Folder ID not provided';
            return;
        }
        
        $updateData = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'parent_id' => $_POST['parent_id'] ?? null
        ];
        
        if ($this->mediaFolder->update($id, $updateData)) {
            $_SESSION['success'] = 'Folder updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update folder';
        }
    }
    
    /**
     * Delete folder
     */
    private function deleteFolder() {
        $id = $_POST['folder_id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Folder ID not provided';
            return;
        }
        
        $result = $this->mediaFolder->delete($id);
        
        if ($result === true) {
            $_SESSION['success'] = 'Folder deleted successfully';
        } elseif (is_array($result) && isset($result['error'])) {
            $_SESSION['error'] = $result['error'];
        } else {
            $_SESSION['error'] = 'Failed to delete folder';
        }
    }
    
    /**
     * AJAX endpoint for media picker
     */
    public function picker() {
        header('Content-Type: application/json');
        
        $type = $_GET['type'] ?? '';
        $search = $_GET['search'] ?? '';
        $folder = $_GET['folder'] ?? '';
        
        $filters = [
            'limit' => 20,
            'offset' => ($_GET['page'] ?? 1 - 1) * 20
        ];
        
        if ($type) {
            $filters['type'] = $type;
        }
        
        if ($search) {
            $filters['search'] = $search;
        }
        
        if ($folder) {
            $filters['folder_id'] = $folder;
        }
        
        $media = $this->media->getAll($filters);
        $folders = $this->mediaFolder->getAll();
        
        echo json_encode([
            'success' => true,
            'media' => $media,
            'folders' => $folders
        ]);
        exit;
    }
    
    /**
     * Download media file
     */
    public function download() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(404);
            exit;
        }
        
        $mediaItem = $this->media->getById($id);
        if (!$mediaItem) {
            http_response_code(404);
            exit;
        }
        
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $mediaItem['file_path'];
        if (!file_exists($filePath)) {
            http_response_code(404);
            exit;
        }
        
        // Increment download count
        $this->media->incrementDownloadCount($id);
        
        // Set headers for download
        header('Content-Type: ' . $mediaItem['mime_type']);
        header('Content-Disposition: attachment; filename="' . $mediaItem['original_name'] . '"');
        header('Content-Length: ' . $mediaItem['file_size']);
        
        readfile($filePath);
        exit;
    }
    
}
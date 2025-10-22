<?php

require_once __DIR__ . '/../../models/Layout.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../core/Controller.php';

class LayoutController extends Controller {
    private $layout;
    private $auth;
    
    public function __construct($language = 'en') {
        parent::__construct($language);
        $this->layout = new Layout();
        $this->auth = new AuthMiddleware();
    }
    
    /**
     * Display layouts list
     */
    public function index() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        $layouts = $this->layout->getAll(true); // Include inactive layouts
        
        // Get usage statistics for each layout
        foreach ($layouts as &$layout) {
            $stats = $this->layout->getUsageStats($layout['id']);
            $layout['page_count'] = $stats['page_count'];
        }
        
        $this->render('admin/layouts/index', [
            'layouts' => $layouts,
            'title' => 'Layout Management'
        ]);
    }
    
    /**
     * Show create layout form
     */
    public function create() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        $this->render('admin/layouts/create', [
            'title' => 'Create New Layout'
        ]);
    }
    
    /**
     * Show edit layout form
     */
    public function edit() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/layouts');
            exit;
        }
        
        $layout = $this->layout->getById($id);
        if (!$layout) {
            header('Location: /admin/layouts');
            exit;
        }
        
        // Don't allow editing system layouts
        if ($layout['is_system']) {
            $_SESSION['error'] = 'System layouts cannot be edited.';
            header('Location: /admin/layouts');
            exit;
        }
        
        $this->render('admin/layouts/edit', [
            'layout' => $layout,
            'title' => 'Edit Layout: ' . $layout['name']
        ]);
    }
    
    /**
     * Handle layout creation
     */
    public function store() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/layouts');
            exit;
        }
        
        $result = $this->validateAndSaveLayout();
        
        if ($result['success']) {
            $_SESSION['success'] = 'Layout created successfully!';
            header('Location: /admin/layouts');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /admin/layouts/create');
        }
        exit;
    }
    
    /**
     * Handle layout update
     */
    public function update() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/layouts');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/layouts');
            exit;
        }
        
        $result = $this->validateAndSaveLayout($id);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Layout updated successfully!';
            header('Location: /admin/layouts');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /admin/layouts/edit?id=' . $id);
        }
        exit;
    }
    
    /**
     * Handle layout deletion
     */
    public function delete() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/layouts');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/layouts');
            exit;
        }
        
        $layout = $this->layout->getById($id);
        if (!$layout) {
            $_SESSION['error'] = 'Layout not found.';
            header('Location: /admin/layouts');
            exit;
        }
        
        if ($layout['is_system']) {
            $_SESSION['error'] = 'System layouts cannot be deleted.';
            header('Location: /admin/layouts');
            exit;
        }
        
        if ($this->layout->delete($id)) {
            $_SESSION['success'] = 'Layout deleted successfully!';
        } else {
            $_SESSION['error'] = 'Cannot delete layout. It may be in use by existing pages.';
        }
        
        header('Location: /admin/layouts');
        exit;
    }
    
    /**
     * Duplicate layout
     */
    public function duplicate() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/layouts');
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /admin/layouts');
            exit;
        }
        
        $newId = $this->layout->duplicate($id);
        
        if ($newId) {
            $_SESSION['success'] = 'Layout duplicated successfully!';
            header('Location: /admin/layouts/edit?id=' . $newId);
        } else {
            $_SESSION['error'] = 'Failed to duplicate layout.';
            header('Location: /admin/layouts');
        }
        exit;
    }
    
    /**
     * Preview layout
     */
    public function preview() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/layouts');
            exit;
        }
        
        $layout = $this->layout->getById($id);
        if (!$layout) {
            header('Location: /admin/layouts');
            exit;
        }
        
        // Sample data for preview
        $sampleData = [
            'title' => 'Sample Page Title',
            'excerpt' => 'This is a sample excerpt to demonstrate how the layout will look with content.',
            'content' => '<p>This is sample content to show how your layout will render. You can customize the layout template to change how content is displayed.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
            'featured_image' => '/assets/img/sample-hero.jpg',
            'sections' => '<div class="sample-section"><h3>Sample Section</h3><p>This represents where page sections would appear.</p></div>'
        ];
        
        $rendered = $this->layout->render($id, $sampleData);
        
        $this->render('admin/layouts/preview', [
            'layout' => $layout,
            'rendered' => $rendered,
            'title' => 'Preview: ' . $layout['name']
        ]);
    }
    
    /**
     * Validate and save layout data
     */
    private function validateAndSaveLayout($id = null) {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $htmlTemplate = $_POST['html_template'] ?? '';
        $cssStyles = $_POST['css_styles'] ?? '';
        $jsScripts = $_POST['js_scripts'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        // Validation
        if (empty($name)) {
            return ['success' => false, 'message' => 'Layout name is required.'];
        }
        
        if (empty($htmlTemplate)) {
            return ['success' => false, 'message' => 'HTML template is required.'];
        }
        
        // Handle file upload for thumbnail
        $thumbnail = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleThumbnailUpload($_FILES['thumbnail']);
            if ($uploadResult['success']) {
                $thumbnail = $uploadResult['filename'];
            } else {
                return ['success' => false, 'message' => $uploadResult['message']];
            }
        } elseif ($id) {
            // Keep existing thumbnail if updating
            $existingLayout = $this->layout->getById($id);
            $thumbnail = $existingLayout['thumbnail'] ?? '';
        }
        
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'html_template' => $htmlTemplate,
            'css_styles' => $cssStyles,
            'js_scripts' => $jsScripts,
            'thumbnail' => $thumbnail,
            'is_active' => $isActive,
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        if ($id) {
            $success = $this->layout->update($id, $data);
        } else {
            $success = $this->layout->create($data);
        }
        
        if ($success) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Failed to save layout. Please try again.'];
        }
    }
    
    /**
     * Handle thumbnail upload
     */
    private function handleThumbnailUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Please upload a JPEG, PNG, GIF, or WebP image.'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File too large. Maximum size is 2MB.'];
        }
        
        $uploadDir = __DIR__ . '/../../../uploads/layouts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'layout_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'message' => 'Failed to upload file.'];
        }
    }
}

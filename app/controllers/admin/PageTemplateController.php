<?php

require_once __DIR__ . '/../../models/PageTemplate.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Helper.php';

class PageTemplateController extends Controller {
    private $pageTemplate;
    private $auth;
    
    public function __construct($language = 'en') {
        parent::__construct($language);
        $this->pageTemplate = new PageTemplate();
        $this->auth = new AuthMiddleware();
    }
    
    /**
     * Display page templates list
     */
    public function index() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        $templates = $this->pageTemplate->getAll(true); // Include inactive templates
        
        // Get usage statistics for each template
        foreach ($templates as &$template) {
            $stats = $this->pageTemplate->getUsageStats($template['id']);
            $template['page_count'] = $stats['page_count'];
        }
        
        $this->render('admin/page-templates/index', [
            'templates' => $templates,
            'title' => 'Page Template Management'
        ]);
    }
    
    /**
     * Show create template form
     */
    public function create() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        $this->render('admin/page-templates/create', [
            'title' => 'Create New Page Template'
        ]);
    }
    
    /**
     * Show edit template form
     */
    public function edit() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $template = $this->pageTemplate->getById($id);
        if (!$template) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        // Don't allow editing system templates
        if ($template['is_system']) {
            $_SESSION['error'] = 'System templates cannot be edited.';
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        // Extract variables from template
        $extractedVars = $this->pageTemplate->extractVariables(
            $template['html_template'], 
            $template['css_styles'], 
            $template['js_scripts']
        );
        
        $this->render('admin/page-templates/edit', [
            'template' => $template,
            'extracted_variables' => $extractedVars,
            'title' => 'Edit Template: ' . $template['name']
        ]);
    }
    
    /**
     * Handle template creation
     */
    public function store() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $result = $this->validateAndSaveTemplate();
        
        if ($result['success']) {
            $_SESSION['success'] = 'Page template created successfully!';
            header('Location: ' . admin_route('page-templates'));
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . admin_route('page-templates/create'));
        }
        exit;
    }
    
    /**
     * Handle template update
     */
    public function update() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $result = $this->validateAndSaveTemplate($id);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Page template updated successfully!';
            header('Location: ' . admin_route('page-templates'));
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . admin_route('page-templates/edit?id=' . $id));
        }
        exit;
    }
    
    /**
     * Handle template deletion
     */
    public function delete() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $template = $this->pageTemplate->getById($id);
        if (!$template) {
            $_SESSION['error'] = 'Template not found.';
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        if ($template['is_system']) {
            $_SESSION['error'] = 'System templates cannot be deleted.';
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        if ($this->pageTemplate->delete($id)) {
            $_SESSION['success'] = 'Template deleted successfully!';
        } else {
            $_SESSION['error'] = 'Cannot delete template. It may be in use by existing pages.';
        }
        
        header('Location: ' . admin_route('page-templates'));
        exit;
    }
    
    /**
     * Duplicate template
     */
    public function duplicate() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $newId = $this->pageTemplate->duplicate($id);
        
        if ($newId) {
            $_SESSION['success'] = 'Template duplicated successfully!';
            header('Location: ' . admin_route('page-templates/edit?id=' . $newId));
        } else {
            $_SESSION['error'] = 'Failed to duplicate template.';
            header('Location: ' . admin_route('page-templates'));
        }
        exit;
    }
    
    /**
     * Preview template
     */
    public function preview() {
        if (!$this->auth->isAuthenticated()) {
            header('Location: ' . admin_route('login'));
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        $template = $this->pageTemplate->getById($id);
        if (!$template) {
            header('Location: ' . admin_route('page-templates'));
            exit;
        }
        
        // Sample data for preview
        $sampleData = [
            'title' => 'Sample Page Title',
            'meta_description' => 'This is a sample meta description for SEO purposes.',
            'meta_keywords' => 'sample, page, template, preview',
            'content' => '<p>This is sample content to show how your template will render. You can customize the template to change how content is displayed.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p><h3>Sample Heading</h3><p>More sample content with different formatting to test the template styling.</p>',
            'excerpt' => 'This is a sample excerpt to demonstrate how the template will look with content.',
            'featured_image' => '/assets/img/sample-hero.jpg',
            'sections_html' => '<div class="sample-section"><h3>Sample Section</h3><p>This represents where page sections would appear if sections are enabled.</p></div>',
            'menu_pages' => '<li class="nav-item"><a class="nav-link" href="/">Home</a></li><li class="nav-item"><a class="nav-link" href="/about">About</a></li><li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>',
            'use_sections' => false,
            'language' => 'en',
            'current_language' => 'en',
            'current_year' => date('Y'),
            'site_name' => 'Your Website',
            'primary_color' => '#198754',
            'secondary_color' => '#20c997'
        ];
        
        // Merge template variables if available
        if (!empty($template['variables'])) {
            $templateVars = json_decode($template['variables'], true);
            if ($templateVars) {
                $sampleData = array_merge($sampleData, $templateVars);
            }
        }
        
        $rendered = $this->pageTemplate->render($id, $sampleData);
        
        $this->render('admin/page-templates/preview', [
            'template' => $template,
            'rendered' => $rendered,
            'sample_data' => $sampleData,
            'title' => 'Preview: ' . $template['name']
        ]);
    }
    
    /**
     * Extract variables from template (AJAX endpoint)
     */
    public function extractVariables() {
        if (!$this->auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        $htmlTemplate = $_POST['html_template'] ?? '';
        $cssStyles = $_POST['css_styles'] ?? '';
        $jsScripts = $_POST['js_scripts'] ?? '';
        
        $variables = $this->pageTemplate->extractVariables($htmlTemplate, $cssStyles, $jsScripts);
        
        header('Content-Type: application/json');
        echo json_encode(['variables' => $variables]);
        exit;
    }
    
    /**
     * Validate and save template data
     */
    private function validateAndSaveTemplate($id = null) {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $templateType = $_POST['template_type'] ?? 'page';
        $htmlTemplate = $_POST['html_template'] ?? '';
        $cssStyles = $_POST['css_styles'] ?? '';
        $jsScripts = $_POST['js_scripts'] ?? '';
        $variables = $_POST['variables'] ?? '{}';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        // Validation
        if (empty($name)) {
            return ['success' => false, 'message' => 'Template name is required.'];
        }
        
        if (empty($htmlTemplate)) {
            return ['success' => false, 'message' => 'HTML template is required.'];
        }
        
        // Validate JSON for variables
        if (!empty($variables)) {
            $decodedVars = json_decode($variables, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['success' => false, 'message' => 'Invalid JSON format for variables.'];
            }
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
            $existingTemplate = $this->pageTemplate->getById($id);
            $thumbnail = $existingTemplate['thumbnail'] ?? '';
        }
        
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'template_type' => $templateType,
            'html_template' => $htmlTemplate,
            'css_styles' => $cssStyles,
            'js_scripts' => $jsScripts,
            'variables' => $variables,
            'thumbnail' => $thumbnail,
            'is_active' => $isActive,
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        if ($id) {
            $success = $this->pageTemplate->update($id, $data);
        } else {
            $success = $this->pageTemplate->create($data);
        }
        
        if ($success) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Failed to save template. Please try again.'];
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
        
        $uploadDir = __DIR__ . '/../../../uploads/page-templates/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'template_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'message' => 'Failed to upload file.'];
        }
    }
}

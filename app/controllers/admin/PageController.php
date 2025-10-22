<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../../models/Layout.php';
require_once __DIR__ . '/../../models/PageSection.php';
require_once __DIR__ . '/../../models/PageTemplate.php';

/**
 * Page Controller for CMS functionality
 * 
 * Handles page management in admin panel
 */
class PageController extends Controller 
{
    private $page;
    private $layout;
    private $pageSection;
    private $pageTemplate;

    public function __construct($lang = 'en') 
    {
        parent::__construct($lang);
        $this->page = new Page();
        $this->layout = new Layout();
        $this->pageSection = new PageSection();
        $this->pageTemplate = new PageTemplate();
    }

    /**
     * List all pages
     */
    public function index() 
    {
        AuthMiddleware::requireAdmin();

        $currentPage = $_GET['page'] ?? 1;
        $perPage = 10;
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $language = $_GET['language'] ?? '';
        
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if (!empty($status)) {
            $filters['status'] = $status;
        }
        if (!empty($language)) {
            $filters['language'] = $language;
        }

        $pages = $this->page->getAll($filters, $currentPage, $perPage);
        $totalPages = ceil($this->page->getCount($filters) / $perPage);

        $this->render('admin.pages.index', [
            'pages' => $pages,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status,
            'language' => $language,
            'csrf_token' => AuthMiddleware::generateCSRFToken(),
            'page_title' => 'Page Management'
        ]);
    }

    /**
     * Show create page form
     */
    public function create() 
    {
        AuthMiddleware::requireAdmin();

        $layouts = $this->layout->getAll();
        $sectionTypes = $this->pageSection->getSectionTypes();

        $this->render('admin.pages.create', [
            'templates' => $this->page->getAvailableTemplates(),
            'page_templates' => $this->pageTemplate->getByType('page'),
            'layouts' => $layouts,
            'sectionTypes' => $sectionTypes,
            'csrf_token' => AuthMiddleware::generateCSRFToken(),
            'page_title' => 'Create New Page'
        ]);
    }

    /**
     * Store new page
     */
    public function store() 
    {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/pages');
            return;
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !AuthMiddleware::verifyCSRFToken($_POST['csrf_token'])) {
            $this->redirectWithError('/admin/pages/create', 'Invalid security token.');
            return;
        }

        $result = $this->validateAndSavePage();
        
        if ($result['success']) {
            $this->redirectWithSuccess('/admin/pages', 'Page created successfully.');
        } else {
            $this->redirectWithError('/admin/pages/create', $result['message']);
        }
    }

    /**
     * Show edit page form
     */
    public function edit() 
    {
        AuthMiddleware::requireAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id || !$this->page->findById($id)) {
            $this->redirectWithError('/admin/pages', 'Page not found.');
            return;
        }

        $layouts = $this->layout->getAll();
        $sectionTypes = $this->pageSection->getSectionTypes();

        $this->render('admin.pages.edit', [
            'page' => $this->page,
            'templates' => $this->page->getAvailableTemplates(),
            'layouts' => $layouts,
            'sectionTypes' => $sectionTypes,
            'csrf_token' => AuthMiddleware::generateCSRFToken(),
            'page_title' => 'Edit Page: ' . $this->page->title
        ]);
    }

    /**
     * Update page
     */
    public function update() 
    {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/pages');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id || !$this->page->findById($id)) {
            $this->redirectWithError('/admin/pages', 'Page not found.');
            return;
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !AuthMiddleware::verifyCSRFToken($_POST['csrf_token'])) {
            $this->redirectWithError('/admin/pages/edit?id=' . $id, 'Invalid security token.');
            return;
        }

        $result = $this->validateAndSavePage($id);
        
        if ($result['success']) {
            $this->redirectWithSuccess('/admin/pages', 'Page updated successfully.');
        } else {
            $this->redirectWithError('/admin/pages/edit?id=' . $id, $result['message']);
        }
    }

    /**
     * Delete page
     */
    public function delete() 
    {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/pages');
            return;
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !AuthMiddleware::verifyCSRFToken($_POST['csrf_token'])) {
            $this->redirectWithError('/admin/pages', 'Invalid security token.');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirectWithError('/admin/pages', 'Invalid page ID.');
            return;
        }

        if ($this->page->delete($id)) {
            $this->redirectWithSuccess('/admin/pages', 'Page deleted successfully.');
        } else {
            $this->redirectWithError('/admin/pages', 'Failed to delete page.');
        }
    }

    /**
     * Preview page
     */
    public function preview() 
    {
        AuthMiddleware::requireAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id || !$this->page->findById($id)) {
            $this->redirectWithError('/admin/pages', 'Page not found.');
            return;
        }

        // Load sections if page uses sections
        $sections = [];
        $sectionsHtml = '';
        if ($this->page->use_sections) {
            $sections = $this->pageSection->getByPageId($id, true); // Only active sections
            $sectionsHtml = $this->renderSectionsForPreview($sections);
        }

        $this->render('admin.pages.preview', [
            'page' => $this->page,
            'sections' => $sections,
            'sections_html' => $sectionsHtml,
            'page_title' => 'Preview: ' . $this->page->title
        ]);
    }

    /**
     * Render sections for preview (simplified version)
     */
    private function renderSectionsForPreview($sections) {
        if (empty($sections)) {
            return '';
        }
        
        $html = '';
        foreach ($sections as $section) {
            $html .= '<div class="preview-section mb-8 p-6 bg-white rounded-lg shadow">';
            $html .= '<div class="preview-section-header mb-4">';
            $html .= '<span class="inline-block px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">' . ucfirst($section['section_type']) . '</span>';
            $html .= '</div>';
            
            if (!empty($section['title'])) {
                $html .= '<h3 class="text-xl font-semibold mb-3">' . htmlspecialchars($section['title']) . '</h3>';
            }
            
            if (!empty($section['content'])) {
                $html .= '<div class="section-content">' . $section['content'] . '</div>';
            }
            
            $html .= '</div>';
        }
        
        return $html;
    }

    /**
     * Validate and save page data
     */
    private function validateAndSavePage($id = null) 
    {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $excerpt = trim($_POST['excerpt'] ?? '');
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');
        $metaKeywords = trim($_POST['meta_keywords'] ?? '');
        $template = $_POST['template'] ?? 'default';
        $layoutId = !empty($_POST['layout_id']) ? $_POST['layout_id'] : null;
        $useSections = isset($_POST['use_sections']) ? 1 : 0;
        $status = $_POST['status'] ?? 'draft';
        $language = $_POST['language'] ?? 'en';
        $translationGroup = trim($_POST['translation_group'] ?? '');
        $parentId = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
        $menuOrder = intval($_POST['menu_order'] ?? 0);
        $isHomepage = isset($_POST['is_homepage']) ? 1 : 0;
        $showInMenu = isset($_POST['show_in_menu']) ? 1 : 0;

        // Validation
        if (empty($title)) {
            return ['success' => false, 'message' => 'Title is required.'];
        }

        if (empty($slug)) {
            $slug = $this->page->generateSlug($title, $id, $language);
        } else {
            // Check if slug is unique for this language
            if ($this->page->slugExists($slug, $id, $language)) {
                return ['success' => false, 'message' => 'Slug already exists for this language. Please choose a different one.'];
            }
        }
        
        // Generate translation group if empty
        if (empty($translationGroup)) {
            $translationGroup = $this->page->generateTranslationGroup($title);
        }

        // Get current user
        $currentUser = AuthMiddleware::getCurrentUser();
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $metaKeywords,
            'template' => $template,
            'layout_id' => $layoutId,
            'use_sections' => $useSections,
            'status' => $status,
            'language' => $language,
            'translation_group' => $translationGroup,
            'author_id' => $currentUser->id,
            'parent_id' => $parentId,
            'menu_order' => $menuOrder,
            'is_homepage' => $isHomepage,
            'show_in_menu' => $showInMenu,
            'published_at' => null
        ];

        // Handle file upload for featured image
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->upload('uploads/pages', $_FILES['featured_image']);
            if ($uploadResult->success) {
                $data['featured_image'] = $uploadResult->filename;
            }
        }

        if ($id) {
            // Update existing page
            if ($this->page->update($id, $data)) {
                return ['success' => true, 'message' => 'Page updated successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to update page.'];
            }
        } else {
            // Create new page
            $pageId = $this->page->create($data);
            if ($pageId) {
                return ['success' => true, 'message' => 'Page created successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to create page.'];
            }
        }
    }

    /**
     * Redirect with success message
     */
    private function redirectWithSuccess($url, $message) 
    {
        session_start();
        $_SESSION['success_message'] = $message;
        header("Location: {$url}");
        exit();
    }

    /**
     * Redirect with error message
     */
    private function redirectWithError($url, $message) 
    {
        session_start();
        $_SESSION['error_message'] = $message;
        header("Location: {$url}");
        exit();
    }

    /**
     * Redirect helper
     */
    private function redirect($url) 
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Manage page sections
     */
    public function sections() 
    {
        AuthMiddleware::requireAdmin();
        
        $pageId = $_GET['page_id'] ?? null;
        if (!$pageId) {
            $this->redirectWithError('/admin/pages', 'Page ID is required.');
        }
        
        $page = (object) $this->page->findById($pageId);
        if (!$page) {
            $this->redirectWithError('/admin/pages', 'Page not found.');
        }
        
        $sections = $this->pageSection->getByPageId($pageId, false);
        $sectionTypes = $this->pageSection->getSectionTypes();
        $this->render('admin.pages.sections', [
            'page' => $page,
            'sections' => $sections,
            'sectionTypes' => $sectionTypes,
            'csrf_token' => AuthMiddleware::generateCSRFToken(),
            'page_title' => 'Manage Sections: ' . $page->title
        ]);
    }

    /**
     * Add new section to page
     */
    public function addSection() 
    {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('/admin/pages', 'Invalid request method.');
        }
        
        $pageId = $_POST['page_id'] ?? null;
        $sectionType = $_POST['section_type'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $settings = $_POST['settings'] ?? [];
        
        if (!$pageId || !$sectionType) {
            $this->redirectWithError("/admin/pages/sections?page_id={$pageId}", 'Page ID and section type are required.');
        }
        
        $data = [
            'page_id' => $pageId,
            'section_type' => $sectionType,
            'title' => $title,
            'content' => $content,
            'settings' => $settings,
            'is_active' => 1
        ];
        
        $sectionId = $this->pageSection->create($data);
        
        if ($sectionId) {
            $_SESSION['success_message'] = 'Section added successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to add section.';
        }
        
        $this->redirect("/admin/pages/sections?page_id={$pageId}");
    }

    /**
     * Update section order
     */
    public function updateSectionOrder() 
    {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $sections = json_decode(file_get_contents('php://input'), true);
        
        if (!$sections) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }
        
        $success = $this->pageSection->updateSortOrder($sections);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }

    /**
     * Delete section
     */
    public function deleteSection() 
    {
        AuthMiddleware::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('/admin/pages', 'Invalid request method.');
        }
        
        $sectionId = $_POST['section_id'] ?? null;
        $pageId = $_POST['page_id'] ?? null;
        
        if (!$sectionId || !$pageId) {
            $this->redirectWithError("/admin/pages/sections?page_id={$pageId}", 'Section ID is required.');
        }
        
        $success = $this->pageSection->delete($sectionId);
        
        if ($success) {
            $_SESSION['success_message'] = 'Section deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete section.';
        }
        
        $this->redirect("/admin/pages/sections?page_id={$pageId}");
    }

    /**
     * Section builder with CodeMirror
     */
    public function sectionBuilder() 
    {
        AuthMiddleware::requireAdmin();
        
        $pageId = $_GET['page_id'] ?? null;
        
        if (!$pageId) {
            $this->redirectWithError('/admin/pages', 'Page ID is required.');
        }
        
        $page = $this->page->findById($pageId);
        
        if (!$page) {
            $this->redirectWithError('/admin/pages', 'Page not found.');
        }
        
        $sections = $this->pageSection->getByPageId($pageId, false);
        $sectionTypes = $this->pageSection->getSectionTypes();
        
        // Get section layout templates
        $sectionLayoutTemplates = $this->getSectionLayoutTemplates();
        
        $this->render('admin.pages.section-builder', [
            'page' => $page,
            'sections' => $sections,
            'sectionTypes' => $sectionTypes,
            'sectionLayoutTemplates' => $sectionLayoutTemplates,
            'csrf_token' => AuthMiddleware::generateCSRFToken(),
            'page_title' => 'Section Builder: ' . $page->title
        ]);
    }

/**
 * Get section layout templates
 */
private function getSectionLayoutTemplates() 
{
    return $this->pageSection->getSectionLayoutTemplates();
}

/**
 * Update section (AJAX endpoint)
 */
public function updateSection() 
{
    AuthMiddleware::requireAdmin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $sectionId = $input['id'];
    $data = [
        'section_type' => $input['section_type'] ?? 'custom',
        'title' => $input['title'] ?? '',
        'content' => $input['content'] ?? '',
        'section_html' => $input['section_html'] ?? '',
        'section_css' => $input['section_css'] ?? '',
        'section_js' => $input['section_js'] ?? '',
        'layout_template' => $input['layout_template'] ?? 'custom',
        'settings' => $input['settings'] ?? [],
        'sort_order' => $input['sort_order'] ?? 0,
        'is_active' => $input['is_active'] ?? 1
    ];
    
    $success = $this->pageSection->update($sectionId, $data);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
}

/**
 * Add section (AJAX endpoint)
 */
public function addSectionAjax() 
{
    AuthMiddleware::requireAdmin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['page_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $data = [
        'page_id' => $input['page_id'],
        'section_type' => $input['section_type'] ?? 'custom',
        'title' => $input['title'] ?? '',
        'content' => $input['content'] ?? '',
        'section_html' => $input['section_html'] ?? '',
        'section_css' => $input['section_css'] ?? '',
        'section_js' => $input['section_js'] ?? '',
        'layout_template' => $input['layout_template'] ?? 'custom',
        'settings' => $input['settings'] ?? [],
        'is_active' => 1
    ];
    
    $sectionId = $this->pageSection->create($data);
    
    header('Content-Type: application/json');
    if ($sectionId) {
        echo json_encode(['success' => true, 'section_id' => $sectionId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create section']);
    }
}

/**
 * Delete section (AJAX endpoint)
 */
public function deleteSectionAjax() 
{
    AuthMiddleware::requireAdmin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['section_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $success = $this->pageSection->delete($input['section_id']);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
}

/**
 * Get section templates (API endpoint)
 */
public function getSectionTemplatesApi() 
{
    AuthMiddleware::requireAdmin();
    
    $templates = $this->pageSection->getSectionLayoutTemplates();
    
    header('Content-Type: application/json');
    echo json_encode($templates);
}
}
<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../models/Page.php';

/**
 * Page Controller for CMS functionality
 * 
 * Handles page management in admin panel
 */
class PageController extends Controller 
{
    private $page;

    public function __construct($lang = 'en') 
    {
        parent::__construct($lang);
        $this->page = new Page();
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
        
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if (!empty($status)) {
            $filters['status'] = $status;
        }

        $pages = $this->page->getAll($filters, $currentPage, $perPage);
        $totalPages = ceil($this->page->getCount($filters) / $perPage);

        $this->render('admin.pages.index', [
            'pages' => $pages,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status,
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

        $this->render('admin.pages.create', [
            'templates' => $this->page->getAvailableTemplates(),
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

        $this->render('admin.pages.edit', [
            'page' => $this->page,
            'templates' => $this->page->getAvailableTemplates(),
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

        $this->render('admin.pages.preview', [
            'page' => $this->page,
            'page_title' => 'Preview: ' . $this->page->title
        ]);
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
        $status = $_POST['status'] ?? 'draft';
        $parentId = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
        $menuOrder = intval($_POST['menu_order'] ?? 0);
        $isHomepage = isset($_POST['is_homepage']) ? 1 : 0;
        $showInMenu = isset($_POST['show_in_menu']) ? 1 : 0;

        // Validation
        if (empty($title)) {
            return ['success' => false, 'message' => 'Title is required.'];
        }

        if (empty($slug)) {
            $slug = $this->page->generateSlug($title, $id);
        } else {
            // Check if slug is unique
            if ($this->page->slugExists($slug, $id)) {
                return ['success' => false, 'message' => 'Slug already exists. Please choose a different one.'];
            }
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
            'status' => $status,
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
}
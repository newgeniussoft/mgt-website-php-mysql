<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Template;
use App\Models\Section;

class PageController extends Controller {
    
    /**
     * Display list of pages
     */
    public function index() {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        
        if ($search) {
            $pages = Page::search($search, $status ?: null);
            $total = count($pages);
            $pages = array_slice($pages, ($page - 1) * $perPage, $perPage);
        } else {
            $pages = Page::paginate($page, $perPage, $status ?: null);
            $total = Page::count($status ?: null);
        }
        
        $totalPages = ceil($total / $perPage);
        
        return view('admin.pages.index', [
            'title' => 'Pages',
            'pages' => $pages,
            'search' => $search,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }
    
    /**
     * Show create page form
     */
    public function create() {
        $templates = Template::getActive();
        $pages = Page::all(); // For parent selection
        
        return view('admin.pages.create', [
            'title' => 'Create Page',
            'templates' => $templates,
            'pages' => $pages
        ]);
    }
    
    /**
     * Store new page
     */
    public function store() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages/create'));
        }
        
        // Validate required fields
        if (empty($_POST['title'])) {
            $_SESSION['error'] = 'Title is required';
            return redirect(admin_url('pages/create'));
        }
        
        // Generate slug
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : Page::generateSlug($_POST['title']);
        
        // Prepare data
        $data = [
            'template_id' => $_POST['template_id'] ?? null,
            'title' => $_POST['title'],
            'slug' => $slug,
            'meta_title' => $_POST['meta_title'] ?? $_POST['title'],
            'meta_description' => $_POST['meta_description'] ?? '',
            'meta_keywords' => $_POST['meta_keywords'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'is_homepage' => isset($_POST['is_homepage']) ? 1 : 0,
            'show_in_menu' => isset($_POST['show_in_menu']) ? 1 : 0,
            'menu_order' => $_POST['menu_order'] ?? 0,
            'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
            'author_id' => $_SESSION['user_id'] ?? 1
        ];
        
        // Handle featured image upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/pages/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = $_FILES['featured_image']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                $data['featured_image'] = '/uploads/pages/' . $fileName;
            }
        }
        
        // Set published_at if status is published
        if ($data['status'] === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        // If set as homepage, unset other homepages
        if ($data['is_homepage']) {
            $conn = Page::find(1)->getConnection();
            $conn->prepare("UPDATE pages SET is_homepage = 0")->execute();
        }
        
        try {
            $page = Page::create($data);
            $_SESSION['success'] = 'Page created successfully';
            return redirect(admin_url('pages/edit?id=' . $page->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating page: ' . $e->getMessage();
            return redirect(admin_url('pages/create'));
        }
    }
    
    /**
     * Show edit page form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        $page = Page::find($id);
        
        if (!$page) {
            $_SESSION['error'] = 'Page not found';
            return redirect(admin_url('pages'));
        }
        
        $templates = Template::getActive();
        $pages = Page::all();
        $sections = Section::getByPage($id, false);
        
        return view('admin.pages.edit', [
            'title' => 'Edit Page',
            'page' => $page,
            'templates' => $templates,
            'pages' => $pages,
            'sections' => $sections
        ]);
    }
    
    /**
     * Update page
     */
    public function update() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        $page = Page::find($id);
        
        if (!$page) {
            $_SESSION['error'] = 'Page not found';
            return redirect(admin_url('pages'));
        }
        
        // Validate required fields
        if (empty($_POST['title'])) {
            $_SESSION['error'] = 'Title is required';
            return redirect(admin_url('pages/edit?id=' . $id));
        }
        
        // Generate slug if changed
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : Page::generateSlug($_POST['title'], $id);
        
        // Update data
        $page->template_id = $_POST['template_id'] ?? null;
        $page->title = $_POST['title'];
        $page->slug = $slug;
        $page->meta_title = $_POST['meta_title'] ?? $_POST['title'];
        $page->meta_description = $_POST['meta_description'] ?? '';
        $page->meta_keywords = $_POST['meta_keywords'] ?? '';
        $page->status = $_POST['status'] ?? 'draft';
        $page->is_homepage = isset($_POST['is_homepage']) ? 1 : 0;
        $page->show_in_menu = isset($_POST['show_in_menu']) ? 1 : 0;
        $page->menu_order = $_POST['menu_order'] ?? 0;
        $page->parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
        
        // Handle featured image upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/pages/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = $_FILES['featured_image']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                // Delete old image
                if ($page->featured_image) {
                    $oldPath = __DIR__ . '/../../../public' . $page->featured_image;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $page->featured_image = '/uploads/pages/' . $fileName;
            }
        }
        
        // Set published_at if status changed to published
        if ($page->status === 'published' && !$page->published_at) {
            $page->published_at = date('Y-m-d H:i:s');
        }
        
        // If set as homepage, unset other homepages
        if ($page->is_homepage) {
            $conn = $page->getConnection();
            $conn->prepare("UPDATE pages SET is_homepage = 0 WHERE id != ?")->execute([$id]);
        }
        
        try {
            $page->save();
            $_SESSION['success'] = 'Page updated successfully';
            return redirect(admin_url('pages/edit?id=' . $id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating page: ' . $e->getMessage();
            return redirect(admin_url('pages/edit?id=' . $id));
        }
    }
    
    /**
     * Delete page
     */
    public function destroy() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('pages'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        $page = Page::find($id);
        
        if (!$page) {
            $_SESSION['error'] = 'Page not found';
            return redirect(admin_url('pages'));
        }
        
        // Delete featured image
        if ($page->featured_image) {
            $imagePath = __DIR__ . '/../../../public' . $page->featured_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        try {
            $page->delete();
            $_SESSION['success'] = 'Page deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting page: ' . $e->getMessage();
        }
        
        return redirect(admin_url('pages'));
    }
    
    /**
     * Preview page
     */
    public function preview() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Page ID is required';
            return redirect(admin_url('pages'));
        }
        
        $page = Page::find($id);
        
        if (!$page) {
            $_SESSION['error'] = 'Page not found';
            return redirect(admin_url('pages'));
        }
        
        $template = $page->getTemplate();
        $sections = $page->getSections();
        
        return view('admin.pages.preview', [
            'title' => 'Preview: ' . $page->title,
            'page' => $page,
            'template' => $template,
            'sections' => $sections
        ]);
    }
}

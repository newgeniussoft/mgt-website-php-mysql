<?php

namespace App\Http\Controllers;

use App\Models\Template;

class TemplateController extends Controller {
    
    /**
     * Display list of templates
     */
    public function index() {
        $templates = Template::all();
        
        return view('admin.templates.index', [
            'title' => 'Templates',
            'templates' => $templates
        ]);
    }
    
    /**
     * Show create template form
     */
    public function create() {
        
        $templates = Template::all();
        return view('admin.templates.edit', [
            'title' => 'Create Template',
            'templates' => $templates
        ]);
    }
    
    /**
     * Store new template
     */
    public function store() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('templates/create'));
        }
        
        // Validate required fields
        if (empty($_POST['name'])) {
            $_SESSION['error'] = 'Template name is required';
            return redirect(admin_url('templates/create'));
        }
        
        // Generate slug
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : Template::generateSlug($_POST['name']);
        
        // Prepare data
        $data = [
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'] ?? '',
            'html_content' => $_POST['html_content'] ?? '',
            'css_content' => $_POST['css_content'] ?? '',
            'js_content' => $_POST['js_content'] ?? '',
            'is_default' => isset($_POST['is_default']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'active',
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/templates/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                $data['thumbnail'] = '/uploads/templates/' . $fileName;
            }
        }
        
        // If set as default, unset other defaults
        if ($data['is_default']) {
            $conn = Template::find(1)->getConnection();
            $conn->prepare("UPDATE templates SET is_default = 0")->execute();
        }
        
        try {
            $template = Template::create($data);
            $_SESSION['success'] = 'Template created successfully';
            return redirect(admin_url('templates/edit?id=' . $template->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating template: ' . $e->getMessage();
            return redirect(admin_url('templates/create'));
        }
    }
    
    /**
     * Show edit template form
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Template ID is required';
            return redirect(admin_url('templates'));
        }
        
        $template = Template::find($id);
        $templates = Template::all();
        
        if (!$template) {
            $_SESSION['error'] = 'Template not found';
            return redirect(admin_url('templates'));
        }
        
        return view('admin.templates.edit', [
            'title' => 'Edit Template',
            'template' => $template,
            'templates' => $templates
        ]);
    }
    
    /**
     * Update template
     */
    public function update() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('templates'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Template ID is required';
            return redirect(admin_url('templates'));
        }
        
        $template = Template::find($id);
        
        if (!$template) {
            $_SESSION['error'] = 'Template not found';
            return redirect(admin_url('templates'));
        }
        
        // Validate required fields
        if (empty($_POST['name'])) {
            $_SESSION['error'] = 'Template name is required';
            return redirect(admin_url('templates/edit?id=' . $id));
        }
        
        // Generate slug if changed
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : Template::generateSlug($_POST['name'], $id);
        
        // Update data
        $template->name = $_POST['name'];
        $template->slug = $slug;
        $template->description = $_POST['description'] ?? '';
        $template->html_content = $_POST['html_content'] ?? '';
        $template->css_content = $_POST['css_content'] ?? '';
        $template->js_content = $_POST['js_content'] ?? '';
        $template->is_default = isset($_POST['is_default']) ? 1 : 0;
        $template->status = $_POST['status'] ?? 'active';
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/templates/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['thumbnail']['name']);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                // Delete old thumbnail
                if ($template->thumbnail) {
                    $oldPath = __DIR__ . '/../../../public' . $template->thumbnail;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $template->thumbnail = '/uploads/templates/' . $fileName;
            }
        }
        if (isset($_POST['thumbnail_removed']) && $_POST['thumbnail_removed'] === '1') {
            $template->thumbnail = null;
        }
        
        // If set as default, unset other defaults
        if ($template->is_default) {
            $conn = $template->getConnection();
            $conn->prepare("UPDATE templates SET is_default = 0 WHERE id != ?")->execute([$id]);
        }
        
        try {
            $template->save();
            $_SESSION['success'] = 'Template updated successfully';
            return redirect(admin_url('templates/edit?id=' . $id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating template: ' . $e->getMessage();
            return redirect(admin_url('templates/edit?id=' . $id));
        }
    }
    
    /**
     * Delete template
     */
    public function destroy() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('templates'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Template ID is required';
            return redirect(admin_url('templates'));
        }
        
        $template = Template::find($id);
        
        if (!$template) {
            $_SESSION['error'] = 'Template not found';
            return redirect(admin_url('templates'));
        }
        
        // Check if template is in use
        $pagesCount = $template->countPages();
        if ($pagesCount > 0) {
            $_SESSION['error'] = "Cannot delete template. It is used by $pagesCount page(s).";
            return redirect(admin_url('templates'));
        }
        
        // Delete thumbnail
        if ($template->thumbnail) {
            $thumbnailPath = __DIR__ . '/../../../public' . $template->thumbnail;
            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }
        }
        
        try {
            $template->delete();
            $_SESSION['success'] = 'Template deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting template: ' . $e->getMessage();
        }
        
        return redirect(admin_url('templates'));
    }
    
    /**
     * Preview template
     */
    public function preview() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Template ID is required';
            return redirect(admin_url('templates'));
        }
        
        $template = Template::find($id);
        
        if (!$template) {
            $_SESSION['error'] = 'Template not found';
            return redirect(admin_url('templates'));
        }
        
        // Sample data for preview
        $variables = [
            'page_title' => 'Sample Page Title',
            'meta_description' => 'This is a sample meta description',
            'meta_keywords' => 'sample, keywords, preview',
            'site_name' => 'My Website',
            'menu_items' => '<li><a href="#">Home</a></li><li><a href="#">About</a></li><li><a href="#">Contact</a></li>',
            'page_sections' => '<div class="section"><h2>Sample Section</h2><p>This is sample content for the preview.</p></div>',
            'custom_css' => '',
            'custom_js' => ''
        ];
        
        $html = $template->render($variables);
        
        return view('admin.templates.preview', [
            'title' => 'Preview: ' . $template->name,
            'template' => $template,
            'html' => $html
        ]);
    }
    
    /**
     * Duplicate template
     */
    public function duplicate() {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('templates'));
        }
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $_SESSION['error'] = 'Template ID is required';
            return redirect(admin_url('templates'));
        }
        
        $template = Template::find($id);
        
        if (!$template) {
            $_SESSION['error'] = 'Template not found';
            return redirect(admin_url('templates'));
        }
        
        // Create duplicate
        $data = [
            'name' => $template->name . ' (Copy)',
            'slug' => Template::generateSlug($template->name . '-copy'),
            'description' => $template->description,
            'html_content' => $template->html_content,
            'css_content' => $template->css_content,
            'js_content' => $template->js_content,
            'is_default' => 0,
            'status' => 'inactive',
            'created_by' => $_SESSION['user_id'] ?? 1
        ];
        
        try {
            $newTemplate = Template::create($data);
            $_SESSION['success'] = 'Template duplicated successfully';
            return redirect(admin_url('templates/edit?id=' . $newTemplate->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error duplicating template: ' . $e->getMessage();
            return redirect(admin_url('templates'));
        }
    }
}

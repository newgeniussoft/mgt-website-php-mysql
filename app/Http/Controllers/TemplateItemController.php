<?php

namespace App\Http\Controllers;

use App\Models\TemplateItem;
use App\Http\Controllers\Controller;

class TemplateItemController extends Controller {
    
    /**
     * Display list of template items
     */
    public function index() {
        $search = $_GET['search'] ?? '';
        $modelFilter = $_GET['model'] ?? '';
        $statusFilter = $_GET['status'] ?? '';
        
        // Build SQL manually to match our lightweight Model API
        $pdo = (new TemplateItem())->getConnection();
        $sql = "SELECT * FROM template_items WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (`name` LIKE ? OR `description` LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($modelFilter)) {
            $sql .= " AND `model_name` = ?";
            $params[] = $modelFilter;
        }
        
        if (!empty($statusFilter)) {
            $sql .= " AND `status` = ?";
            $params[] = $statusFilter;
        }
        
        $sql .= " ORDER BY `created_at` DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $templates = $stmt->fetchAll(\PDO::FETCH_CLASS, TemplateItem::class);
        
        // Distinct model names
        $modelsStmt = $pdo->query("SELECT DISTINCT `model_name` FROM template_items WHERE `model_name` IS NOT NULL AND `model_name` <> '' ORDER BY `model_name`");
        $models = $modelsStmt->fetchAll(\PDO::FETCH_COLUMN);
        
        return view('admin.template-items.index', [
            'templates' => $templates,
            'models' => $models,
            'search' => $search,
            'modelFilter' => $modelFilter,
            'statusFilter' => $statusFilter
        ]);
    }
    
    /**
     * Show create form
     */
    public function create() {
        $availableModels = [
            'media' => 'Media',
            'post' => 'Blog Posts',
            'page' => 'Pages',
            'tour' => 'Tours',
            'gallery' => 'Galleries',
            'user' => 'Users'
        ];
        
        return view('admin.template-items.create', [
            'availableModels' => $availableModels
        ]);
    }
    
    /**
     * Store new template item
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            redirect(admin_url('template-items/create'));
            return;
        }
        
        if (empty($_POST['name']) || empty($_POST['model_name']) || empty($_POST['html_template'])) {
            $_SESSION['error'] = 'Name, Model, and HTML Template are required';
            redirect(admin_url('template-items/create'));
            return;
        }
        
        $template = new TemplateItem();
        $template->name = trim($_POST['name']);
        $template->slug = TemplateItem::generateSlug($_POST['name']);
        $template->description = trim($_POST['description'] ?? '');
        $template->model_name = trim($_POST['model_name']);
        $template->html_template = $_POST['html_template'];
        $template->css_styles = $_POST['css_styles'] ?? '';
        $template->js_code = $_POST['js_code'] ?? '';
        $template->default_keys = trim($_POST['default_keys'] ?? '');
        $template->is_default = isset($_POST['is_default']) ? 1 : 0;
        $template->status = $_POST['status'] ?? 'active';
        
        // Process variables
        $this->processVariables($template);
        
        // Handle thumbnail
        $this->handleThumbnailUpload($template);
        
        if ($template->is_default) {
            // Clear other defaults for this model
            $pdo = (new TemplateItem())->getConnection();
            $stmt = $pdo->prepare("UPDATE `template_items` SET `is_default` = 0 WHERE `model_name` = ?");
            $stmt->execute([$template->model_name]);
        }
        
        if ($template->save()) {
            $_SESSION['success'] = 'Template item created successfully';
            redirect(admin_url('template-items'));
        } else {
            $_SESSION['error'] = 'Failed to create template item';
            redirect(admin_url('template-items/create'));
        }
    }
    
    /**
     * Show edit form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $template = TemplateItem::find($id);
        
        if (!$template) {
            $_SESSION['error'] = 'Template item not found';
            redirect(admin_url('template-items'));
            return;
        }
        
        $availableModels = [
            'media' => 'Media',
            'post' => 'Blog Posts',
            'page' => 'Pages',
            'tour' => 'Tours',
            'gallery' => 'Galleries',
            'user' => 'Users'
        ];
        
        return view('admin.template-items.edit', [
            'template' => $template,
            'availableModels' => $availableModels,
            'variables' => $template->getVariablesArray()
        ]);
    }
    
    /**
     * Update template item
     */
    public function update() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            redirect(admin_url('template-items'));
            return;
        }
        
        $id = $_POST['id'] ?? 0;
        $template = TemplateItem::find($id);
        
        if (!$template) {
            $_SESSION['error'] = 'Template item not found';
            redirect(admin_url('template-items'));
            return;
        }
        
        $template->name = trim($_POST['name']);
        $template->slug = TemplateItem::generateSlug($_POST['name'], $id);
        $template->description = trim($_POST['description'] ?? '');
        $template->model_name = trim($_POST['model_name']);
        $template->html_template = $_POST['html_template'];
        $template->css_styles = $_POST['css_styles'] ?? '';
        $template->js_code = $_POST['js_code'] ?? '';
        $template->default_keys = trim($_POST['default_keys'] ?? '');
        $template->is_default = isset($_POST['is_default']) ? 1 : 0;
        $template->status = $_POST['status'] ?? 'active';
        
        $this->processVariables($template);
        $this->handleThumbnailUpload($template, true);
        
        if ($template->is_default) {
            // Clear other defaults for this model, excluding current record
            $pdo = (new TemplateItem())->getConnection();
            $stmt = $pdo->prepare("UPDATE `template_items` SET `is_default` = 0 WHERE `model_name` = ? AND `id` != ?");
            $stmt->execute([$template->model_name, $id]);
        }
        
        if ($template->save()) {
            $_SESSION['success'] = 'Template item updated successfully';
            redirect(admin_url('template-items'));
        } else {
            $_SESSION['error'] = 'Failed to update template item';
            redirect(admin_url('template-items/edit?id=' . $id));
        }
    }
    
    /**
     * Delete template item
     */
    public function delete() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            redirect(admin_url('template-items'));
            return;
        }
        
        $id = $_POST['id'] ?? 0;
        $template = TemplateItem::find($id);
        
        if ($template && $template->delete()) {
            $_SESSION['success'] = 'Template item deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete template item';
        }
        
        redirect(admin_url('template-items'));
    }
    
    /**
     * Duplicate template item
     */
    public function duplicate() {
        $id = $_GET['id'] ?? 0;
        $template = TemplateItem::find($id);
        
        if ($template) {
            $newTemplate = $template->duplicate();
            $_SESSION['success'] = 'Template item duplicated successfully';
            redirect(admin_url('template-items/edit?id=' . $newTemplate->id));
        } else {
            $_SESSION['error'] = 'Template item not found';
            redirect(admin_url('template-items'));
        }
    }
    
    /**
     * Preview template item
     */
    public function preview() {
        $id = $_GET['id'] ?? 0;
        $template = TemplateItem::find($id);
        
        if (!$template) {
            return 'Template not found';
        }
        
        $sampleData = (object)[
            'id' => 1,
            'name' => 'Sample Item',
            'title' => 'Sample Title',
            'description' => 'This is a sample description',
            'image' => '/public/images/logos/logo.png',
            'url' => '#',
            'price' => '99.99'
        ];
        
        echo $template->render($sampleData);
    }
    
    /**
     * Extract variables from HTML template (AJAX)
     */
    public function extractVariables() {
        header('Content-Type: application/json');
        
        $html = $_POST['html'] ?? '';
        $pattern = '/\{\{\s*\$item\.([a-zA-Z0-9_]+)\s*\}\}/i';
        preg_match_all($pattern, $html, $matches);
        
        $variables = [];
        if (!empty($matches[1])) {
            foreach (array_unique($matches[1]) as $var) {
                $variables[] = [
                    'key' => $var,
                    'label' => ucwords(str_replace('_', ' ', $var)),
                    'type' => 'text',
                    'default' => ''
                ];
            }
        }
        
        echo json_encode(['success' => true, 'variables' => $variables]);
        exit;
    }
    
    /**
     * Process variables from POST data
     */
    private function processVariables($template) {
        if (isset($_POST['var_key'])) {
            $variables = [];
            $varKeys = $_POST['var_key'] ?? [];
            $varLabels = $_POST['var_label'] ?? [];
            $varTypes = $_POST['var_type'] ?? [];
            $varDefaults = $_POST['var_default'] ?? [];
            
            foreach ($varKeys as $index => $key) {
                if (!empty($key)) {
                    $variables[] = [
                        'key' => trim($key),
                        'label' => trim($varLabels[$index] ?? ''),
                        'type' => trim($varTypes[$index] ?? 'text'),
                        'default' => trim($varDefaults[$index] ?? '')
                    ];
                }
            }
            
            $template->variables = json_encode($variables);
        }
    }
    
    /**
     * Handle thumbnail upload
     */
    private function handleThumbnailUpload($template, $isUpdate = false) {
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../../storage/uploads/template-items/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $filename = time() . '_' . basename($_FILES['thumbnail']['name']);
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
                if ($isUpdate && $template->thumbnail) {
                    $oldFile = __DIR__ . '/../../../../public' . $template->thumbnail;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $template->thumbnail = '/storage/uploads/template-items/' . $filename;
            }
        }
    }
}

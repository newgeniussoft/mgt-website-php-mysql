<?php
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/MiddlewareHandler.php';
require_once __DIR__ . '/Controller.php';

require_once __DIR__ . '/../../models/Content.php';
class PagesAdminController extends Controller {
    private $middleware;
    private $pageModel;
    public function __construct() {
        parent::__construct();
        $this->middleware = new MiddlewareHandler();
        $this->pageModel = new Page();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    // List all pages
    public function index() {
        return $this->applyMiddleware(function () {
            $pages = $this->pageModel->all();
            echo $this->view('admin.pages.index', ['pages' => $pages, 'title' => 'Manage Pages']);
        }, [new AuthMiddleware()]);
    }

    // get page by path
    public function getPage($path) {
        $page = $this->pageModel->findByPath($path);
        return $page;
    }
    // Show create form & handle create
    public function create() {
        return $this->applyMiddleware(function () {
            $error = '';
            $contentModel = new Content();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST;
                // Handle file upload
                if (isset($_FILES['meta_image']) && $_FILES['meta_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                    $filename = time() . '_' . basename($_FILES['meta_image']['name']);
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['meta_image']['tmp_name'], $targetPath)) {
                        $data['meta_image'] = 'img/uploads/images/' . $filename;
                    } else {
                        $data['meta_image'] = '';
                    }
                } else {
                    $data['meta_image'] = '';
                }
                // Ensure content fields are set (HTML from Summernote)
                $data['content'] = $_POST['content'] ?? '';
                $data['content_es'] = $_POST['content_es'] ?? '';
                $data['title'] = $_POST['title_en'] ?? '';
                $data['title_es'] = $_POST['title_es'] ?? '';
                if ($this->pageModel->create($data)) {
                    // Get last inserted page (by path or id)
                    $lastPage = $this->pageModel->findByPath($data['path']);
                    // Handle contents
                    if (!empty($_POST['content_type']) && is_array($_POST['content_type'])) {
                        foreach ($_POST['content_type'] as $i => $ctype) {
                            $cval = $_POST['content_val'][$i] ?? '';
                            if ($ctype && $cval) {
                                $contentModel->create([
                                    'page' => $lastPage['path'],
                                    'type' => $ctype,
                                    'val' => $cval
                                ]);
                            }
                        }
                    }
                    header('Location: /access/pages'); exit;
                } else {
                    $error = 'Failed to create page.';
                }
            }
            $allPages = $this->pageModel->all();
            echo $this->view('admin.pages.form', [
                'action' => 'create',
                'error' => $error,
                'title' => 'Create Page',
                'allPages' => $allPages,
                'contents' => []
            ]);
        }, [new AuthMiddleware()]);
    }
    // Show edit form & handle update
    public function edit() {
        return $this->applyMiddleware(function () {
            $id = $_GET['id'] ?? null;
            if (!$id) { header('Location: /access/pages'); exit; }
            $error = '';
            $contentModel = new Content();
            $page = $this->pageModel->find($id);
            if (!$page) { header('Location: /access/pages'); exit; }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST;
                // Handle file upload
                if (isset($_FILES['meta_image']) && $_FILES['meta_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                    $filename = time() . '_' . basename($_FILES['meta_image']['name']);
                    $targetPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['meta_image']['tmp_name'], $targetPath)) {
                        $data['meta_image'] = 'img/uploads/images/' . $filename;
                    } else {
                        $data['meta_image'] = $page['meta_image'] ?? '';
                    }
                } else {
                    // No new upload, keep old image
                    $data['meta_image'] = $_POST['old_meta_image'] ?? ($page['meta_image'] ?? '');
                }
                // Ensure content fields are set (HTML from Summernote)
                $data['content'] = $_POST['content'] ?? '';
                $data['content_es'] = $_POST['content_es'] ?? '';
                $data['title'] = $_POST['title_en'] ?? '';
                $data['title_es'] = $_POST['title_es'] ?? '';
                if ($this->pageModel->update($id, $data)) {
                    // Handle contents CRUD
                    $pagePath = $page['path'];
                    // Delete
                    if (!empty($_POST['delete_content_id'])) {
                        foreach ($_POST['delete_content_id'] as $delId) {
                            $contentModel->delete($delId);
                        }
                    }
                    // Update & Create
                    if (!empty($_POST['content_type']) && is_array($_POST['content_type'])) {
                        foreach ($_POST['content_type'] as $i => $ctype) {
                            $cid = $_POST['content_id'][$i] ?? null;
                            $cval = $_POST['content_val'][$i] ?? '';
                            if ($cid) {
                                // Update
                                $contentModel->update($cid, ['type' => $ctype, 'val' => $cval]);
                            } elseif ($ctype && $cval) {
                                // Create
                                $contentModel->create([
                                    'page' => $pagePath,
                                    'type' => $ctype,
                                    'val' => $cval
                                ]);
                            }
                        }
                    }
                    header('Location: /access/pages'); exit;
                } else {
                    $error = 'Failed to update page.';
                }
            }
            $allPages = $this->pageModel->all();
            $contents = $contentModel->allByPage($page['path']);
            echo $this->view('admin.pages.form', [
                'action' => 'edit',
                'page' => $page,
                'error' => $error,
                'title' => 'Edit Page',
                'allPages' => $allPages,
                'contents' => $contents
            ]);
        }, [new AuthMiddleware()]);
    }
    // Remove page
    public function delete() {
        return $this->applyMiddleware(function () {
            $id = $_GET['id'] ?? null;
            if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->pageModel->delete($id);
                header('Location: /access/pages'); exit;
            }
            $page = $this->pageModel->find($id);
            echo $this->view('admin.pages.delete', ['page' => $page, 'title' => 'Delete Page']);
        }, [new AuthMiddleware()]);
    }
    // Middleware helper
    public function applyMiddleware($callback, $middlewares = []) {
        $handler = new MiddlewareHandler();
        foreach ($middlewares as $middleware) {
            $handler->add($middleware);
        }
        return $handler->run($callback);
    }
}

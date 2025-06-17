<?php
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/MiddlewareHandler.php';
require_once __DIR__ . '/Controller.php';

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
    // Show create form & handle create
    public function create() {
        return $this->applyMiddleware(function () {
            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST;
                if ($this->pageModel->create($data)) {
                    header('Location: /access/pages'); exit;
                } else {
                    $error = 'Failed to create page.';
                }
            }
            echo $this->view('admin.pages.form', ['action' => 'create', 'error' => $error, 'title' => 'Create Page']);
        }, [new AuthMiddleware()]);
    }
    // Show edit form & handle update
    public function edit() {
        return $this->applyMiddleware(function () {
            $id = $_GET['id'] ?? null;
            if (!$id) { header('Location: /access/pages'); exit; }
            $error = '';
            $page = $this->pageModel->find($id);
            if (!$page) { header('Location: /access/pages'); exit; }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $_POST;
                if ($this->pageModel->update($id, $data)) {
                    header('Location: /access/pages'); exit;
                } else {
                    $error = 'Failed to update page.';
                }
            }
            echo $this->view('admin.pages.form', ['action' => 'edit', 'page' => $page, 'error' => $error, 'title' => 'Edit Page']);
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
    private function applyMiddleware($callback, $middlewares = []) {
        $handler = new MiddlewareHandler();
        foreach ($middlewares as $middleware) {
            $handler->add($middleware);
        }
        return $handler->run($callback);
    }
}

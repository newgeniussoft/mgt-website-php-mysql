<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../../models/Content.php';
require_once __DIR__ . '/../../utils/helpers/helper.php';

require_once 'Controller.php';

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class PageController extends Controller
{
    public $model;
    private $contentModel;
    private $pages;
    private $user;

    public function __construct($language = null)
    {
        parent::__construct($language);
        AuthMiddleware::check();
        $this->user = $_SESSION['user'];
        $this->model = new Page();
        $this->contentModel = new Content();
        $this->pages = $this->model->all();
    }

    public function index() {
        echo $this->view('admin.pages.index', [
            'user' => $this->user,
            'pages' => $this->pages ]
        );
    }

    public function delete($id = 0) {
        $variables = [
            'user' => $this->user,
            'pages' => $this->pages,
        ];
            if ($id != 0) {
                $page = (object) $this->model->get($id);
                $contents = $this->contentModel->where('page', $page->path);
                foreach ($contents as $content) {
                    $this->contentModel->delete($content->id);
                }
                $this->model->delete($id);
            }
            $variables['success'] = "ok";
        header("Location: ".url_admin("page"));
    }

    public function all() {
        return $this->model->all();
    }
    

    public function edit($id = 0) {
        $variables = [
            'user' => $this->user,
            'pages' => $this->pages,
        ];
        if ($id != 0) {
            $page = $this->model->get($id);
            $contents = $this->contentModel->where('page', $page['path']);
            $variables['contents'] = $contents;
            $variables['page'] = $page;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [];
            if (isset($_FILES['meta_image']) && $_FILES['meta_image']['error'] === UPLOAD_ERR_OK) {
                
                $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
                $upImg = $this->upload($uploadDir, $_FILES['meta_image']);
                if ($upImg->success) {
                    $data['meta_image'] = 'img/uploads/images/' . $upImg->filename;
                } else {
                    $data['meta_image'] = $page['meta_image'] ?? '';
                }
            }
            $data['menu_title'] = $_POST['menu_title'] ?? '';
            $data['menu_title_es'] = $_POST['menu_title_es'] ?? '';
            $data['title'] = $_POST['title'] ?? '';
            $data['title_es'] = $_POST['title_es'] ?? '';
            $data['meta_title'] = $_POST['meta_title'] ?? '';
            $data['meta_title_es'] = $_POST['meta_title_es'] ?? '';
            $data['meta_description'] = $_POST['meta_description'] ?? '';
            $data['meta_description_es'] = $_POST['meta_description_es'] ?? '';
            $data['meta_keywords'] = $_POST['meta_keywords'] ?? '';
            $data['meta_keywords_es'] = $_POST['meta_keywords_es'] ?? '';
            $data['title_h1'] = $_POST['title_h1'] ?? '';
            $data['title_h1_es'] = $_POST['title_h1_es'] ?? '';
            $data['title_h2'] = $_POST['title_h2'] ?? '';
            $data['title_h2_es'] = $_POST['title_h2_es'] ?? '';
            $data['content'] = $_POST['content'] ?? '';
            $data['content_es'] = $_POST['content_es'] ?? '';
            $data['path'] = $_POST['path'] ?? '';
            if ($_POST['action'] == "edit") {
                if ($this->model->update($id, $data)) {
                    // Handle contents CRUD
                    $pagePath = $page['path'];
                    // Delete
                    if (!empty($_POST['delete_content_id'])) {
                        foreach ($_POST['delete_content_id'] as $delId) {
                            $this->contentModel->delete($delId);
                        }
                    }
                    // Update & Create
                    if (!empty($_POST['content_type']) && is_array($_POST['content_type'])) {
                        foreach ($_POST['content_type'] as $i => $ctype) {
                            $cid = $_POST['content_id'][$i] ?? null;
                            $cval = $_POST['content_val'][$i] ?? '';
                            $cval_es = $_POST['content_val_es'][$i] ?? '';
                            if ($cid) {
                                // Update
                                $this->contentModel->update($cid, ['type' => $ctype, 'val' => $cval, 'val_es' => $cval_es]);
                            } elseif ($ctype && ($cval || $cval_es)) {
                                // Create
                                $this->contentModel->create([
                                    'page' => $pagePath,
                                    'type' => $ctype,
                                    'val' => $cval,
                                    'val_es' => $cval_es
                                ]);
                            }
                        }
                        $variables['contents'] = $this->contentModel->where('page', $pagePath);
                    }
                /*  header('Location: /page'); exit;*/
                    $variables['success'] = "ok";
                } else {
                    $error = 'Failed to update page.';
                }
                
        $variables['page'] = $this->model->get($id);
            } else {
                if ($this->model->create($data)) {
                    
                    $pagePath = $data['path'];
                    if (!empty($_POST['content_type']) && is_array($_POST['content_type'])) {
                        foreach ($_POST['content_type'] as $i => $ctype) {
                            $cid = $_POST['content_id'][$i] ?? null;
                            $cval = $_POST['content_val'][$i] ?? '';
                            $cval_es = $_POST['content_val_es'][$i] ?? '';
                            if ($cid) {
                                // Update
                                $this->contentModel->update($cid, ['type' => $ctype, 'val' => $cval, 'val_es' => $cval_es]);
                            } elseif ($ctype && ($cval || $cval_es)) {
                                // Create
                                $this->contentModel->create([
                                    'page' => $pagePath,
                                    'type' => $ctype,
                                    'val' => $cval,
                                    'val_es' => $cval_es
                                ]);
                            }
                        }
                        $variables['contents'] = $this->contentModel->where('page', $pagePath);
                    }
                }
            }
        }
        
       echo $this->view('admin.pages.form', $variables);
    }


}
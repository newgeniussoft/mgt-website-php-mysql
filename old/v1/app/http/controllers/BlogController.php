<?php
require_once __DIR__ . '/../../models/Blog.php';
require_once 'Controller.php';

class BlogController extends Controller {
    protected $blogModel;

    public function __construct($lang = 'en') {
        parent::__construct(new Blog(), $lang);
        $this->blogModel = new Blog();
    }

    // Admin: List all blogs
    public function adminIndex() {
        $blogs = $this->blogModel->all();
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.blogs.index', ['user' => $user, 'blogs' => $blogs]);
    }

    // Admin: Show create form
    public function adminCreate() {
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.blogs.create', ['user' => $user]);
    }

    // Admin: Store new blog
    public function adminStore() {
        $data = $_POST;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/img/uploads/blogs/';
            $upImg = $this->upload($uploadDir, $_FILES['image']);
            $data['image'] = 'img/uploads/blogs/' . $upImg->filename;
        }
        $this->blogModel->create($data);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/blogs');
        exit;
    }

    // Admin: Show edit form
    public function adminEdit($id) {
        $blog = $this->blogModel->get($id);
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.blogs.edit', ['user' => $user, 'blog' => $blog]);
    }

    // Admin: Update blog
    public function adminUpdate($id) {
        $data = $_POST;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/img/uploads/blogs/';
            $upImg = $this->upload($uploadDir, $_FILES['image']);
            $data['image'] = 'img/uploads/blogs/' . $upImg->filename;
        }   
        $this->blogModel->update($id, $data);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/blogs');
        exit;
    }

    // Admin: Delete blog
    public function adminDelete($id) {
        $this->blogModel->delete($id);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/blogs');
        exit;
    }
}
?>

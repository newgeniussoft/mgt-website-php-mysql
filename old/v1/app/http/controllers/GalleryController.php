<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Gallery.php';

require_once 'Controller.php';

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class GalleryController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
    }

    public function index()
    {
        AuthMiddleware::check();
        $user = $_SESSION['user'];
        
        $galleryModel = new Gallery();
        $galleries = $galleryModel->all();
        $message_error = '';
        $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/gallery/';
        $variables = [
            'user' => $user,
            'galleries' => $galleries
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gallery_action'])) {
            // Handle image upload
            $data = [];
            if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
                $upImg = $this->upload($uploadDir, $_FILES['gallery_image']);
                if ($upImg->success) {
                    $data['image'] = 'img/uploads/gallery/' . $upImg->filename;
                    $data['type'] = isset($_POST['gallery_type']) ? $_POST['gallery_type'] : 'default';
                    $galleryModel->create($data);
                    $galleries = $galleryModel->all();
                    $variables['galleries'] = $galleries;
                } else {
                    $variables['error'] = "Image errors: " . implode(', ', $upImg->errors) . "<br>";
                }

            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gallery_delete'])) {
            $galleryModel->delete($_POST['gallery_delete']);
            $galleries = $galleryModel->all();
            $variables['galleries'] = $galleries;
            $response['success'] = true;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;

        }

        echo $this->view('admin.pages.gallery.index', $variables);
    }

}
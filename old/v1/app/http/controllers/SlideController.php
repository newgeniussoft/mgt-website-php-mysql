<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Slide.php';

require_once 'Controller.php';

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class SlideController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
    }

    public function index()
    {
        AuthMiddleware::check();
        $user = $_SESSION['user'];
        
        $slideModel = new Slide();
        $slides = $slideModel->all();
        $variables = [
            'user' => $user,
            'slides' => $slides
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slide_action'])) {
            $data = [
                'caption' => $_POST['caption'] ?? '',
                'caption_es' => $_POST['caption_es'] ?? '',
            ];
            $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/slides/';
            $upImg = $this->upload($uploadDir, $_FILES['slide_image']);
            if ($upImg->success) {
                $data['image'] = 'img/uploads/slides/' . $upImg->filename;
                $slideModel->create($data);
                $variables['slides'] = $slideModel->all();
            } else {
                $variables['error'] = "Image errors: " . implode(', ', $upImg->errors) . "\n";
            }
            
        } 
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slide_delete'])) {
            $slideModel->delete($_POST['slide_delete']);
            $response['success'] = true;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }


        echo $this->view('admin.pages.info.slide', $variables);
    }
}

?>
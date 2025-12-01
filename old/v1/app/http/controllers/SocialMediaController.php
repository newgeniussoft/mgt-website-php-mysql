<?php

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/SocialMedia.php';

require_once 'Controller.php';



require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class SocialMediaController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
    }
    public function index()
    {
        AuthMiddleware::check();
        $user = $_SESSION['user'];
        $variables = [
            'user' => $user,
        ];
        $socialMediaModel = new SocialMedia();
        $socialMedias = $socialMediaModel->all();
        $variables['socialMedias'] = $socialMedias;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['social_action'])) {
            $data = [
                'name' => $_POST['name'] ?? '',
                'link' => $_POST['link'] ?? '',
            ];

            $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
            if (isset($_FILES['image'])) {
                $upImg = $this->upload($uploadDir, $_FILES['image']);
                if ($upImg->success) {
                    $data['image'] = 'img/uploads/images/' . $upImg->filename;
                    $variables['socialMedias'] = $socialMediaModel->all();
                } else {
                    $variables['error'] = "Image errors: " . implode(', ', $upImg->errors) . "\n";
                }
                
            }

            if ($_POST['social_action'] == 'create') {
                $socialMediaModel->create($data);
            } elseif ($_POST['social_action'] == 'edit') {
                $socialMediaModel->update($_POST['id'], $data);
                $response['data'] = $socialMediaModel->get($_POST['id']);
            } elseif ($_POST['social_action'] == 'delete') {
                $socialMediaModel->delete($_POST['id']);
            }
            $response['success'] = true;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;

        }

        echo $this->view('admin.pages.info.social_media', $variables);
    }
}
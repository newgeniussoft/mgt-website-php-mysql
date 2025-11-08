<?php
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Info.php';

require_once 'Controller.php';

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class InfoController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
    }

    public function index() {
        AuthMiddleware::check();
        $user = $_SESSION['user'];
        $infoModel = new Info();
        $info = (object) $infoModel->get(1);
        $message_error = '';
        $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
        $variables = [
            'user' => $user,
            'info' => $info
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['info_action'])) {
            $data = [
                'phone' => $_POST['phone'] ?? '',
                'whatsapp' => $_POST['whatsapp'] ?? '',
                'email' => $_POST['email'] ?? '',
                'short_about' => $_POST['short_about'] ?? '',
                'short_about_es' => $_POST['short_about_es'] ?? '',
                'address' => $_POST['address'] ?? '',
            ];
            $fields = ['logo', 'image', 'image_property'];
            foreach ($fields as $field) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                    $upImg = $this->upload($uploadDir, $_FILES[$field]);
                    if ($upImg->success) {
                        $data[$field] = 'img/uploads/images/' . $upImg->filename;
                    } else {
                        $message_error .= ucfirst($field) . " image errors: " . implode(', ', $upImg->errors) . "<br>";
                    }
                }
            }

            try {
                $infoModel->update(1, $data);
                $variables['info'] = (object) $infoModel->get(1);
                $variables['success'] = "Update successfully!";
                $variables['message_error'] = $message_error;
            } catch (PDOException $error) {
                $variables['error'] = "Update error! " . $error->getMessage();
            }
        }

        echo $this->view('admin.pages.info.index', $variables);
    }
}
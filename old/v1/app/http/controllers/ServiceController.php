<?php
require_once __DIR__ . '/../../models/Service.php';
require_once 'Controller.php';

class ServiceController extends Controller {
    protected $serviceModel;

    public function __construct($lang = 'en') {
        parent::__construct(new Service(), $lang);
        $this->serviceModel = new Service();
    }

    // Admin: List all services
    public function adminIndex() {
        $services = $this->serviceModel->all();
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.services.index', ['user' => $user, 'services' => $services]);
    }

    // Admin: Show create form
    public function adminCreate() {
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.services.create', ['user' => $user]);
    }

    // Admin: Store new service
    public function adminStore() {
        $data = $_POST;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/img/uploads/services/';
            $imgUpload = $this->upload($uploadDir, $_FILES['image']);
            if ($imgUpload->success) {
                $data['image'] = 'img/uploads/services/' . $imgUpload->filename;
            }
        }
        $this->serviceModel->create($data);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/services');
        exit;
    }

    // Admin: Show edit form
    public function adminEdit($id) {
        $user = $_SESSION['user'];
        $service = $this->serviceModel->get($id);
        echo $this->view('admin.pages.services.edit', ['user' => $user, 'service' => $service]);
    }

    // Admin: Update service
    public function adminUpdate($id) {
        $data = $_POST;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/img/uploads/services/';
            $imgUpload = $this->upload($uploadDir, $_FILES['image']);
            if ($imgUpload->success) {
                $data['image'] = 'img/uploads/services/' . $imgUpload->filename;
            }
        }
        $this->serviceModel->update($id, $data);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/services');
        exit;
    }

    // Admin: Delete service
    public function adminDelete($id) {
        $this->serviceModel->delete($id);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/services');
        exit;
    }
}
?>

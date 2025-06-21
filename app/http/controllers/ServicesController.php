<?php
require_once __DIR__ . '/../../models/Service.php';
require_once 'Controller.php';

class ServicesController extends Controller {
    public function index() {
        $serviceModel = new Service();
        $services = $serviceModel->all();
        $success = isset($_GET['success']) ? $_GET['success'] : '';
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        echo $this->view('admin.services.index', [
            'services' => $services,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function handle() {
        $serviceModel = new Service();
        $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/services/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

        if (isset($_POST['service_action'])) {
            $action = $_POST['service_action'];
            if ($action === 'create') {
                $image = '';
                if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
                    $filename = time() . '_' . basename($_FILES['service_image']['name']);
                    move_uploaded_file($_FILES['service_image']['tmp_name'], $uploadDir . $filename);
                    $image = 'img/uploads/services/' . $filename;
                }
                $data = [
                    'image' => $image,
                    'title' => $_POST['title'],
                    'title_es' => $_POST['title_es'],
                    'subtitle' => $_POST['subtitle'],
                    'subtitle_es' => $_POST['subtitle_es']
                ];
                $serviceModel->create($data);
                header('Location: /access/services?success=Service created');
                exit;
            } elseif ($action === 'edit') {
                $id = $_POST['service_id'];
                $oldImage = $_POST['old_image'];
                $image = $oldImage;
                if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
                    $filename = time() . '_' . basename($_FILES['service_image']['name']);
                    move_uploaded_file($_FILES['service_image']['tmp_name'], $uploadDir . $filename);
                    $image = 'img/uploads/services/' . $filename;
                }
                $data = [
                    'image' => $image,
                    'title' => $_POST['title'],
                    'title_es' => $_POST['title_es'],
                    'subtitle' => $_POST['subtitle'],
                    'subtitle_es' => $_POST['subtitle_es']
                ];
                $serviceModel->update($id, $data);
                header('Location: /access/services?success=Service updated');
                exit;
            } elseif ($action === 'delete') {
                $id = $_POST['service_id'];
                $service = $serviceModel->find($id);
                if ($service && !empty($service['image'])) {
                    $imagePath = $uploadDir . basename($service['image']);
                    if (file_exists($imagePath)) { @unlink($imagePath); }
                }
                $serviceModel->delete($id);
                header('Location: /access/services?success=Service deleted');
                exit;
            }
        }
        header('Location: /access/services?error=Invalid action');
        exit;
    }
}

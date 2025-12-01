<?php
require_once __DIR__ . '/../../models/User.php';

class UserController extends Controller {
    public $userModel;
    public function __construct() {
        parent::__construct(new User());
        $this->userModel = new User();
    }

    public function index() {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        if (isset($pathParts[3])) {
            $this->edit($pathParts[3]);
        } else {
            $users = $this->userModel->all();
            echo $this->view('admin.users.index', ['users' => $users]);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fullname' => $_POST['fullname'],
                'email' => $_POST['email'],
                'roles' => $_POST['roles'] ?? 'user'
            ];

            // Only update password if provided
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
                $upload = new UploadImage();
                $data['profile_image'] = $upload->uploadImage($_FILES['profile_image']);
            }

            $this->userModel->update($id, $data);
            $user = (object) $this->userModel->get($id);
            echo $this->view('admin.users.edit', ['user' => $user, "success" => "User updated successfully"]);
            exit;
        }
        $user = (object) $this->userModel->get($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }
        echo $this->view('admin.users.edit', ['user' => $user]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fullname' => $_POST['fullname'],
                'email' => $_POST['email'],
                'roles' => $_POST['roles'] ?? 'user'
            ];

            // Only update password if provided
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
                $upload = new UploadImage();
                $data['profile_image'] = $upload->uploadImage($_FILES['profile_image']);
            }

            $this->userModel->updateData($id, $data);
            header('Location: /admin/users');
            exit;
        }
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAll() {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateData($id, $data) {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}

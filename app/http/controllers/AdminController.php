<?php

require_once 'Controller.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/MiddlewareHandler.php';
require_once __DIR__ . '/ServicesController.php';

class AdminController extends Controller
{
    /**
     * @var MiddlewareHandler
     */
    private $middleware;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware = new MiddlewareHandler();
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Apply middleware to a callback function
     * 
     * @param callable $callback The controller method to execute
     * @param array $middlewares List of middleware to apply
     * @return mixed
     */
    public function applyMiddleware($callback, $middlewares = [])
    {
        $handler = new MiddlewareHandler();
        
        // Add all middleware to the handler
        foreach ($middlewares as $middleware) {
            $handler->add($middleware);
        }
        
        // Run the middleware chain
        return $handler->run($callback);
    }
    
    /**
     * Admin dashboard page (protected by auth middleware)
     */
    public function index()
    {
        return $this->dashboard();
    }
    
    /**
     * Admin login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
            header('Location: /access');
            exit;
        }
        
        $error = '';
        
        // Process login form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate credentials (replace with your actual authentication logic)
            if ($this->validateCredentials($username, $password)) {
                // Set session variables
                $_SESSION['admin_authenticated'] = true;
                $_SESSION['admin_username'] = $username;
                
                // Redirect to admin dashboard
                header('Location: /access');
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        }
        
        // Display login form
        echo $this->view('admin.login', [
            'title' => 'Admin Login',
            'error' => $error
        ]);
    }
    
    /**
     * Admin logout
     */
    public function logout()
    {
        // Clear session variables
        unset($_SESSION['admin_authenticated']);
        unset($_SESSION['admin_username']);
        
        // Destroy session
        session_destroy();
        
        // Redirect to login page
        header('Location: /access/login');
        exit;
    }
    
    /**
     * Dashboard page (protected by auth middleware)
     */
    public function dashboard()
    {
        return $this->applyMiddleware(function() {
            echo $this->view('admin.dashboard', [
                'title' => 'Admin Dashboard',
                'username' => $_SESSION['admin_username'] ?? 'Admin'
            ]);
        }, [new AuthMiddleware()]);
    }
    
    /**
     * Users management page (protected by auth middleware)
     */
    public function users()
    {
        return $this->applyMiddleware(function() {
            return $this->view('admin.users', [
                'title' => 'User Management',
                'username' => $_SESSION['admin_username'] ?? 'Admin'
            ]);
        }, [new AuthMiddleware()]);
    }
    
    /**
     * Settings page (protected by auth middleware)
     */
    public function settings()
    {
        return $this->applyMiddleware(function() {
            return $this->view('admin.settings', [
                'title' => 'Admin Settings',
                'username' => $_SESSION['admin_username'] ?? 'Admin'
            ]);
        }, [new AuthMiddleware()]);
    }
    
    /**
     * Validate user credentials
     * 
     * @param string $username Username
     * @param string $password Password
     * @return bool
     */
    private function validateCredentials($username, $password)
    {
        // Replace with your actual authentication logic (database check, etc.)
        // This is a simple example - in a real application, you would check against a database
        // and use proper password hashing
        
        // For demo purposes, accept 'admin' / 'admin123'
        return ($username === 'admin' && $password === 'admin123');
    }
    /**
     * Website Info management page (edit-only, single record)
     */
    public function info()
    {
        require_once __DIR__ . '/../../models/Info.php';
        require_once __DIR__ . '/../../models/Slide.php';
        require_once __DIR__ . '/../../models/Video.php';
        require_once __DIR__ . '/../../models/SocialMedia.php';
        return $this->applyMiddleware(function() {
            $infoModel = new Info();
            $slideModel = new Slide();
            $videoModel = new Video();
            $socialMediaModel = new SocialMedia();
            $info = $infoModel->getInfo();
            $slides = $slideModel->all();
            $videos = $videoModel->all();
            $social_media = $socialMediaModel->all();
            $success = '';
            $error = '';

            $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/images/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            $slideUploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/slides/';
            if (!is_dir($slideUploadDir)) { mkdir($slideUploadDir, 0777, true); }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['info_action'])) {
                $data = [
                    'phone' => $_POST['phone'] ?? '',
                    'whatsapp' => $_POST['whatsapp'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'short_about' => $_POST['short_about'] ?? '',
                ];

                // Handle logo upload
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $logoName = uniqid('logo_') . '_' . basename($_FILES['logo']['name']);
                    $logoPath = $uploadDir . $logoName;
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
                        $data['logo'] = 'img/uploads/images/' . $logoName;
                    } else {
                        $error = 'Failed to upload logo.';
                    }
                } else {
                    $data['logo'] = $info['logo'] ?? '';
                }

                // Handle main image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $imgName = uniqid('img_') . '_' . basename($_FILES['image']['name']);
                    $imgPath = $uploadDir . $imgName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $imgPath)) {
                        $data['image'] = 'img/uploads/images/' . $imgName;
                    } else {
                        $error = 'Failed to upload main image.';
                    }
                } else {
                    $data['image'] = $info['image'] ?? '';
                }

                // Handle image_property as image upload
                if (isset($_FILES['image_property']) && $_FILES['image_property']['error'] === UPLOAD_ERR_OK) {
                    $imgPropName = uniqid('imgprop_') . '_' . basename($_FILES['image_property']['name']);
                    $imgPropPath = $uploadDir . $imgPropName;
                    if (move_uploaded_file($_FILES['image_property']['tmp_name'], $imgPropPath)) {
                        $data['image_property'] = 'img/uploads/images/' . $imgPropName;
                    } else {
                        $error = 'Failed to upload image property.';
                    }
                } else {
                    $data['image_property'] = $info['image_property'] ?? '';
                }

                if (!$error) {
                    if ($infoModel->updateInfo($data)) {
                        $success = 'Website info updated successfully.';
                        $info = $infoModel->getInfo();
                    } else {
                        $error = 'Failed to update info.';
                    }
                }
            }

            // Handle Slide CRUD
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            if (isset($_POST['slide_action'])) {
                $slideAction = $_POST['slide_action'];
                if ($slideAction === 'create') {
                    $caption = $_POST['caption'] ?? '';
                    $caption_es = $_POST['caption_es'] ?? '';
                    $image = '';
                    if (isset($_FILES['slide_image']) && $_FILES['slide_image']['error'] === UPLOAD_ERR_OK) {
                        $imgName = uniqid('slide_') . '_' . basename($_FILES['slide_image']['name']);
                        $imgPath = $slideUploadDir . $imgName;
                        if (move_uploaded_file($_FILES['slide_image']['tmp_name'], $imgPath)) {
                            $image = 'img/uploads/slides/' . $imgName;
                        } else {
                            $error = 'Failed to upload slide image.';
                        }
                    }
                    if (!$error) {
                        $slideModel->create([
                            'caption' => $caption,
                            'caption_es' => $caption_es,
                            'image' => $image
                        ]);
                        $success = 'Slide created successfully.';
                        $slides = $slideModel->all();
                    }
                }
                if ($slideAction === 'edit') {
                    $slide_id = $_POST['slide_id'] ?? null;
                    $caption = $_POST['caption'] ?? '';
                    $caption_es = $_POST['caption_es'] ?? '';
                    $image = $_POST['old_image'] ?? '';
                    if (isset($_FILES['slide_image']) && $_FILES['slide_image']['error'] === UPLOAD_ERR_OK) {
                        $imgName = uniqid('slide_') . '_' . basename($_FILES['slide_image']['name']);
                        $imgPath = $slideUploadDir . $imgName;
                        if (move_uploaded_file($_FILES['slide_image']['tmp_name'], $imgPath)) {
                            $image = 'img/uploads/slides/' . $imgName;
                        } else {
                            $error = 'Failed to upload slide image.';
                        }
                    }
                    if ($slide_id && !$error) {
                        $slideModel->update($slide_id, [
                            'caption' => $caption,
                            'caption_es' => $caption_es,
                            'image' => $image
                        ]);
                        $success = 'Slide updated successfully.';
                        $slides = $slideModel->all();
                    }
                }
                if ($slideAction === 'delete') {
                    $slide_id = $_POST['slide_id'] ?? null;
                    if ($slide_id) {
                        $slideModel->delete($slide_id);
                        $success = 'Slide deleted successfully.';
                        $slides = $slideModel->all();
                    }
                }
                if ($isAjax) {
                    if ($error) {
                        echo json_encode(['error' => $error]); exit;
                    } else {
                        echo json_encode(['success' => $success]); exit;
                    }
                }
            }

            // Handle Video CRUD
            if (isset($_POST['video_action'])) {
                $videoAction = $_POST['video_action'];
                if ($videoAction === 'create') {
                    $title = $_POST['title'] ?? '';
                    $subtitle = $_POST['subtitle'] ?? '';
                    $title_es = $_POST['title_es'] ?? '';
                    $subtitle_es = $_POST['subtitle_es'] ?? '';
                    $link = $_POST['link'] ?? '';
                    $videoModel->create([
                        'title' => $title,
                        'subtitle' => $subtitle,
                        'title_es' => $title_es,
                        'subtitle_es' => $subtitle_es,
                        'link' => $link
                    ]);
                    $success = 'Video created successfully.';
                    $videos = $videoModel->all();
                }
                if ($videoAction === 'edit') {
                    $video_id = $_POST['video_id'] ?? null;
                    $title = $_POST['title'] ?? '';
                    $subtitle = $_POST['subtitle'] ?? '';
                    $title_es = $_POST['title_es'] ?? '';
                    $subtitle_es = $_POST['subtitle_es'] ?? '';
                    $link = $_POST['link'] ?? '';
                    if ($video_id) {
                        $videoModel->update($video_id, [
                            'title' => $title,
                            'subtitle' => $subtitle,
                            'title_es' => $title_es,
                            'subtitle_es' => $subtitle_es,
                            'link' => $link
                        ]);
                        $success = 'Video updated successfully.';
                        $videos = $videoModel->all();
                    }
                }
                if ($videoAction === 'delete') {
                    $video_id = $_POST['video_id'] ?? null;
                    if ($video_id) {
                        $videoModel->delete($video_id);
                        $success = 'Video deleted successfully.';
                        $videos = $videoModel->all();
                    }
                }
                if ($isAjax) {
                    if ($error) {
                        echo json_encode(['error' => $error]); exit;
                    } else {
                        echo json_encode(['success' => $success]); exit;
                    }
                }
            }

            // Handle Social Media CRUD
            $socialUploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/social_media/';
            if (!is_dir($socialUploadDir)) { mkdir($socialUploadDir, 0777, true); }
            if (isset($_POST['social_action'])) {
                $socialAction = $_POST['social_action'];
                if ($socialAction === 'create') {
                    $name = $_POST['name'] ?? '';
                    $link = $_POST['link'] ?? '';
                    $image = '';
                    if (isset($_FILES['social_image']) && $_FILES['social_image']['error'] === UPLOAD_ERR_OK) {
                        $imgName = uniqid('social_') . '_' . basename($_FILES['social_image']['name']);
                        $imgPath = $socialUploadDir . $imgName;
                        if (move_uploaded_file($_FILES['social_image']['tmp_name'], $imgPath)) {
                            $image = 'img/uploads/social_media/' . $imgName;
                        } else {
                            $error = 'Failed to upload social media image.';
                        }
                    }
                    if (!$error) {
                        $socialMediaModel->create([
                            'name' => $name,
                            'link' => $link,
                            'image' => $image
                        ]);
                        $success = 'Social media entry created successfully.';
                        $social_media = $socialMediaModel->all();
                    }
                }
                if ($socialAction === 'edit') {
                    $social_id = $_POST['social_id'] ?? null;
                    $name = $_POST['name'] ?? '';
                    $link = $_POST['link'] ?? '';
                    $image = $_POST['old_image'] ?? '';
                    if (isset($_FILES['social_image']) && $_FILES['social_image']['error'] === UPLOAD_ERR_OK) {
                        $imgName = uniqid('social_') . '_' . basename($_FILES['social_image']['name']);
                        $imgPath = $socialUploadDir . $imgName;
                        if (move_uploaded_file($_FILES['social_image']['tmp_name'], $imgPath)) {
                            $image = 'img/uploads/social_media/' . $imgName;
                        } else {
                            $error = 'Failed to upload social media image.';
                        }
                    }
                    if ($social_id && !$error) {
                        $socialMediaModel->update($social_id, [
                            'name' => $name,
                            'link' => $link,
                            'image' => $image
                        ]);
                        $success = 'Social media entry updated successfully.';
                        $social_media = $socialMediaModel->all();
                    }
                }
                if ($socialAction === 'delete') {
                    $social_id = $_POST['social_id'] ?? null;
                    if ($social_id) {
                        $socialMediaModel->delete($social_id);
                        $success = 'Social media entry deleted successfully.';
                        $social_media = $socialMediaModel->all();
                    }
                }
                if ($isAjax) {
                    if ($error) {
                        echo json_encode(['error' => $error]); exit;
                    } else {
                        echo json_encode(['success' => $success]); exit;
                    }
                }
            }

            echo $this->view('admin.info', [
                'title' => 'Website Info',
                'info' => $info,
                'slides' => $slides,
                'videos' => $videos,
                'social_media' => $social_media,
                'success' => $success,
                'error' => $error
            ]);
        }, [new AuthMiddleware()]);
    /**
     * Services admin CRUD page (protected by auth middleware)
     */
        
    }
    public function services()
    {
        return $this->applyMiddleware(function() {
            $controller = new ServicesController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handle();
            } else {
                $controller->index();
            }
        }, [new AuthMiddleware()]);
    }
}

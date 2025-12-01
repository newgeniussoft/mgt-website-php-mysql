<?php

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Review.php';

require_once __DIR__ . '/../Controller.php';


require_once __DIR__ . '/../../middleware/AuthMiddleware.php';

class AdminController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
        session_start();
    }

    public function loginForm($error = null)
    {
        AuthMiddleware::guest();
        echo $this->view('admin.auth.login', ['error' => $error]);
    }

    public function login()
    {
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: /admin-panel/dashboard');
            exit;
        } else {
            $this->loginForm('Invalid email or password');
        }
    }

    public function logout()
    {
        AuthMiddleware::check();
        session_destroy();
        header('Location: /admin-panel/login');
        exit;
    }

    public function dashboard()
    {
        AuthMiddleware::check();
        $user = $_SESSION['user'];
        // Models
        require_once __DIR__ . '/../../models/Review.php';
        require_once __DIR__ . '/../../models/Tour.php';
        require_once __DIR__ . '/../../models/Page.php';
        require_once __DIR__ . '/../../models/Gallery.php';
        require_once __DIR__ . '/../../models/Slide.php';
        require_once __DIR__ . '/../../models/Blog.php';

        $reviewModel = new \Review();
        $tourModel = new \Tour();
        $pageModel = new \Page();
        $galleryModel = new \Gallery();
        $slideModel = new \Slide();
        $blogModel = new \Blog();

        // Review counts
        $allReviews = $reviewModel->all();
        $pendingReviews = 0;
        $approvedReviews = 0;
        foreach ($allReviews as $review) {
            if (isset($review->status) && strtolower($review->status) === 'pending') {
                $pendingReviews++;
            } else {
                $approvedReviews++;
            }
        }

        $tourCount = count($tourModel->all());
        $pageCount = count($pageModel->all());
        $galleryCount = count($galleryModel->all());
        $slideCount = count($slideModel->all());
        $blogCount = count($blogModel->all());

        require_once __DIR__ . '/../../models/Visitor.php';
        $visitorModel = new \Visitor();
        $visitorCount = count($visitorModel->all());

        echo $this->view('admin.pages.dashboard', [
            'user' => $user,
            'review_count' => count($allReviews),
            'pending_review_count' => $pendingReviews,
            'approved_review_count' => $approvedReviews,
            'tour_count' => $tourCount,
            'page_count' => $pageCount,
            'gallery_count' => $galleryCount,
            'slide_count' => $slideCount,
            'blog_count' => $blogCount,
            'visitor_count' => $visitorCount
        ]);
    }
    public function model($model = null)
    {
        AuthMiddleware::check();
        $moreController = new MoreController();
        $moreController->index($model);
    }

    public function info()
    {
        AuthMiddleware::check();
       $infoController = new InfoController();
       $infoController->index();
    }
    public function users()
    {
        AuthMiddleware::check();
        $userController = new UserController();
        $userController->index();
    }

    public function gallery()
    {
        AuthMiddleware::check();
        $galleryController = new GalleryController();
        $galleryController->index();
    }

    public function slide()
    {
        AuthMiddleware::check();
        $slideController = new SlideController();
        $slideController->index();
    }

    public function editor()
    {
        AuthMiddleware::check();
        $editorController = new EditorController();
        $editorController->index();
    }

    public function socialMedia()
    {
        AuthMiddleware::check();
        $socialMediaController = new SocialMediaController();
        $socialMediaController->index();
    }

    public function video()
    {
        AuthMiddleware::check();
        $videoController = new VideoController();
        $videoController->index();
    }
    public function page($params = null,$params2 = null)
    {
        $pageController = new PageController();
        if ($params) {
            if ($params == 'delete') {
                $pageController->delete($params2);
            } else {
                $pageController->edit($params2);
            }
        } else {
            $pageController->index();
        }
    }
    
    public function action()
    {
        $review = new Review();
        if (isset($_GET['delete_review'])) {
            $id_review = $_GET['delete_review'];
            $review->delete($id_review);
            header("location: https://madagascar-green-tours.com/reviews?deleted");
        }
        if (isset($_GET['accept_review'])) {
            $id_review = $_GET['accept_review'];
            $review->update($id_review, ['pending' => '0']);
            header("location: https://madagascar-green-tours.com/reviews?added");
        }
    }

    public function visitor() {
        require_once __DIR__ . '/../../models/Visitor.php';
        $visitor = new Visitor();
        //$visitors = $visitor->all();
        $query = $visitor->sql("SELECT count(*) as count, country_code, country FROM visitors GROUP BY country_code ORDER BY count DESC");
       
        $visitors = null;
        foreach ($query->fetchAll(PDO::FETCH_OBJ) as $visitor) {
            $visitors[] = [
                'id' => $visitor->country_code,
                'name' => $visitor->country,
                'count' => (int)$visitor->count,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($visitors);
    }
}

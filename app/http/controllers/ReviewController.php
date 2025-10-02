<?php
require_once __DIR__ . '/../../models/Review.php';
require_once 'Controller.php';

class ReviewController extends Controller {
    protected $reviewModel;

    public function __construct($lang = 'en') {
        parent::__construct(new Review(), $lang);
        $this->reviewModel = new Review();
    }

    // Admin: List all reviews
    public function adminIndex() {
        $user = $_SESSION['user'];
        $reviews = $this->reviewModel->all();
        echo $this->view('admin.pages.reviews.index', ['user' => $user, 'reviews' => $reviews]);
    }

    // Admin: Show create form
    public function adminCreate() {
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.reviews.create', ['user' => $user]);
    }

    // Admin: Store new review
    public function adminStore() {
        $data = $_POST;
        $this->reviewModel->create($data);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/reviews');
        exit;
    }

    // Admin: Show edit form
    public function adminEdit($id) {
        $user = $_SESSION['user'];
        $review = (object) $this->reviewModel->get($id);
        echo $this->view('admin.pages.reviews.edit', ['user' => $user, 'review' => $review]);
    }

    // Admin: Update review
    public function adminUpdate($id) {
        $data = $_POST;
        $this->reviewModel->update($id, $data);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/reviews');
        exit;
    }

    // Admin: Delete review
    public function adminDelete($id) {
        $this->reviewModel->delete($id);
        header('Location: /'.$_ENV['PATH_ADMIN'].'/reviews');
        exit;
    }
}

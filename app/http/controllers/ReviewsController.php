<?php
require_once __DIR__ . '/../../models/Review.php';
require_once 'Controller.php';

class ReviewsController extends Controller {
    public function index() {
        $reviewModel = new Review();
        $reviews = $reviewModel->all();
        $success = isset($_GET['success']) ? $_GET['success'] : '';
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        echo $this->view('admin.reviews.index', [
            'reviews' => $reviews,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function handle() {
        $reviewModel = new Review();
        if (isset($_POST['review_action'])) {
            $action = $_POST['review_action'];
            if ($action === 'create') {
                $data = [
                    'rating' => max(0, min(5, (int)$_POST['rating'])),
                    'name_user' => $_POST['name_user'],
                    'email_user' => $_POST['email_user'],
                    'message' => $_POST['message'],
                    'pending' => isset($_POST['pending']) ? 1 : 0,
                    
                ];
                $reviewModel->create($data);
                header('Location: /access/reviews?success=Review created');
                exit;
            } elseif ($action === 'edit') {
                $id = $_POST['review_id'];
                $data = [
                    'rating' => max(0, min(5, (int)$_POST['rating'])),
                    'name_user' => $_POST['name_user'],
                    'email_user' => $_POST['email_user'],
                    'message' => $_POST['message'],
                    'pending' => isset($_POST['pending']) ? 1 : 0,
                    
                ];
                $reviewModel->update($id, $data);
                header('Location: /access/reviews?success=Review updated');
                exit;
            } elseif ($action === 'delete') {
                $id = $_POST['review_id'];
                $reviewModel->delete($id);
                header('Location: /access/reviews?success=Review deleted');
                exit;
            }
        }
        header('Location: /access/reviews?error=Invalid action');
        exit;
    }
}

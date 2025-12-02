<?php

namespace App\Http\Controllers;

use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $status = $_GET['status'] ?? 'pending';
        if ($status === 'approved') {
            $reviews = Review::getApproved();
        } elseif ($status === 'all') {
            $reviews = Review::all();
        } else {
            $reviews = Review::getPending();
            $status = 'pending';
        }

        return view('admin.reviews.index', [
            'title' => 'Reviews',
            'reviews' => $reviews,
            'status' => $status,
        ]);
    }

    public function create()
    {
        return view('admin.reviews.create', [
            'title' => 'Create Review',
        ]);
    }

    public function store()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('reviews/create'));
        }

        $rating = (int)($_POST['rating'] ?? 0);
        $name = trim($_POST['name_user'] ?? '');
        $email = trim($_POST['email_user'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($rating < 1 || $rating > 5 || $name === '' || $email === '' || $message === '') {
            $_SESSION['error'] = 'Please fill in all required fields and provide a valid rating (1-5).';
            return redirect(admin_url('reviews/create'));
        }

        try {
            $review = Review::create([
                'rating' => $rating,
                'name_user' => $name,
                'email_user' => $email,
                'message' => $message,
                'pending' => isset($_POST['pending']) ? (int)$_POST['pending'] : 1,
            ]);
            $_SESSION['success'] = 'Review created successfully';
            return redirect(admin_url('reviews/edit?id=' . $review->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating review: ' . $e->getMessage();
            return redirect(admin_url('reviews/create'));
        }
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Invalid review ID';
            return redirect(admin_url('reviews'));
        }

        $review = Review::find($id);
        if (!$review) {
            $_SESSION['error'] = 'Review not found';
            return redirect(admin_url('reviews'));
        }

        return view('admin.reviews.edit', [
            'title' => 'Edit Review',
            'review' => $review,
        ]);
    }

    public function update()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('reviews'));
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Invalid review ID';
            return redirect(admin_url('reviews'));
        }

        $data = [];
        if (isset($_POST['rating'])) $data['rating'] = (int)$_POST['rating'];
        if (isset($_POST['name_user'])) $data['name_user'] = trim($_POST['name_user']);
        if (isset($_POST['email_user'])) $data['email_user'] = trim($_POST['email_user']);
        if (isset($_POST['message'])) $data['message'] = trim($_POST['message']);
        if (isset($_POST['pending'])) $data['pending'] = (int)$_POST['pending'];

        try {
            Review::updateById($id, $data);
            $_SESSION['success'] = 'Review updated successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating review: ' . $e->getMessage();
        }

        return redirect(admin_url('reviews/edit?id=' . $id));
    }

    public function approve()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('reviews'));
        }

        $id = (int)($_POST['id'] ?? 0);
        $pending = (int)($_POST['pending'] ?? 0); // 0 approve, 1 pending

        if ($id <= 0) {
            $_SESSION['error'] = 'Invalid review ID';
            return redirect(admin_url('reviews'));
        }

        try {
            Review::setPending($id, $pending);
            $_SESSION['success'] = $pending ? 'Review marked as pending' : 'Review approved';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating review status: ' . $e->getMessage();
        }

        return back();
    }

    public function destroy()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? null)) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('reviews'));
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Invalid review ID';
            return redirect(admin_url('reviews'));
        }

        $review = Review::find($id);
        if (!$review) {
            $_SESSION['error'] = 'Review not found';
            return redirect(admin_url('reviews'));
        }

        try {
            $review->delete();
            $_SESSION['success'] = 'Review deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting review: ' . $e->getMessage();
        }

        return redirect(admin_url('reviews'));
    }

    public function action() {
        if(isset($_GET['accept_review'])) {
            $id = (int)($_GET['accept_review'] ?? 0);
            $review = Review::updateById($id, ['pending' => 0]);
        } 
        if (isset($_GET['delete_review'])) {
            $id = (int)($_GET['delete_review'] ?? 0);
            $review = Review::deleteById($id);
        }
        
        return view('frontend.review');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tour;
use App\Models\TourDetail;
use App\Models\TourPhoto;
use App\View\View;

class TourController
{
    private $tourModel;
    private $tourDetailModel;
    private $tourPhotoModel;
    
    public function __construct()
    {
        $this->tourModel = new Tour();
        $this->tourDetailModel = new TourDetail();
        $this->tourPhotoModel = new TourPhoto();
    }
    
    /**
     * Display tours listing
     */
    public function index()
    {
        // Get filters from request
        $filters = [];
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        if (!empty($_GET['language'])) {
            $filters['language'] = $_GET['language'];
        }
        
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        
        if (!empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        
        if (!empty($_GET['featured'])) {
            $filters['featured'] = $_GET['featured'];
        }
        
        $filters['limit'] = $perPage;
        $filters['offset'] = $offset;
        
        // Get tours and statistics
        $tours = $this->tourModel->getAll($filters);
        $stats = $this->tourModel->getStats();
        $categories = $this->tourModel->getCategories();
        $languages = $this->tourModel->getAvailableLanguages();
        
        // Calculate pagination
        $totalToursQuery = $this->tourModel->getAll(array_merge($filters, ['limit' => null, 'offset' => null]));
        $totalTours = count($totalToursQuery);
        $totalPages = ceil($totalTours / $perPage);
        
        // Add photo and detail counts to tours
        foreach ($tours as &$tour) {
            $tour['photo_count'] = $this->tourPhotoModel->getCountByTourId($tour['id']);
            $tour['detail_count'] = $this->tourDetailModel->getCountByTourId($tour['id']);
            
            // Parse JSON fields for display
            $tour['highlights_array'] = json_decode($tour['highlights'] ?? '[]', true);
            $tour['price_includes_array'] = json_decode($tour['price_includes'] ?? '[]', true);
            $tour['price_excludes_array'] = json_decode($tour['price_excludes'] ?? '[]', true);
        }
        
      //  require_once 'app/views/admin/tours/index.blade.php';
      return View::make('admin.tours.index', compact('tours', 'stats', 'categories', 'languages', 'totalPages', 'page'));
        
    }
    
    /**
     * Show create tour form
     */
    public function create()
    {
        $categories = $this->tourModel->getCategories();
        $languages = $this->tourModel->getAvailableLanguages();
        $difficultyLevels = ['easy' => 'Easy', 'moderate' => 'Moderate', 'challenging' => 'Challenging', 'extreme' => 'Extreme'];
        
        return View::make('admin.tours.create', compact('categories', 'languages', 'difficultyLevels'));
    }
    
    /**
     * Store new tour
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours/create');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours/create');
            exit;
        }
        
        // Prepare data
        $data = [
            'name' => $_POST['name'] ?? '',
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'language' => $_POST['language'] ?? 'en',
            'translation_group' => $_POST['translation_group'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'description' => $_POST['description'] ?? '',
            'itinerary' => $_POST['itinerary'] ?? '',
            'price' => !empty($_POST['price']) ? (float)$_POST['price'] : null,
            'duration_days' => !empty($_POST['duration_days']) ? (int)$_POST['duration_days'] : null,
            'max_participants' => !empty($_POST['max_participants']) ? (int)$_POST['max_participants'] : null,
            'difficulty_level' => $_POST['difficulty_level'] ?? 'moderate',
            'category' => $_POST['category'] ?? '',
            'location' => $_POST['location'] ?? '',
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'sort_order' => !empty($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0,
            'meta_title' => $_POST['meta_title'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'meta_keywords' => $_POST['meta_keywords'] ?? ''
        ];
        
        // Process highlights
        if (!empty($_POST['highlights'])) {
            $highlights = array_filter(array_map('trim', explode("\n", $_POST['highlights'])));
            $data['highlights'] = $highlights;
        }
        
        // Process price includes
        if (!empty($_POST['price_includes'])) {
            $includes = array_filter(array_map('trim', explode("\n", $_POST['price_includes'])));
            $data['price_includes'] = $includes;
        }
        
        // Process price excludes
        if (!empty($_POST['price_excludes'])) {
            $excludes = array_filter(array_map('trim', explode("\n", $_POST['price_excludes'])));
            $data['price_excludes'] = $excludes;
        }
        
        // Handle file uploads
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image'] = $this->handleImageUpload($_FILES['image'], 'tours');
        }
        
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $data['cover_image'] = $this->handleImageUpload($_FILES['cover_image'], 'tours');
        }
        
        // Create tour
        $tourId = $this->tourModel->create($data);
        
        if ($tourId) {
            $_SESSION['success'] = 'Tour created successfully!';
            header('Location: /admin/tours/edit?id=' . $tourId);
        } else {
            $_SESSION['error'] = 'Failed to create tour. Please try again.';
            header('Location: /admin/tours/create');
        }
        exit;
    }
    
    /**
     * Show edit tour form
     */
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        $tour = $this->tourModel->getById($id);
        
        if (!$tour) {
            $_SESSION['error'] = 'Tour not found';
            header('Location: /admin/tours');
            exit;
        }
        
        // Parse JSON fields
        $tour['highlights_array'] = json_decode($tour['highlights'] ?? '[]', true);
        $tour['price_includes_array'] = json_decode($tour['price_includes'] ?? '[]', true);
        $tour['price_excludes_array'] = json_decode($tour['price_excludes'] ?? '[]', true);
        
        // Get related data
        $tour['details'] = $this->tourDetailModel->getByTourId($id);
        $tour['photos'] = $this->tourPhotoModel->getByTourId($id);
        $tour['translations'] = $this->tourModel->getTranslations($tour['translation_group']);
        
        $categories = $this->tourModel->getCategories();
        $languages = $this->tourModel->getAvailableLanguages();
        $difficultyLevels = ['easy' => 'Easy', 'moderate' => 'Moderate', 'challenging' => 'Challenging', 'extreme' => 'Extreme'];
        
        return View::make('admin.tours.edit', compact('tour', 'categories', 'languages', 'difficultyLevels'));
    }
    
    /**
     * Update tour
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours/edit?id=' . $id);
            exit;
        }
        
        $tour = $this->tourModel->getById($id);
        if (!$tour) {
            $_SESSION['error'] = 'Tour not found';
            header('Location: /admin/tours');
            exit;
        }
        
        // Prepare data (same as store method)
        $data = [
            'name' => $_POST['name'] ?? '',
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'language' => $_POST['language'] ?? 'en',
            'translation_group' => $_POST['translation_group'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'description' => $_POST['description'] ?? '',
            'itinerary' => $_POST['itinerary'] ?? '',
            'price' => !empty($_POST['price']) ? (float)$_POST['price'] : null,
            'duration_days' => !empty($_POST['duration_days']) ? (int)$_POST['duration_days'] : null,
            'max_participants' => !empty($_POST['max_participants']) ? (int)$_POST['max_participants'] : null,
            'difficulty_level' => $_POST['difficulty_level'] ?? 'moderate',
            'category' => $_POST['category'] ?? '',
            'location' => $_POST['location'] ?? '',
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'sort_order' => !empty($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0,
            'meta_title' => $_POST['meta_title'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'meta_keywords' => $_POST['meta_keywords'] ?? ''
        ];
        
        // Process arrays (same as store method)
        if (!empty($_POST['highlights'])) {
            $highlights = array_filter(array_map('trim', explode("\n", $_POST['highlights'])));
            $data['highlights'] = $highlights;
        }
        
        if (!empty($_POST['price_includes'])) {
            $includes = array_filter(array_map('trim', explode("\n", $_POST['price_includes'])));
            $data['price_includes'] = $includes;
        }
        
        if (!empty($_POST['price_excludes'])) {
            $excludes = array_filter(array_map('trim', explode("\n", $_POST['price_excludes'])));
            $data['price_excludes'] = $excludes;
        }
        
        // Handle file uploads
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image'] = $this->handleImageUpload($_FILES['image'], 'tours');
        }
        
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $data['cover_image'] = $this->handleImageUpload($_FILES['cover_image'], 'tours');
        }
        
        // Update tour
        if ($this->tourModel->update($id, $data)) {
            $_SESSION['success'] = 'Tour updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update tour. Please try again.';
        }
        
        header('Location: /admin/tours/edit?id=' . $id);
        exit;
    }
    
    /**
     * Delete tour
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        if ($this->tourModel->delete($id)) {
            $_SESSION['success'] = 'Tour deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete tour. Please try again.';
        }
        
        header('Location: /admin/tours');
        exit;
    }
    
    /**
     * Duplicate tour
     */
    public function duplicate()
    {
        $id = (int)($_GET['id'] ?? 0);
        $language = $_GET['language'] ?? null;
        
        $newTourId = $this->tourModel->duplicate($id, $language);
        
        if ($newTourId) {
            // Duplicate tour details
            $this->tourDetailModel->duplicateForTour($id, $newTourId);
            
            // Duplicate tour photos
            $this->tourPhotoModel->duplicateForTour($id, $newTourId);
            
            $_SESSION['success'] = 'Tour duplicated successfully!';
            header('Location: /admin/tours/edit?id=' . $newTourId);
        } else {
            $_SESSION['error'] = 'Failed to duplicate tour. Please try again.';
            header('Location: /admin/tours');
        }
        exit;
    }
    
    /**
     * Manage tour details (daily itinerary)
     */
    public function details()
    {
        $tourId = (int)($_GET['tour_id'] ?? 0);
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            $_SESSION['error'] = 'Tour not found';
            header('Location: /admin/tours');
            exit;
        }
        
        $details = $this->tourDetailModel->getByTourId($tourId);
        
        require_once 'app/views/admin/tours/details.blade.php';
    }
    
    /**
     * Add/Edit tour detail
     */
    public function saveDetail()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        $tourId = (int)($_POST['tour_id'] ?? 0);
        $detailId = (int)($_POST['detail_id'] ?? 0);
        
        $data = [
            'tour_id' => $tourId,
            'day' => (int)($_POST['day'] ?? 1),
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'meals' => $_POST['meals'] ?? '',
            'accommodation' => $_POST['accommodation'] ?? '',
            'transport' => $_POST['transport'] ?? '',
            'notes' => $_POST['notes'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0)
        ];
        
        // Process activities
        if (!empty($_POST['activities'])) {
            $activities = array_filter(array_map('trim', explode("\n", $_POST['activities'])));
            $data['activities'] = $activities;
        }
        
        if ($detailId > 0) {
            // Update existing detail
            $success = $this->tourDetailModel->update($detailId, $data);
            $message = $success ? 'Tour detail updated successfully!' : 'Failed to update tour detail.';
        } else {
            // Create new detail
            $detailId = $this->tourDetailModel->create($data);
            $success = $detailId !== false;
            $message = $success ? 'Tour detail created successfully!' : 'Failed to create tour detail.';
        }
        
        $_SESSION[$success ? 'success' : 'error'] = $message;
        header('Location: /admin/tours/details?tour_id=' . $tourId);
        exit;
    }
    
    /**
     * Delete tour detail
     */
    public function deleteDetail()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        $detailId = (int)($_POST['detail_id'] ?? 0);
        $tourId = (int)($_POST['tour_id'] ?? 0);
        
        if ($this->tourDetailModel->delete($detailId)) {
            $_SESSION['success'] = 'Tour detail deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete tour detail.';
        }
        
        header('Location: /admin/tours/details?tour_id=' . $tourId);
        exit;
    }
    
    /**
     * Manage tour photos
     */
    public function photos()
    {
        $tourId = (int)($_GET['tour_id'] ?? 0);
        $tour = $this->tourModel->getById($tourId);
        
        if (!$tour) {
            $_SESSION['error'] = 'Tour not found';
            header('Location: /admin/tours');
            exit;
        }
        
        $photos = $this->tourPhotoModel->getByTourId($tourId);
        $photoTypes = $this->tourPhotoModel->getAvailableTypes();
        
        require_once 'app/views/admin/tours/photos.blade.php';
    }
    
    /**
     * Upload tour photo
     */
    public function uploadPhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        $tourId = (int)($_POST['tour_id'] ?? 0);
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoData = [
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'alt_text' => $_POST['alt_text'] ?? '',
                'type' => $_POST['type'] ?? 'gallery',
                'day' => !empty($_POST['day']) ? (int)$_POST['day'] : null,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0
            ];
            
            $photoId = $this->tourPhotoModel->uploadAndCreate($tourId, $_FILES['photo'], $photoData);
            
            if ($photoId) {
                $_SESSION['success'] = 'Photo uploaded successfully!';
            } else {
                $_SESSION['error'] = 'Failed to upload photo. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'Please select a valid image file.';
        }
        
        header('Location: /admin/tours/photos?tour_id=' . $tourId);
        exit;
    }
    
    /**
     * Delete tour photo
     */
    public function deletePhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        $photoId = (int)($_POST['photo_id'] ?? 0);
        $tourId = (int)($_POST['tour_id'] ?? 0);
        
        if ($this->tourPhotoModel->delete($photoId)) {
            $_SESSION['success'] = 'Photo deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete photo.';
        }
        
        header('Location: /admin/tours/photos?tour_id=' . $tourId);
        exit;
    }
    
    /**
     * Update tour photo
     */
    public function updatePhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        $photoId = (int)($_POST['photo_id'] ?? 0);
        $tourId = (int)($_POST['tour_id'] ?? 0);
        
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'alt_text' => $_POST['alt_text'] ?? '',
            'type' => $_POST['type'] ?? 'gallery',
            'day' => !empty($_POST['day']) ? (int)$_POST['day'] : null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0
        ];
        
        if ($this->tourPhotoModel->update($photoId, $data)) {
            $_SESSION['success'] = 'Photo updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update photo.';
        }
        
        header('Location: /admin/tours/photos?tour_id=' . $tourId);
        exit;
    }
    
    /**
     * Set featured photo
     */
    public function setFeaturedPhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/tours');
            exit;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            header('Location: /admin/tours');
            exit;
        }
        
        $photoId = (int)($_POST['photo_id'] ?? 0);
        $tourId = (int)($_POST['tour_id'] ?? 0);
        
        if ($this->tourPhotoModel->setFeatured($photoId, $tourId)) {
            $_SESSION['success'] = 'Featured photo updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to set featured photo.';
        }
        
        header('Location: /admin/tours/photos?tour_id=' . $tourId);
        exit;
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload($file, $folder = 'tours')
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadPath = "storage/uploads/$folder/";
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
            return "$folder/$filename";
        }
        
        return false;
    }
}

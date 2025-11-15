<?php

namespace App\Http\Controllers;

use App\Models\Slide;

class SlideController extends Controller
{
    /**
     * Display list of slides
     */
    public function index()
    {
        $slides = Slide::all();

        return view('admin.slides.index', [
            'title' => 'Slides',
            'slides' => $slides,
        ]);
    }

    /**
     * Show create slide form
     */
    public function create()
    {
        return view('admin.slides.create', [
            'title' => 'Create Slide',
        ]);
    }

    /**
     * Store new slide
     */
    public function store()
    {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('slides/create'));
        }

        // Validate required image
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Slide image is required';
            return redirect(admin_url('slides/create'));
        }

        $imagePath = $this->handleImageUpload($_FILES['image']);
        if (!$imagePath) {
            $_SESSION['error'] = 'Failed to upload image';
            return redirect(admin_url('slides/create'));
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'description' => $_POST['description'] ?? '',
            'image' => $imagePath,
            'link_url' => $_POST['link_url'] ?? '',
            'button_text' => $_POST['button_text'] ?? '',
            'sort_order' => isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0,
            'status' => $_POST['status'] ?? 'draft',
        ];

        try {
            $slide = Slide::create($data);
            $_SESSION['success'] = 'Slide created successfully';
            return redirect(admin_url('slides/edit?id=' . $slide->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating slide: ' . $e->getMessage();
            return redirect(admin_url('slides/create'));
        }
    }

    /**
     * Show edit slide form
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Slide ID is required';
            return redirect(admin_url('slides'));
        }

        $slide = Slide::find($id);

        if (!$slide) {
            $_SESSION['error'] = 'Slide not found';
            return redirect(admin_url('slides'));
        }

        return view('admin.slides.edit', [
            'title' => 'Edit Slide',
            'slide' => $slide,
        ]);
    }

    /**
     * Update slide
     */
    public function update()
    {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('slides'));
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Slide ID is required';
            return redirect(admin_url('slides'));
        }

        $slide = Slide::find($id);

        if (!$slide) {
            $_SESSION['error'] = 'Slide not found';
            return redirect(admin_url('slides'));
        }

        $slide->title = $_POST['title'] ?? '';
        $slide->subtitle = $_POST['subtitle'] ?? '';
        $slide->description = $_POST['description'] ?? '';
        $slide->link_url = $_POST['link_url'] ?? '';
        $slide->button_text = $_POST['button_text'] ?? '';
        $slide->sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
        $slide->status = $_POST['status'] ?? 'draft';

        // Handle image upload (optional)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath) {
                // Delete old image
                if ($slide->image) {
                    $oldPath = __DIR__ . '/../../../public/uploads/' . $slide->image;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $slide->image = $imagePath;
            }
        }

        try {
            $slide->save();
            $_SESSION['success'] = 'Slide updated successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating slide: ' . $e->getMessage();
        }

        return redirect(admin_url('slides/edit?id=' . $id));
    }

    /**
     * Delete slide
     */
    public function destroy()
    {
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('slides'));
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'Slide ID is required';
            return redirect(admin_url('slides'));
        }

        $slide = Slide::find($id);

        if (!$slide) {
            $_SESSION['error'] = 'Slide not found';
            return redirect(admin_url('slides'));
        }

        // Delete image file
        if ($slide->image) {
            $imagePath = __DIR__ . '/../../../public/uploads/' . $slide->image;
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        try {
            $slide->delete();
            $_SESSION['success'] = 'Slide deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting slide: ' . $e->getMessage();
        }

        return redirect(admin_url('slides'));
    }

    /**
     * Handle slide image upload while preserving (sanitized) original filename.
     * Returns path relative to /uploads (e.g. "slides/hero.jpg") or false on failure.
     */
    private function handleImageUpload($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        $originalName = $file['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        // Sanitize base name: keep it as close as possible to original,
        // only replacing characters that are invalid on common filesystems
        // (Windows: \ / : * ? " < > |)
        $safeBase = preg_replace('/[\\\\\/\:\*\?\"\<\>\|]/', '_', $baseName);
        $safeBase = trim($safeBase);
        if ($safeBase === '') {
            $safeBase = 'slide';
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/slides/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = $safeBase . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // If a file with this name already exists, append a numeric suffix
        if (file_exists($targetPath)) {
            $suffix = 1;
            while (file_exists($uploadDir . $safeBase . '_' . $suffix . '.' . $extension)) {
                $suffix++;
            }
            $filename = $safeBase . '_' . $suffix . '.' . $extension;
            $targetPath = $uploadDir . $filename;
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'slides/' . $filename;
        }

        return false;
    }
}

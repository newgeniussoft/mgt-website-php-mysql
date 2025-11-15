<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $items = Gallery::all();

        return view('admin.galleries.index', [
            'title' => 'Gallery',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin.galleries.create', [
            'title' => 'Create Gallery Item',
        ]);
    }

    public function store()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('galleries/create'));
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Image is required';
            return redirect(admin_url('galleries/create'));
        }

        $imagePath = $this->handleImageUpload($_FILES['image']);
        if (!$imagePath) {
            $_SESSION['error'] = 'Failed to upload image';
            return redirect(admin_url('galleries/create'));
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'image' => $imagePath,
            'sort_order' => isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0,
            'status' => $_POST['status'] ?? 'draft',
        ];

        try {
            $item = Gallery::create($data);
            $_SESSION['success'] = 'Gallery item created successfully';
            return redirect(admin_url('galleries/edit?id=' . $item->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating gallery item: ' . $e->getMessage();
            return redirect(admin_url('galleries/create'));
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Gallery ID is required';
            return redirect(admin_url('galleries'));
        }

        $item = Gallery::find($id);
        if (!$item) {
            $_SESSION['error'] = 'Gallery item not found';
            return redirect(admin_url('galleries'));
        }

        return view('admin.galleries.edit', [
            'title' => 'Edit Gallery Item',
            'item' => $item,
        ]);
    }

    public function update()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('galleries'));
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Gallery ID is required';
            return redirect(admin_url('galleries'));
        }

        $item = Gallery::find($id);
        if (!$item) {
            $_SESSION['error'] = 'Gallery item not found';
            return redirect(admin_url('galleries'));
        }

        $item->title = $_POST['title'] ?? '';
        $item->description = $_POST['description'] ?? '';
        $item->sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
        $item->status = $_POST['status'] ?? 'draft';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath) {
                if ($item->image) {
                    $oldPath = __DIR__ . '/../../../public/uploads/' . $item->image;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $item->image = $imagePath;
            }
        }

        try {
            $item->save();
            $_SESSION['success'] = 'Gallery item updated successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating gallery item: ' . $e->getMessage();
        }

        return redirect(admin_url('galleries/edit?id=' . $id));
    }

    public function destroy()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('galleries'));
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Gallery ID is required';
            return redirect(admin_url('galleries'));
        }

        $item = Gallery::find($id);
        if (!$item) {
            $_SESSION['error'] = 'Gallery item not found';
            return redirect(admin_url('galleries'));
        }

        if ($item->image) {
            $imagePath = __DIR__ . '/../../../public/uploads/' . $item->image;
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        try {
            $item->delete();
            $_SESSION['success'] = 'Gallery item deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting gallery item: ' . $e->getMessage();
        }

        return redirect(admin_url('galleries'));
    }

    private function handleImageUpload($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        $originalName = $file['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        $safeBase = preg_replace('/[\\\\\/\:\*\?\"\<\>\|]/', '_', $baseName);
        $safeBase = trim($safeBase);
        if ($safeBase === '') {
            $safeBase = 'gallery';
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/galleries/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = $safeBase . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (file_exists($targetPath)) {
            $suffix = 1;
            while (file_exists($uploadDir . $safeBase . '_' . $suffix . '.' . $extension)) {
                $suffix++;
            }
            $filename = $safeBase . '_' . $suffix . '.' . $extension;
            $targetPath = $uploadDir . $filename;
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'galleries/' . $filename;
        }

        return false;
    }
}

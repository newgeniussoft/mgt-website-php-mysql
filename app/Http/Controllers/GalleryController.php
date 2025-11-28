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
        // Collect files (support multiple uploads similar to tour photos)
        $filesToProcess = [];
        if (isset($_FILES['images']) && isset($_FILES['images']['name']) && is_array($_FILES['images']['name'])) {
            $count = count($_FILES['images']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!empty($_FILES['images']['name'][$i]) && $_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $filesToProcess[] = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i],
                    ];
                }
            }
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Backward compatibility for single file field "image"
            $filesToProcess[] = $_FILES['image'];
        }

        if (empty($filesToProcess)) {
            $_SESSION['error'] = 'Image is required';
            return redirect(admin_url('galleries/create'));
        }

        // Optional thumbnail upload (one file applied to created items)
        $thumbPath = null;
        $thumbEnabled = $this->columnExists('galleries', 'thumbnail');
        if ($thumbEnabled && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbPath = $this->handleImageUpload($_FILES['thumbnail'], 'galleries/thumbs');
            if (!$thumbPath) {
                $_SESSION['error'] = 'Failed to upload thumbnail';
                return redirect(admin_url('galleries/create'));
            }
        }

        $sortStart = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
        $status = $_POST['status'] ?? 'draft';
        $titleInput = trim($_POST['title'] ?? '');
        $description = $_POST['description'] ?? '';

        $createdItems = [];
        $i = 0;
        foreach ($filesToProcess as $file) {
            $imagePath = $this->handleImageUpload($file, 'galleries');
            if (!$imagePath) {
                continue;
            }

            $defaultTitle = pathinfo($file['name'], PATHINFO_FILENAME);
            $title = (count($filesToProcess) === 1 && $titleInput !== '') ? $titleInput : $defaultTitle;

            $data = [
                'title' => $title,
                'description' => $description,
                'image' => $imagePath,
                'sort_order' => $sortStart + $i,
                'status' => $status,
            ];
            if ($thumbEnabled && $thumbPath) {
                $data['thumbnail'] = $thumbPath;
            }

            try {
                $item = Gallery::create($data);
                $createdItems[] = $item;
            } catch (\Exception $e) {
                // continue processing other files
            }
            $i++;
        }

        if (count($createdItems) > 0) {
            $_SESSION['success'] = count($createdItems) . ' gallery item' . (count($createdItems) > 1 ? 's' : '') . ' created successfully';
            if (count($createdItems) === 1) {
                return redirect(admin_url('galleries/edit?id=' . $createdItems[0]->id));
            }
            return redirect(admin_url('galleries'));
        } else {
            $_SESSION['error'] = 'Failed to create gallery items. Please try again.';
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
            $imagePath = $this->handleImageUpload($_FILES['image'], 'galleries');
            if ($imagePath) {
                if ($item->image) {
                    $this->deleteIfUnreferenced($item->image, 'image');
                }
                $item->image = $imagePath;
            }
        }

        if ($this->columnExists('galleries', 'thumbnail') && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbPath = $this->handleImageUpload($_FILES['thumbnail'], 'galleries/thumbs');
            if ($thumbPath) {
                if ($item->thumbnail) {
                    $this->deleteIfUnreferenced($item->thumbnail, 'thumbnail');
                }
                $item->thumbnail = $thumbPath;
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
            $this->deleteIfUnreferenced($item->image, 'image');
        }
        if ($this->columnExists('galleries', 'thumbnail') && $item->thumbnail) {
            $this->deleteIfUnreferenced($item->thumbnail, 'thumbnail');
        }

        try {
            $item->delete();
            $_SESSION['success'] = 'Gallery item deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting gallery item: ' . $e->getMessage();
        }

        return redirect(admin_url('galleries'));
    }

    private function handleImageUpload($file, $folder = 'galleries')
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        $originalName = $file['name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        // Preserve original basename; only de-dupe by adding numeric suffix
        $uploadDir = __DIR__ . '/../../../public/uploads/' . rtrim($folder, '/');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Ensure trailing slash
        $uploadDir = rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR;

        $candidate = $baseName . '.' . $extension;
        $filename = $candidate;
        $suffix = 1;
        while (file_exists($uploadDir . $filename)) {
            $filename = $baseName . '-' . $suffix . '.' . $extension;
            $suffix++;
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return rtrim($folder, '/') . '/' . $filename;
        }

        return false;
    }

    private function deleteIfUnreferenced($relativePath, $column = 'image')
    {
        if (empty($relativePath)) {
            return;
        }
        $fullPath = __DIR__ . '/../../../public/uploads/' . $relativePath;
        try {
            $count = 0;
            if ($column === 'thumbnail' && !$this->columnExists('galleries', 'thumbnail')) {
                // No DB references possible, safe to delete file
                $count = 0;
            } else {
                $model = new Gallery();
                $conn = $model->getConnection();
                $stmt = $conn->prepare("SELECT COUNT(*) FROM galleries WHERE `$column` = ?");
                $stmt->execute([$relativePath]);
                $count = (int)$stmt->fetchColumn();
            }
            if ($count <= 1 && file_exists($fullPath)) {
                @unlink($fullPath);
            }
        } catch (\Exception $e) {
            // On error, do not delete to be safe
        }
    }

    private function columnExists($table, $column)
    {
        try {
            $model = new Gallery();
            $conn = $model->getConnection();
            $stmt = $conn->prepare('SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
            $stmt->execute([$table, $column]);
            return ((int)$stmt->fetchColumn()) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $items = Blog::getAll();

        return view('admin.blogs.index', [
            'title' => 'Blogs',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin.blogs.create', [
            'title' => 'Create Blog',
        ]);
    }

    public function store()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('blogs/create'));
        }

        // Validate required fields
        $title = trim($_POST['title'] ?? '');
        $titleEs = trim($_POST['title_es'] ?? '');
        $shortTexte = trim($_POST['short_texte'] ?? '');
        $shortTexteEs = trim($_POST['short_texte_es'] ?? '');
        $description = $_POST['description'] ?? '';
        $descriptionEs = $_POST['description_es'] ?? '';

        if (empty($title) || empty($titleEs)) {
            $_SESSION['error'] = 'Title (both languages) is required';
            return redirect(admin_url('blogs/create'));
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Image is required';
            return redirect(admin_url('blogs/create'));
        }

        $imagePath = $this->handleImageUpload($_FILES['image'], 'blogs');
        if (!$imagePath) {
            $_SESSION['error'] = 'Failed to upload image';
            return redirect(admin_url('blogs/create'));
        }

        try {
            $item = Blog::create([
                'title' => $title,
                'title_es' => $titleEs,
                'short_texte' => $shortTexte,
                'short_texte_es' => $shortTexteEs,
                'description' => $description,
                'description_es' => $descriptionEs,
                'image' => $imagePath,
            ]);

            $_SESSION['success'] = 'Blog created successfully';
            return redirect(admin_url('blogs/edit?id=' . $item->id));
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to create blog: ' . $e->getMessage();
            return redirect(admin_url('blogs/create'));
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Blog ID is required';
            return redirect(admin_url('blogs'));
        }

        $item = Blog::find($id);
        if (!$item) {
            $_SESSION['error'] = 'Blog not found';
            return redirect(admin_url('blogs'));
        }

        return view('admin.blogs.edit', [
            'title' => 'Edit Blog',
            'item' => $item,
        ]);
    }

    public function update()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('blogs'));
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Blog ID is required';
            return redirect(admin_url('blogs'));
        }

        $item = Blog::find($id);
        if (!$item) {
            $_SESSION['error'] = 'Blog not found';
            return redirect(admin_url('blogs'));
        }

        $item->title = trim($_POST['title'] ?? '');
        $item->title_es = trim($_POST['title_es'] ?? '');
        $item->short_texte = trim($_POST['short_texte'] ?? '');
        $item->short_texte_es = trim($_POST['short_texte_es'] ?? '');
        $item->description = $_POST['description'] ?? '';
        $item->description_es = $_POST['description_es'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image'], 'blogs');
            if ($imagePath) {
                if ($item->image) {
                    $this->deleteIfUnreferenced($item->image);
                }
                $item->image = $imagePath;
            }
        }

        try {
            $item->save();
            $_SESSION['success'] = 'Blog updated successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating blog: ' . $e->getMessage();
        }

        return redirect(admin_url('blogs/edit?id=' . $id));
    }

    public function destroy()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid CSRF token';
            return redirect(admin_url('blogs'));
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'Blog ID is required';
            return redirect(admin_url('blogs'));
        }

        $item = Blog::find($id);
        if (!$item) {
            $_SESSION['error'] = 'Blog not found';
            return redirect(admin_url('blogs'));
        }

        if ($item->image) {
            $this->deleteIfUnreferenced($item->image);
        }

        try {
            $item->delete();
            $_SESSION['success'] = 'Blog deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error deleting blog: ' . $e->getMessage();
        }

        return redirect(admin_url('blogs'));
    }

    private function handleImageUpload($file, $folder = 'blogs')
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

    private function deleteIfUnreferenced($relativePath)
    {
        if (empty($relativePath)) {
            return;
        }
        $fullPath = __DIR__ . '/../../../public/uploads/' . $relativePath;
        try {
            $model = new Blog();
            $conn = $model->getConnection();
            $stmt = $conn->prepare("SELECT COUNT(*) FROM blogs WHERE image = ?");
            $stmt->execute([$relativePath]);
            $count = (int)$stmt->fetchColumn();
            if ($count <= 1 && file_exists($fullPath)) {
                @unlink($fullPath);
            }
        } catch (\Exception $e) {
            // On error, do not delete to be safe
        }
    }
}

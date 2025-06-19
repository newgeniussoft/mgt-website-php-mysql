<?php
// app/http/controllers/GalleryController.php
require_once __DIR__ . '/../../models/Gallery.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class GalleryController extends Controller {
    public function index() {
        return $this->applyMiddleware(function() {
            $galleryModel = new Gallery();
            $galleries = $galleryModel->all();
            $success = '';
            $error = '';
            $uploadDir = realpath(__DIR__ . '/../../../') . '/assets/img/uploads/galleries/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

            // Handle create
            if (isset($_POST['gallery_action']) && $_POST['gallery_action'] === 'create') {
                $image = '';
                if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
                    $imgName = uniqid('gallery_') . '_' . basename($_FILES['gallery_image']['name']);
                    $imgPath = $uploadDir . $imgName;
                    if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $imgPath)) {
                        $image = 'img/uploads/galleries/' . $imgName;
                        $galleryModel->create(['image' => $image]);
                        header('Location: /access/gallery');
                        exit;
                    } else {
                        $error = 'Failed to upload image.';
                    }
                } else {
                    $error = 'No image uploaded.';
                }
            }

            // Handle edit
            if (isset($_POST['gallery_action']) && $_POST['gallery_action'] === 'edit') {
                $gallery_id = $_POST['gallery_id'] ?? null;
                $old_image = $_POST['old_image'] ?? '';
                $image = $old_image;
                if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
                    $imgName = uniqid('gallery_') . '_' . basename($_FILES['gallery_image']['name']);
                    $imgPath = $uploadDir . $imgName;
                    if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $imgPath)) {
                        $image = 'img/uploads/galleries/' . $imgName;
                        // Delete old image file
                        if ($old_image && file_exists($uploadDir . basename($old_image))) {
                            @unlink($uploadDir . basename($old_image));
                        }
                    } else {
                        $error = 'Failed to upload new image.';
                    }
                }
                if ($gallery_id && !$error) {
                    $galleryModel->update($gallery_id, ['image' => $image]);
                    header('Location: /access/gallery');
                    exit;
                }
            }

            // Handle delete
            if (isset($_POST['gallery_action']) && $_POST['gallery_action'] === 'delete') {
                $gallery_id = $_POST['gallery_id'] ?? null;
                $image = $_POST['old_image'] ?? '';
                if ($gallery_id) {
                    $galleryModel->delete($gallery_id);
                    // Delete image file
                    if ($image && file_exists($uploadDir . basename($image))) {
                        @unlink($uploadDir . basename($image));
                    }
                    header('Location: /access/gallery');
                    exit;
                }
            }

            echo $this->view('admin.gallery', [
                'title' => 'Gallery',
                'galleries' => $galleries,
                'success' => $success,
                'error' => $error
            ]);
        }, [new AuthMiddleware()]);
    }
}

<?php
include_once __DIR__ . '/../../models/Gallery.php';
include_once __DIR__ . '/contracts/GalleryRepositoryInterface.php';

class GalleryRepository implements GalleryRepositoryInterface {
    public function all() {
        $gallery = new Gallery();
        return $gallery->all();
    }

    public function create($data) {
        $gallery = new Gallery();
        return $gallery->create($data);
    }
}
?>
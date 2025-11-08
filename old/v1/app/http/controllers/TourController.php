<?php
require_once __DIR__ . '/../../models/Tour.php';
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../../models/Review.php';
require_once __DIR__ . '/../../models/TourDetail.php';
require_once __DIR__ . '/../../models/TourHighlight.php';
require_once __DIR__ . '/../../models/TourPrice.php';
require_once __DIR__ . '/../../models/TourPhoto.php';
require_once 'Controller.php';

class TourController extends Controller {
    protected $tourModel;

    public function __construct($lang = 'en') {
        parent::__construct(new Tour(), $lang);
        $this->tourModel = new Tour();
    }

    // Admin: List all tours
    public function adminIndex() {
        $user = $_SESSION['user'];
        $tours = $this->tourModel->all();
        echo $this->view('admin.pages.tours.index', ['user' => $user, 'tours' => $tours]);
    }

    // Admin: Show create form
    public function adminCreate() {
        $user = $_SESSION['user'];
        // No tour_details on create
        echo $this->view('admin.pages.tours.create', ['user' => $user]);
    }

    // Admin: Store new tour
    public function adminStore() {
        $data = $_POST;
        // Ensure show_in_home is 0 or 1
        $data['show_in_home'] = isset($data['show_in_home']) && $data['show_in_home'] == 1 ? 1 : 0;
        $tourDetails = isset($data['tour_details']) ? $data['tour_details'] : [];
        unset($data['tour_details']);
        $tourHighlights = isset($data['tour_highlights']) ? $data['tour_highlights'] : [];
        unset($data['tour_highlights']);
        $tourPrices = isset($data['tour_prices']) ? $data['tour_prices'] : [];
        unset($data['tour_prices']);
        $tourPhotos = isset($data['tour_photos']) ? $data['tour_photos'] : [];
        unset($data['tour_photos']);
        // Handle file uploads
        foreach (["image", "image_cover", "map"] as $fileField) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                $filename = time() . '_' . basename($_FILES[$fileField]["name"]);
                $targetDir = __DIR__ . '/../../../assets/img/uploads/tours/'.strtolower(str_replace(" ", "_", $data['name']));
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $upImg = $this->upload($targetDir, $_FILES[$fileField]);
                if ($upImg->success) {
                    $data[$fileField] = 'img/uploads/tours/'.strtolower(str_replace(" ", "_", $data['name'])) . '/' . $upImg->filename;
                }
            }
        }
        $this->tourModel->create($data);
        if (!empty($_FILES['tour_photo_files']['name'][0])) {
            $this->saveTourPhotos($data['name'], $_FILES['tour_photo_files']);
        }
        if (!empty($tourDetails)) {
            $this->saveTourDetails($data['name'], $tourDetails);
        }
        if (!empty($tourHighlights)) {
            $this->saveTourHighlights($data['name'], $tourHighlights);
        }
        if (!empty($tourPrices)) {
            $this->saveTourPrices($data['name'], $tourPrices);
        }
        $_SESSION['success'] = 'Tour created successfully!';
        header('Location: /' . $_ENV['PATH_ADMIN'] . '/tours');
        exit;
    }

    // Admin: Show edit form
    public function adminEdit($id) {
        
        $user = $_SESSION['user'];
        $tour = (object) $this->tourModel->get($id);
        $tourDetailModel = new TourDetail();
        $tour_details = $tourDetailModel->where('name_tour', $tour->name);
        $tourHighlightModel = new TourHighlight();
        $tour_highlights = $tourHighlightModel->where('name_tour', $tour->name);
        $tourPriceModel = new TourPrice();
        $tour_prices = $tourPriceModel->where('name_tour', $tour->name);
        $tourPhotoModel = new TourPhoto();
        $tour_photos = $tourPhotoModel->where('name_tour', $tour->name);
        echo $this->view('admin.pages.tours.edit', [
            'user' => $user,
            'tour' => $tour,
            'tour_details' => $tour_details,
            'tour_highlights' => $tour_highlights,
            'tour_prices' => $tour_prices,
            'tour_photos' => $tour_photos
        ]);
    }

    // Admin: Update tour
    public function adminUpdate($id) {
        $user = $_SESSION['user'];
        $data = $_POST;
        // Ensure show_in_home is 0 or 1
        $data['show_in_home'] = isset($data['show_in_home']) && $data['show_in_home'] == 1 ? 1 : 0;
        $tour = (object) $this->tourModel->get($id);
        // Ensure show_in_home is 0 or 1
        $data['show_in_home'] = isset($data['show_in_home']) && $data['show_in_home'] == 1 ? 1 : 0;
        $tourDetails = isset($data['tour_details']) ? $data['tour_details'] : [];
        unset($data['tour_details']);
        $tourHighlights = isset($data['tour_highlights']) ? $data['tour_highlights'] : [];
        unset($data['tour_highlights']);
        $tourPrices = isset($data['tour_prices']) ? $data['tour_prices'] : [];
        unset($data['tour_prices']);
        $tourPhotos = isset($data['tour_photos']) ? $data['tour_photos'] : [];
        unset($data['tour_photos']);
        unset($data['delete_tour_photo_ids']);
        // Handle file uploads
        foreach (["image", "image_cover", "map"] as $fileField) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                $filename = time() . '_' . basename($_FILES[$fileField]["name"]);
                $targetDir = __DIR__ . '/../../../assets/img/uploads/tours/'.strtolower(str_replace(" ", "_", $data['name'])).'/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $upImg = $this->upload($targetDir, $_FILES[$fileField]);
                if ($upImg->success) {
                    $data[$fileField] = 'img/uploads/tours/'.strtolower(str_replace(" ", "_", $data['name'])) . '/' . $upImg->filename;
                }   
            }
        }
        $this->tourModel->update($id, $data);
        $this->updateTourDetails($tour->name, $tourDetails);
        $this->updateTourHighlights($tour->name, $tourHighlights);
        $this->updateTourPrices($tour->name, $tourPrices);
        if (isset($_POST['delete_tour_photo_ids']) && is_array($_POST['delete_tour_photo_ids'])) {
            foreach ($_POST['delete_tour_photo_ids'] as $photoId) {
                $this->deleteTourPhoto($photoId);
            }
        }
        if (!empty($_FILES['tour_photo_files']['name'][0])) {
            $this->saveTourPhotos($tour->name, $_FILES['tour_photo_files']);
        }
        $_SESSION['success'] = 'Tour updated successfully!';
        header('Location: /' . $_ENV['PATH_ADMIN'] . '/tours');
        exit;
    }

    // Helper to save tour photos on create
    private function saveTourPhotos($name_tour, $files) {
        $tourPhotoModel = new TourPhoto();
        $count = count($files['name']);
        $targetDir = __DIR__ . '/../../../assets/img/uploads/tours/'.strtolower(str_replace(" ", "_", $name_tour)).'/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $filename = time() . '_' . basename($files['name'][$i]);
                move_uploaded_file($files['tmp_name'][$i], $targetDir . $filename);
                $tourPhotoModel->create([
                    'name_tour' => $name_tour,
                    'image' =>  'img/uploads/tours/'.strtolower(str_replace(" ", "_", $name_tour)) . '/' . $filename
                ]);
            }
        }
    }

    // Helper to delete a tour photo by id
    private function deleteTourPhoto($photoId) {
        $tourPhotoModel = new TourPhoto();
        $photo = $tourPhotoModel->where('id', $photoId);
        if ($photo && isset($photo[0]->image)) {
            $file = __DIR__ . '/../../../assets/img/uploads/tours/'.strtolower(str_replace(" ", "_", $photo[0]->name_tour)).'/' . $photo[0]->image;
            if (file_exists($file)) {
                @unlink($file);
            }
        }
        $tourPhotoModel->delete($photoId);
    }

    // Helper to save tour prices on create
    private function saveTourPrices($name_tour, $prices) {
        $tourPriceModel = new TourPrice();
        foreach ($prices as $price) {
            $price['name_tour'] = $name_tour;
            $tourPriceModel->create($price);
        }
    }

    // Helper to update tour prices on update
    private function updateTourPrices($name_tour, $prices) {
        $tourPriceModel = new TourPrice();
        // Fetch all existing prices for this tour
        $existing = $tourPriceModel->where('name_tour', $name_tour);
        $existingMap = [];
        foreach ($existing as $item) {
            $existingMap[$item->id] = $item;
        }
        $sentIds = [];
        foreach ($prices as $price) {
            if (isset($price['id']) && $price['id']) {
                $sentIds[] = $price['id'];
                $tourPriceModel->update($price['id'], $price);
            } else {
                $price['name_tour'] = $name_tour;
                $tourPriceModel->create($price);
            }
        }
        // Delete removed prices
        foreach ($existingMap as $id => $item) {
            if (!in_array($id, $sentIds)) {
                $tourPriceModel->delete($id);
            }
        }
    }

    // Helper to save tour highlights on create
    private function saveTourHighlights($name_tour, $highlights) {
        $tourHighlightModel = new TourHighlight();
        foreach ($highlights as $highlight) {
            $highlight['name_tour'] = $name_tour;
            $tourHighlightModel->create($highlight);
        }
    }

    // Helper to update tour highlights on update
    private function updateTourHighlights($name_tour, $highlights) {
        $tourHighlightModel = new TourHighlight();
        // Fetch all existing highlights for this tour
        $existing = $tourHighlightModel->where('name_tour', $name_tour);
        $existingMap = [];
        foreach ($existing as $item) {
            $existingMap[$item->id] = $item;
        }
        $sentIds = [];
        foreach ($highlights as $highlight) {
            if (isset($highlight['id']) && $highlight['id']) {
                $sentIds[] = $highlight['id'];
                $tourHighlightModel->update($highlight['id'], $highlight);
            } else {
                $highlight['name_tour'] = $name_tour;
                $tourHighlightModel->create($highlight);
            }
        }
        // Delete removed highlights
        foreach ($existingMap as $id => $item) {
            if (!in_array($id, $sentIds)) {
                $tourHighlightModel->delete($id);
            }
        }
    }

    // Helper to save tour details on create
    private function saveTourDetails($name_tour, $details) {
        $tourDetailModel = new TourDetail();
        foreach ($details as $detail) {
            $detail['name_tour'] = $name_tour;
            $tourDetailModel->create($detail);
        }
    }

    // Helper to update tour details on update
    private function updateTourDetails($name_tour, $details) {
        $tourDetailModel = new TourDetail();
        // Fetch all existing details for this tour
        $existing = $tourDetailModel->where('name_tour', $name_tour);
        $existingMap = [];
        foreach ($existing as $item) {
            $existingMap[$item->id] = $item;
        }
        $sentIds = [];
        foreach ($details as $detail) {
            if (isset($detail['id']) && $detail['id']) {
                $sentIds[] = $detail['id'];
                $tourDetailModel->update($detail['id'], $detail);
            } else {
                $detail['name_tour'] = $name_tour;
                $tourDetailModel->create($detail);
            }
        }
        // Delete removed details
        foreach ($existingMap as $id => $item) {
            if (!in_array($id, $sentIds)) {
                $tourDetailModel->delete($id);
            }
        }
    }

    // Admin: Delete tour
    public function adminDestroy($id) {
        $this->tourModel->delete($id);
        $_SESSION['success'] = 'Tour deleted successfully!';
        header('Location: /' . $_ENV['PATH_ADMIN'] . '/tours');
        exit;
    }
}

?>

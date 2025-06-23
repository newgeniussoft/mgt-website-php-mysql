<?php
require_once 'Controller.php';
require_once __DIR__ . '/../../models/Tour.php';
require_once __DIR__ . '/../../utils/helpers/helper.php';

class AdminTourController extends Controller
{
    public function index()
    {
        $tourModel = new Tour();
        $tours = $tourModel->all();
        // Convert objects to arrays for Blade compatibility
        $toursArr = array_map(function($item) { return (array)$item; }, $tours);
        echo $this->view('admin.tours', ['tours' => $toursArr]);
    }

    // Show create tour form
    public function create()
    {
        $tourModel = new Tour();
        $tours = $tourModel->all();
        // Convert objects to arrays for Blade compatibility
        $toursArr = array_map(function($item) { return (array)$item; }, $tours);
        echo $this->view('admin.tours_create', ['tours' => $toursArr]);
    }

    public function store()
    {
        // Debug logging for troubleshooting
        file_put_contents(__DIR__ . '/debug_tour_store.txt',
            'POST: ' . print_r($_POST, true) .
            "\nFILES: " . print_r($_FILES, true) .
            "\nSERVER: " . print_r($_SERVER, true) .
            "\n---\n",
            FILE_APPEND
        );
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fields = [
                'name', 'name_es', 'title', 'title_es', 'subtitle', 'subtitle_es',
                'description', 'description_es', 'text_for_customer', 'text_for_customer_es',
                'itinerary', 'meta_title', 'meta_title_es', 'meta_description', 'meta_description_es',
                'meta_keywords', 'meta_keywords_es', 'path'
            ];
            $data = [];
            foreach ($fields as $field) {
                $data[$field] = $_POST[$field] ?? '';
            }
            // Handle file uploads
            $uploadDir = 'assets/img/uploads/tours/';
            $dbDir = 'img/uploads/tours/';
            $data['image'] = $this->handleUpload('image', $uploadDir, null, $dbDir);
            $data['image_cover'] = $this->handleUpload('image_cover', $uploadDir, null, $dbDir);
            $data['map'] = $this->handleUpload('map', $uploadDir, null, $dbDir);
            // Insert into DB
            $tourModel = new Tour();
            $tourModel->insert($data);
           // header('Location: ' . route('access/tours'));
            exit;
        }
    }

    public function edit($id)
    {
        require_once __DIR__ . '/../../models/TourHighlight.php';
        require_once __DIR__ . '/../../models/TourDetail.php';
        require_once __DIR__ . '/../../models/TourPrice.php';
        $tourModel = new Tour();
        $tour = $tourModel->find($id);
        $highlightModel = new TourHighlight();
        $detailModel = new TourDetail();
        $priceModel = new TourPrice();
        $highlights = $highlightModel->where('name_tour', $tour->name);
        $details = $detailModel->where('name_tour', $tour->name);
        $prices = $priceModel->where('name_tour', $tour->name);
        echo $this->view('admin.tours_edit', [
            'tour' => (array)$tour,
            'highlights' => $highlights,
            'details' => $details,
            'prices' => $prices
        ]);
    }

    // --- CRUD for TourHighlight ---
    public function storeHighlight($tourId)
    {
        require_once __DIR__ . '/../../models/TourHighlight.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name_tour' => $_POST['name_tour'],
                'texte' => $_POST['texte'],
                'texte_es' => $_POST['texte_es']
            ];
            $highlightModel = new TourHighlight();
            $highlightModel->insert($data);
            header('Location: ' . route('access/tours/edit/' . $tourId));
            exit;
        }
    }
    public function updateHighlight($id, $tourId)
    {
        require_once __DIR__ . '/../../models/TourHighlight.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'texte' => $_POST['texte'],
                'texte_es' => $_POST['texte_es']
            ];
            $highlightModel = new TourHighlight();
            $highlightModel->update($id, $data);
            header('Location: ' . route('access/tours/edit/' . $tourId));
            exit;
        }
    }
    public function deleteHighlight($id, $tourId)
    {
        require_once __DIR__ . '/../../models/TourHighlight.php';
        $highlightModel = new TourHighlight();
        $highlightModel->delete($id);
        header('Location: ' . route('access/tours/edit/' . $tourId));
        exit;
    }

    // --- CRUD for TourDetail ---
    public function storeDetail($tourId)
    {
        require_once __DIR__ . '/../../models/TourDetail.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'day' => $_POST['day'],
                'name_tour' => $_POST['name_tour'],
                'title_es' => $_POST['title_es'],
                'details_es' => $_POST['details_es'],
                'name_tours_es' => $_POST['name_tours_es']
            ];
            $detailModel = new TourDetail();
            $detailModel->insert($data);
            header('Location: ' . route('access/tours/edit/' . $tourId));
            exit;
        }
    }
    public function updateDetail($id, $tourId)
    {
        require_once __DIR__ . '/../../models/TourDetail.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'day' => $_POST['day'],
                'title_es' => $_POST['title_es'],
                'details_es' => $_POST['details_es'],
                'name_tours_es' => $_POST['name_tours_es']
            ];
            $detailModel = new TourDetail();
            $detailModel->update($id, $data);
            header('Location: ' . route('access/tours/edit/' . $tourId));
            exit;
        }
    }
    public function deleteDetail($id, $tourId)
    {
        require_once __DIR__ . '/../../models/TourDetail.php';
        $detailModel = new TourDetail();
        $detailModel->delete($id);
        header('Location: ' . route('access/tours/edit/' . $tourId));
        exit;
    }

    // --- CRUD for TourPrice ---
    public function storePrice($tourId)
    {
        require_once __DIR__ . '/../../models/TourPrice.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name_tour' => $_POST['name_tour'],
                'type' => $_POST['type'],
                'texte' => $_POST['texte'],
                'texte_es' => $_POST['texte_es']
            ];
            $priceModel = new TourPrice();
            $priceModel->insert($data);
            header('Location: ' . route('access/tours/edit/' . $tourId));
            exit;
        }
    }
    public function updatePrice($id, $tourId)
    {
        require_once __DIR__ . '/../../models/TourPrice.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'type' => $_POST['type'],
                'texte' => $_POST['texte'],
                'texte_es' => $_POST['texte_es']
            ];
            $priceModel = new TourPrice();
            $priceModel->update($id, $data);
            header('Location: ' . route('access/tours/edit/' . $tourId));
            exit;
        }
    }
    public function deletePrice($id, $tourId)
    {
        require_once __DIR__ . '/../../models/TourPrice.php';
        $priceModel = new TourPrice();
        $priceModel->delete($id);
        header('Location: ' . route('access/tours/edit/' . $tourId));
        exit;
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fields = [
                'name', 'name_es', 'title', 'title_es', 'subtitle', 'subtitle_es',
                'description', 'description_es', 'text_for_customer', 'text_for_customer_es',
                'itinerary', 'meta_title', 'meta_title_es', 'meta_description', 'meta_description_es',
                'meta_keywords', 'meta_keywords_es', 'path'
            ];
            $data = [];
            foreach ($fields as $field) {
                $data[$field] = $_POST[$field] ?? '';
            }
            $uploadDir = 'assets/img/uploads/tours/';
            $dbDir = 'img/uploads/tours/';
            $data['image'] = $this->handleUpload('image', $uploadDir, $_POST['old_image'] ?? null, $dbDir);
            $data['image_cover'] = $this->handleUpload('image_cover', $uploadDir, $_POST['old_image_cover'] ?? null, $dbDir);
            $data['map'] = $this->handleUpload('map', $uploadDir, $_POST['old_map'] ?? null, $dbDir);
            $tourModel = new Tour();
            $tourModel->update($id, $data);
            header('Location: ' . route('access/tours'));
            exit;
        }
    }

    public function delete($id)
    {
        $tourModel = new Tour();
        $tour = $tourModel->find($id);
        // Optionally delete files
        if ($tour) {
            $this->deleteFile($tour->image);
            $this->deleteFile($tour->image_cover);
            $this->deleteFile($tour->map);
        }
        $tourModel->delete($id);
        header('Location: ' . route('access/tours'));
        exit;
    }

    // --- Helpers ---
    private function handleUpload($field, $dir, $oldFile = null, $dbDir = null)
    {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $filename = uniqid() . '_' . basename($_FILES[$field]['name']);
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir;
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $target = $targetDir . $filename;
            move_uploaded_file($_FILES[$field]['tmp_name'], $target);
            // Optionally delete old file
            if ($oldFile && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $oldFile)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $oldFile);
            }
            // Save only the relative path for DB (without 'assets/')
            $dbPath = $dbDir ? $dbDir . $filename : $dir . $filename;
            return $dbPath;
        }
        return $oldFile;
    }

    private function deleteFile($path)
    {
        if ($path && file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $path);
        }
    }
}

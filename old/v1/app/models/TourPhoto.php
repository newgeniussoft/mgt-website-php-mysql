<?php
require_once __DIR__ . '/../models/Model.php';

class TourPhoto extends Model {
    private $table = 'tour_photos';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
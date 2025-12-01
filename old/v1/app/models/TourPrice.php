<?php
require_once __DIR__ . '/../models/Model.php';

class TourPrice extends Model {
    private $table = 'tour_price';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
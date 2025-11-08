<?php
require_once __DIR__ . '/../models/Model.php';

class TourDetail extends Model {
    private $table = 'tour_details';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
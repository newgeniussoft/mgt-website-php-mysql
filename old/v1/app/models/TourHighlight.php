<?php
require_once __DIR__ . '/../models/Model.php';

class TourHighlight extends Model {
    private $table = 'tour_highlights';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
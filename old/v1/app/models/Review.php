<?php
require_once __DIR__ . '/../models/Model.php';

class Review extends Model {
    private $table = 'reviews';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
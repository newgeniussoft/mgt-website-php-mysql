<?php
require_once __DIR__ . '/../models/Model.php';

class Slide extends Model {
    private $table = 'slides';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
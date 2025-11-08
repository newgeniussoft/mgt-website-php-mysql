<?php
require_once __DIR__ . '/../models/Model.php';

class Video extends Model {
    private $table = 'videos';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
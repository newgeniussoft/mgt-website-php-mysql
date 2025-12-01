<?php
require_once __DIR__ . '/../models/Model.php';

class Gallery extends Model {
    private $table = 'galleries';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
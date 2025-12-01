<?php
require_once __DIR__ . '/../models/Model.php';
class Tour extends Model {
    private $table = 'tours';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
<?php
require_once __DIR__ . '/../models/Model.php';

class Visitor extends Model {
    private $table = 'visitors';

    public function __construct() {
        parent::__construct($this->table);
    }
}

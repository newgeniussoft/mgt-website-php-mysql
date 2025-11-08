<?php
require_once __DIR__ . '/../models/Model.php';

class Service extends Model {
    private $table = 'services';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
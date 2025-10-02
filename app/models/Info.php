<?php
require_once __DIR__ . '/../models/Model.php';

class Info extends Model {
    private $table = 'info';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
<?php
require_once __DIR__ . '/../models/Model.php';

class Content extends Model {
    private $table = 'contents';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
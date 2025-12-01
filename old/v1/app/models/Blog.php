<?php
require_once __DIR__ . '/../models/Model.php';

class Blog extends Model {
    protected $table = 'blogs';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>

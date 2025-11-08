<?php
require_once __DIR__ . '/../models/Model.php';
class Page extends Model {
    private $table = 'pages';

    public function __construct() {
        parent::__construct($this->table);
    }

    public function getByPath($path) {
        return $this->where('path', $path)[0];
    }
}
?>
<?php
require_once __DIR__ . '/../models/Model.php';

class SocialMedia extends Model {
    private $table = 'social_media';

    public function __construct() {
        parent::__construct($this->table);
    }
}
?>
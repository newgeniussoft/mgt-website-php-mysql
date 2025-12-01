<?php
require_once __DIR__ . '/../models/Model.php';

class User extends Model {
    private $table = 'users';

    public function __construct() {
        parent::__construct($this->table);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }
}

?>
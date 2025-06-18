<?php
    require_once __DIR__ . '/../config/database.php';

    class Model {
        public $conn;
        public $table_name;
        public $stmt;
        public $db;
    
        public function __construct($table)
        {
            $this->table_name = $table;
            $database = new Database();
            $this->conn = $database->getConnection();
            $this->db = $database;
            }

        private function execute($query) {
            $this->stmt = $this->conn->prepare($query);
            return $this->stmt->execute();
        }
    
        public function fetchAll()
        {
            $this->execute("SELECT * FROM " . $this->table_name);
            $data = array();
            while($item = $this->stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $item;
            }
            return $data;
        }
        public function fetchBy($col, $value) {
            $this->execute("SELECT * FROM " . $this->table_name." WHERE ".$col." = '".$value."'");
            $tour = null;
            while($item = $this->stmt->fetch(PDO::FETCH_OBJ)) {
                $tour = $item;
            }
            return $tour;
        }
    }

?>
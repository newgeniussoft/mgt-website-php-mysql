<?php
require_once __DIR__ . '/../config/database.php';

class Model {
    protected $db;
    public $table_name;

    public function __construct($table_name) {
        $this->db = Database::getInstance()->getConn();
        $this->table_name = $table_name;
    }

    public function create($data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table_name} ({$columns}) VALUES ({$placeholders})";
        return $this->query($sql, array_values($data));
    }

    public function sql($sql) {
        return $this->query($sql);
    }

    public function update($id, $data) {
        $columns = $this->columns();
        $col_not_allowed = ['id', 'created_at', 'updated_at'];
        $nData = [];
       /* foreach($columns as $col) {
            if (!in_array($col, $col_not_allowed)) {
                $nData[$col] = $data[$col];
            }
        }*/
        $set = implode(',', array_map(function($key) use ($data) { return " {$key} = ?"; }, array_keys($data))); 
        $sql = "UPDATE {$this->table_name} SET {$set} WHERE id = ?";
       //echo($sql);
       $params = [];
       foreach($data as $item) {
        $params[] = $item;
       }
       $params[]  = $id;
       
       // print_r($params);


     //  echo($sql);
       
       $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
       /*foreach(array_keys($data) as $key) {
            echo('<b>'.$key.'</b>: '.$data[$key].'<br>');
       }*/
      /*/$stmt = $this->db->prepare($sql);
        $stmt->execute();*/
        // return $this->query($sql);
    }

    public function where($col, $value) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table_name." WHERE ".$col." = '".$value."'");
        $stmt->execute();
        $data = [];
        while($item = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = (object) $item;
        }
        return $data;
    }

    public function like($col, $value) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table_name." WHERE ".$col." LIKE '%".$value."%' ");
        $stmt->execute();
        $data = [];
        while($item = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = (object) $item;
        }
        return $data;
    }

    public function limit($limit) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table_name." LIMIT ".$limit);
        $stmt->execute();
        $data = [];
        while($item = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = (object) $item;
        }
        return $data;
    }

    public function limitOffset($limit, $offset, $condition = '', $order = 'ASC') {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table_name." ".$condition." ORDER BY id ".$order." LIMIT ".$limit." OFFSET ".$offset);
        $stmt->execute();
        $data = [];
        while($item = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = (object) $item;
        }
        return $data;
    }

    public function columns() {
        $columns = [];
        $stmt = $this->db->query("SHOW COLUMNS FROM `$this->table_name`");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }
        return $columns;
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table_name} WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function all($order = 'ASC') {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id {$order}";
        return $this->toObjects($this->fetchAll($sql));
    }

    public function toObjects($data) {
        $objects = [];
        foreach ($data as $row) {
            $objects[] = (object)$row;
        }
        return $objects;
    }

    public function get($id) {
        $sql = "SELECT * FROM {$this->table_name} WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }

    // Safe query execution with prepared statements
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fetch all results
    protected function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Fetch single row
    protected function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    // For child models to access PDO directly if needed
    public function getDb() {
        return $this->db;
    }
}

?>
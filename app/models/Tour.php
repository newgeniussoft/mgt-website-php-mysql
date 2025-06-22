<?php

require_once 'Model.php';

class Tour extends Model
{
    
    private $table = 'tours';
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    private function selectTable($table, $condition) {
        $stmt = $this->conn->prepare("SELECT * FROM ".$table." WHERE ".$condition);
        $stmt->execute();
        $data = [];
        while($item = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = $item;
        }
        return $data;
    }
    
    public function fetchByPath($path) {
        $this->execute("SELECT * FROM " . $this->table_name." WHERE path = '".$path."'");
        $tour = null;
        while($item = $this->stmt->fetch(PDO::FETCH_OBJ)) {
            $tour = $item;
            $tour->highlights = $this->selectTable("tour_highlights", "name_tour = '".$item->name."'");
            $tour->price_includes = $this->selectTable("tour_price", "name_tour = '".$item->name."' AND type = 'INCLUDE'");
            $tour->price_excludes = $this->selectTable("tour_price", "name_tour = '".$item->name."' AND type = 'EXCLUDE'");
            $tour->details = $this->selectTable("tour_details", "name_tour = '".$item->name."'");
            $tour->photos = $this->selectTable("tour_photos", "name_tour = '".$item->name."'");
        }
        return $tour;
    }
    // Fetch all tours
    public function all() {
        $this->execute("SELECT * FROM " . $this->table_name);
        $tours = [];
        while($item = $this->stmt->fetch(PDO::FETCH_OBJ)) {
            $tours[] = $item;
        }
        return $tours;
    }

    // Fetch a tour by ID
    public function find($id) {
        $this->execute("SELECT * FROM " . $this->table_name . " WHERE id = '".$id."' LIMIT 1");
        
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function insert($data) {
        $db = $this->db->getConnection();
        $fields = array_keys($data);
        $placeholders = array_map(function($f){ return '"' . $f.'"'; }, $fields);
        $sql = "INSERT INTO `tours` (" . implode(",", $fields) . ") VALUES (:" . implode(",:", $fields) . ")"; 
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $db->lastInsertId();
    }

    // Insert a new tour
    public function inserts($data) {
        $db = $this->db->getConnection();
        $fields = array_keys($data);
        $placeholders = array_map(function($f){ return ':' . $f; }, $fields);
        $sql = "INSERT INTO " . $this->table_name . " (" . implode(",", $fields) . ") VALUES (" . implode(",", $placeholders) . ")";
        $stmt = $db->prepare($sql);
        $bind = [];
        foreach ($data as $k => $v) {
            $bind[':' . $k] = $v;
        }
        
        $stmt->execute($bind);
        return $db->lastInsertId();
        
        
        
        /*$placeholders = array_map(function($f){ return ':' . $f; }, $fields);
        $sql = "INSERT INTO " . $this->table_name . " (" . implode(",", $fields) . ") VALUES (" . implode(",", $placeholders) . ")";
        $stmt = $this->conn->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();*/
        return $this->conn->lastInsertId();
    }

    // Update a tour
    public function update($id, $data) {
        $fields = array_keys($data);
        $set = implode(", ", array_map(function($f){ return "$f = :$f"; }, $fields));
        $sql = "UPDATE " . $this->table_name . " SET $set WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // Delete a tour
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}

?>
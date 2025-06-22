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
}

?>
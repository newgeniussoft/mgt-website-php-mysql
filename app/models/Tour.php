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
    
    public function fetchByPath($path) {
        $this->execute("SELECT * FROM " . $this->table_name." WHERE path = '".$path."'");
        $tour = null;
        while($item = $this->stmt->fetch(PDO::FETCH_OBJ)) {
            $tour = $item;
            /*$this->execute("SELECT * FROM tour_highlights WHERE name_tour = '".$item->name."'");
            $tour->highlights = $this->stmt->fetch(PDO::FETCH_OBJ);*/
        }
        return $tour;
    }
}

?>
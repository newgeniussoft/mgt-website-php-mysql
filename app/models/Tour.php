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
}

?>
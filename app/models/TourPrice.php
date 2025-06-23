<?php


require_once 'Model.php';
class TourPrice extends Model {
    protected $table = 'tour_price';
    protected $fillable = ['name_tour', 'type', 'texte', 'texte_es'];
    // Optionally, add methods for relationships if needed
    public function __construct()
    {
        parent::__construct($this->table);
    }
}

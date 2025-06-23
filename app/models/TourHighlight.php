<?php
require_once 'Model.php';
class TourHighlight extends Model {
    protected $table = 'tour_highlights';
    protected $fillable = ['name_tour', 'texte', 'texte_es'];
    // Optionally, add methods for relationships if needed
    public function __construct()
    {
        parent::__construct($this->table);
    }
}

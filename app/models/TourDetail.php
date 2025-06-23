<?php
require_once 'Model.php';
class TourDetail extends Model {
    protected $table = 'tour_details';
    protected $fillable = ['title', 'day', 'name_tour', 'title_es', 'details_es', 'name_tours_es'];
    // Optionally, add methods for relationships if needed
    public function __construct()
    {
        parent::__construct($this->table);
    }
}

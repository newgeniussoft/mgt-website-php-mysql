<?php

require_once __DIR__ . '/../../core/Controller.php';


class HomeController extends Controller {

    public function __construct($language = null)
        {
            parent::__construct($language);
        }

    public function index()
    {
        echo $this->view('frontend.pages.view', ['hello_world' => 'Hello World']);    
    }
    
}

?>
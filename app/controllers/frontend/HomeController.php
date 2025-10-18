<?php

require_once __DIR__ . '/../../core/Controller.php';


class HomeController extends Controller {

    public function __construct($language = null)
        {
            parent::__construct($language);
        }

    public function index()
    {
        $this->view('index', ['hello_world' => 'Hello World']);    
    }
    
}

?>
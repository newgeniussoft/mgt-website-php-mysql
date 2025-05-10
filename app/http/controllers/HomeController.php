<?php
    
    require_once 'Controller.php';

    class HomeController extends Controller
    {

        public function index()
        {
            echo $this->view('pages.index', [
                'title' => 'Home Page',
                'content' => 'Welcome to the home page!'
            ]);
        }
    }
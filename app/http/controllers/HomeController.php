<?php
    
    require_once 'Controller.php';

    class HomeController extends Controller
    {

        public function index()
        {
            echo $this->view('pages.index', []);
        }

        public function about() {
            echo $this->view('pages.about', []);
        }
    }
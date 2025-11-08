<?php
require_once 'Controller.php';
require 'HomeController.php';
require 'AdminController.php';

class MainController extends Controller
{
    private $homeController;
    private $adminController;

    public function __construct() {
        $this->homeController = new HomeController();
        $this->adminController = new AdminController();
    }

    public function index()
    {
        return $this->homeController->index();
    }

    public function about()
    {
        return $this->homeController->about();
    }

    public function access()
    {
        return $this->adminController->index();
    }
}



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

    /**
     * Show homepage with optional language
     * 
     * @param string|null $language Language code
     * @return mixed
     */
    public function index($language = null)
    {
        return $this->homeController->index($language);
    }

    /**
     * Show about page with optional language
     * 
     * @param string|null $language Language code
     * @return mixed
     */
    public function about($language = null)
    {
        return $this->homeController->about($language);
    }

    public function access()
    {
        return $this->adminController->index();
    }

    public function seo_test()
    {
        return $this->homeController->seo_test();
    }
}



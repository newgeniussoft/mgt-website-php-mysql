<?php
require_once 'Controller.php';
require 'HomeController.php';
require 'AdminController.php';
require 'TourController.php';

class MainController extends Controller
{
    private $homeController;
    private $adminController;
    private $tourController;

    public function __construct() {
        $this->homeController = new HomeController();
        $this->adminController = new AdminController();
        $this->tourController = new TourController();
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

    public function tours($language = null)
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($currentPath, '/'));
        if (count($pathParts) == 1) {
            return $this->tourController->all($language);
        } else {
            if (count($pathParts) == 2 && $language == "es") {
                return $this->tourController->all($language);
            } else {
                if (count($pathParts) == 3 && $language == "es") {
                return $this->tourController->show($language, $pathParts[2]);
                } else {
                    return $this->tourController->show($language, $pathParts[1]);
                }
            }
        }
        return $this->tourController->all($language);
    }

    public function access(){ return $this->adminController->index();}
    public function seo_test(){ return $this->homeController->seo_test();}
    public function car_rental(){ return $this->homeController->car_rental();}
    public function page($lang, $path){ return $this->homeController->page($lang, $path);}

}



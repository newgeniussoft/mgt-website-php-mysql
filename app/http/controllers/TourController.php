<?php
    
    require_once 'Controller.php';

    class TourController extends Controller
    {
        private $pagesAdminController;
        /**
         * Constructor
         *
         * @param string|null $language Language code
         */
        public function __construct($language = null)
        {
            parent::__construct($language);
            $this->pagesAdminController = new PagesAdminController();
        }
        
        /**
         * Tours page - Best practices for content and performance
         *
         * @param string|null $language Language code
         * @return string
         */
        public function all($language = null)
        {
            $path = "tours";
            $page = $this->pagesAdminController->getPage($path);

            if($page){
                echo $this->view('pages.tours.all', [
                    'tours' => $this->tours,
                    'language' => $language,
                    'page' => $page,
                    'contents' => $this->pagesAdminController->getContents($path)
                ]);
            }
        }
        public function show($lang = null, $path) {
            $tour = $this->tourModel->fetchBy('path', $path);
            $path = "tours";
            $page = $this->pagesAdminController->getPage($path);

            if($page){
                echo $this->view('pages.tours.show', [
                    'tours' => $this->tours,
                    'tour' => $tour,
                    'language' => $lang,
                    'page' => $page,
                    'contents' => $this->pagesAdminController->getContents($path)
                ]);
            }
        }
        
    }
?>
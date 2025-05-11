<?php
    
    require_once 'Controller.php';

    class HomeController extends Controller
    {
        /**
         * Current language code
         *
         * @var string|null
         */
        private $language = null;
        
        /**
         * Constructor
         *
         * @param string|null $language Language code
         */
        public function __construct($language = null)
        {
            parent::__construct();
            $this->language = $language;
        }
        
        /**
         * Show homepage
         *
         * @param string|null $language Language code
         * @return string
         */
        public function index($language = null)
        {
            // Update language if provided
            if ($language !== null) {
                $this->language = $language;
            }
            
            echo $this->view('pages.index', [
                'language' => $this->language,
                'currentPage' => 'home'
            ]);
        }

        /**
         * Show about page
         *
         * @param string|null $language Language code
         * @return string
         */
        public function about($language = null) 
        {
            // Update language if provided
            if ($language !== null) {
                $this->language = $language;
            }
            
            echo $this->view('pages.about', [
                'language' => $this->language,
                'currentPage' => 'about'
            ]);
        }
        
        /**
         * Get URL with language prefix
         *
         * @param string $page Page name
         * @param string|null $language Language code (if null, uses current language)
         * @return string
         */
        public function getLanguageUrl($page, $language = null)
        {
            $lang = $language ?? $this->language;
            
            if ($lang) {
                return '/' . $lang . '/' . $page;
            }
            
            return '/' . $page;
        }
    }
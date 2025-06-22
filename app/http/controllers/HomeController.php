<?php
    
    require_once 'Controller.php';
    require_once 'PagesAdminController.php';
    require_once __DIR__ . '/../../models/Video.php';
    require_once __DIR__ . '/../../models/Review.php';
    require_once __DIR__ . '/../../models/Gallery.php';
    require_once __DIR__ . '/../../models/SocialMedia.php';
    require_once __DIR__ . '/../../models/Slide.php';
    require_once __DIR__ . '/../../models/Service.php';

    class HomeController extends Controller
    {
        /**
         * Constructor
         *
         * @param string|null $language Language code
         */
        
        private $pagesAdminController;

        public function __construct($language = null)
        {
            parent::__construct($language);
            $this->pagesAdminController = new PagesAdminController();
        }
        

        public function page($lang, $path){
            $page = $this->pagesAdminController->getPage($path);
            $modelVideo = new Video();
            $modelReview = new Review();
            $modelGallery = new Gallery();
            $modelSocialMedia = new SocialMedia();
            $modelSlide = new Slide();
            $modelService = new Service();
            $videos = $modelVideo->all();
            $reviews = $modelReview->fetchLimit('4');
            $galleries = $modelGallery->all();
            $socialMedia = $modelSocialMedia->all();
            $slides = $modelSlide->all();
            $services = $modelService->all();

            
            $view = $path;
            if ($path == "") {
                $view = 'index';
            }
            if($page){
                echo $this->view('pages.'.$view, [
                    'tours' => $this->tours,
                    'language' => $lang,
                    'videos' => $videos,
                    'reviews' => $reviews,
                    'galleries' => $galleries,
                    'socialMedia' => $socialMedia,
                    'slides' => $slides,
                    'services' => $services,
                    'page' => $page,
                    'info' => $this->info,
                    'contents' => $this->pagesAdminController->getContents($path)
                ]);
            }
            /*return $this->index();*/
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
            
            // SEO metadata for about page
            $languagePrefix = $this->language === 'es' ? ' - Español' : '';
            
            echo $this->view('pages.blank', [
                'language' => $this->language,
                'currentPage' => 'about',
                'metaTitle' => 'About Madagascar Green Tours - Sustainable Tourism' . $languagePrefix,
                'metaDescription' => 'Learn about Madagascar Green Tours, our mission for sustainable tourism, and our commitment to preserving Madagascar\'s unique biodiversity while providing unforgettable travel experiences.',
                'metaKeywords' => 'about madagascar green tours, sustainable tourism madagascar, eco-friendly travel company, madagascar tour operator, green travel agency',
                'metaImage' => 'img/images/lemur.jpg'
            ]);
        }
        
        public function car_rental($language = null) 
        {
            // Update language if provided
            if ($language !== null) {
                $this->language = $language;
            }
            
            // SEO metadata for about page
            $languagePrefix = $this->language === 'es' ? ' - Español' : '';
            
            echo $this->view('pages.services.car-rental', [
                'language' => $this->language,
                'tours' => $this->tours,
                'currentPage' => 'about',
                'metaTitle' => 'About Madagascar Green Tours - Sustainable Tourism' . $languagePrefix,
                'metaDescription' => 'Learn about Madagascar Green Tours, our mission for sustainable tourism, and our commitment to preserving Madagascar\'s unique biodiversity while providing unforgettable travel experiences.',
                'metaKeywords' => 'about madagascar green tours, sustainable tourism madagascar, eco-friendly travel company, madagascar tour operator, green travel agency',
                'metaImage' => 'img/images/lemur.jpg'
            ]);
        }
        
        /**
         * SEO Test Page - Best practices for content and performance
         *
         * @param string|null $language Language code
         * @return string
         */
        public function seo_test($language = null)
        {
            // Update language if provided
            if ($language !== null) {
                $this->language = $language;
            }
            
            // SEO metadata for test page
            $languagePrefix = $this->language === 'es' ? ' - Español' : '';
            
            echo $this->view('pages.seo-test', [
                'language' => $this->language,
                'currentPage' => 'seo-test',
                'metaTitle' => 'SEO Best Practices - Performance Optimized Page' . $languagePrefix,
                'metaDescription' => 'This test page demonstrates SEO best practices for content structure, semantic HTML, performance optimization, and user experience enhancements.',
                'metaKeywords' => 'seo best practices, web performance, content optimization, semantic html, page speed, user experience',
                'metaImage' => 'img/seo-test-image.jpg',
                'metaRobots' => 'index,follow',
                'lastUpdated' => '2025-05-11T16:56:51+03:00'
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
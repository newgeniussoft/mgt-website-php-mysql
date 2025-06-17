<?php
    
    require_once 'Controller.php';

    class HomeController extends Controller
    {
        /**
         * Constructor
         *
         * @param string|null $language Language code
         */
        public function __construct($language = null)
        {
            parent::__construct($language);
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
                
            // SEO metadata for homepage
            $languagePrefix = $this->language === 'es' ? ' - Español' : '';
            
            echo $this->view('pages.index', [
                'language' => $this->language,
                'tours' => $this->tours,
                'currentPage' => 'home',
                'metaTitle' => 'Madagascar Green Tours - Eco-Friendly Travel Experience' . $languagePrefix,
                'metaDescription' => 'Madagascar Green Tours offers eco-friendly travel experiences in Madagascar with sustainable tourism practices. Explore unique wildlife, breathtaking landscapes, and authentic cultural experiences.',
                'metaKeywords' => 'madagascar tours, eco tourism, green travel, wildlife tours, sustainable tourism, madagascar travel, lemur tours',
                'metaImage' => 'img/logo/logo_new_updated.png'
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
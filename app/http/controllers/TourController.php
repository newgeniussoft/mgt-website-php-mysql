<?php
    
    require_once 'Controller.php';

    class TourController extends Controller
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
         * Tours page - Best practices for content and performance
         *
         * @param string|null $language Language code
         * @return string
         */
        public function all($language = null)
        {
            // Update language if provided
            parent::setLang($language);
            
            echo $this->view('pages.tours.all', [
                'language' => $this->language,
                'tours' => $this->tours,
                'currentPage' => 'tours',
                'metaTitle' => 'SEO Best Practices - Performance Optimized Page',
                'metaDescription' => 'This test page demonstrates SEO best practices for content structure, semantic HTML, performance optimization, and user experience enhancements.',
                'metaKeywords' => 'seo best practices, web performance, content optimization, semantic html, page speed, user experience',
                'metaImage' => 'img/seo-test-image.jpg',
                'metaRobots' => 'index,follow',
                'lastUpdated' => '2025-05-11T16:56:51+03:00'
            ]);
        }
        public function show($lang = null, $path) {
            $tour = $this->tourModel->fetchBy('path', $path);
            echo $this->view('pages.tours.show', [
                'language' => $this->language,
                'tours' => $this->tours,
                'tour' => $tour,
                'currentPage' => 'tours',
                'metaTitle' => $tour->meta_title,
                'metaDescription' => $tour->meta_description,
                'metaKeywords' => $tour->meta_keywords,
                'metaImage' => $tour->image,
                'metaRobots' => 'index,follow',
                'lastUpdated' => date('c', strtotime($tour->updated_at))
            ]);
        }
    }
?>
<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/BladeEngine.php';
require_once __DIR__ . '/../../models/Page.php';

class MainController extends Controller {

    public $language;

    public function __construct($language = null)
        {
            parent::__construct($language);
            $this->language = $language;
        }

    public function index()
    {
            $this->render('frontend.view', [
            'title' => 'Welcome',
            'users' => [
                ['name' => 'John', 'email' => 'john@example.com'],
                ['name' => 'Jane', 'email' => 'jane@example.com']
            ]
        ]);
    }

    public function welcome() {
        $this->render('welcome', [
            'title' => 'Welcome',
                'users' => [
                ['name' => 'John', 'email' => 'john@example.com'],
                ['name' => 'Jane', 'email' => 'jane@example.com']
            ]
        ]);
    }
    
    /**
     * Show a page from the database
     */
    public function showPage($page) {
        $pageModel = new Page();
        $currentLanguage = $page->language ?? $this->language ?? 'en';
        $menuPages = $pageModel->getMenuPages($currentLanguage);
        
        // Get translations for language switcher
        $translations = [];
        if ($page->translation_group) {
            $translations = $pageModel->getTranslations($page->translation_group);
        }
        
        // Determine which template to use
        $template = $this->getPageTemplate($page->template);
        
        $this->render($template, [
            'page' => $page,
            'title' => $page->meta_title ?: $page->title,
            'meta_description' => $page->meta_description ?: $page->excerpt,
            'meta_keywords' => $page->meta_keywords,
            'menu_pages' => $menuPages,
            'translations' => $translations,
            'current_language' => $currentLanguage
        ]);
    }
    
    /**
     * Get the appropriate template for a page
     */
    private function getPageTemplate($template) {
        $templates = [
            'homepage' => 'frontend.homepage',
            'about' => 'frontend.about',
            'contact' => 'frontend.contact',
            'blog' => 'frontend.blog',
            'services' => 'frontend.services',
            'gallery' => 'frontend.gallery',
            'default' => 'frontend.page'
        ];
        
        return $templates[$template] ?? 'frontend.page';
    }
}

?>
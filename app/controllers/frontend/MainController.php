<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/BladeEngine.php';
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../../models/PageSection.php';

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
        
        // Load sections if page uses sections
        $sections = [];
        $sectionsHtml = '';
        
        // Debug: Check if use_sections field exists and its value
        error_log("DEBUG: Page ID: " . $page->id);
        error_log("DEBUG: use_sections exists: " . (isset($page->use_sections) ? 'YES' : 'NO'));
        error_log("DEBUG: use_sections value: " . ($page->use_sections ?? 'NULL'));
        
        if (isset($page->use_sections) && $page->use_sections) {
            $pageSectionModel = new PageSection();
            $sections = $pageSectionModel->getByPageId($page->id, true); // Only active sections
            error_log("DEBUG: Found " . count($sections) . " sections");
            $sectionsHtml = $this->renderSections($sections);
            error_log("DEBUG: Sections HTML length: " . strlen($sectionsHtml));
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
            'current_language' => $currentLanguage,
            'sections' => $sections,
            'sections_html' => $sectionsHtml
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
    
    /**
     * Render sections for frontend display
     */
    private function renderSections($sections) {
        if (empty($sections)) {
            return '';
        }
        
        $html = '';
        $css = '';
        $js = '';
        
        foreach ($sections as $section) {
            // Get section template if available
            $sectionHtml = $this->renderSectionContent($section);
            $html .= $sectionHtml['html'];
            
            if (!empty($sectionHtml['css'])) {
                $css .= $sectionHtml['css'];
            }
            
            if (!empty($sectionHtml['js'])) {
                $js .= $sectionHtml['js'];
            }
        }
        
        // Combine HTML with CSS and JS
        $output = $html;
        
        if (!empty($css)) {
            $output .= '<style>' . $css . '</style>';
        }
        
        if (!empty($js)) {
            $output .= '<script>' . $js . '</script>';
        }
        
        return $output;
    }
    
    /**
     * Render individual section content
     */
    private function renderSectionContent($section) {
        // Check if section has custom HTML/CSS/JS
        if (!empty($section['section_html'])) {
            return [
                'html' => $this->processTemplateVariables($section['section_html'], $section),
                'css' => $section['section_css'] ?? '',
                'js' => $section['section_js'] ?? ''
            ];
        }
        
        // Fallback to basic section rendering
        return $this->renderBasicSection($section);
    }
    
    /**
     * Process template variables in section content
     */
    private function processTemplateVariables($template, $section) {
        $settings = json_decode($section['settings'] ?? '{}', true);
        
        // Replace basic variables
        $template = str_replace('{{ title }}', $section['title'] ?? '', $template);
        $template = str_replace('{{ content }}', $section['content'] ?? '', $template);
        
        // Replace settings variables
        foreach ($settings as $key => $value) {
            $template = str_replace('{{ ' . $key . ' }}', $value, $template);
        }
        
        // Process conditional blocks
        $template = $this->processConditionals($template, array_merge(['title' => $section['title'], 'content' => $section['content']], $settings));
        
        return $template;
    }
    
    /**
     * Process conditional template blocks
     */
    private function processConditionals($template, $data) {
        $pattern = '/@if\(([^)]+)\)(.*?)@endif/s';
        
        return preg_replace_callback($pattern, function($matches) use ($data) {
            $condition = trim($matches[1]);
            $content = $matches[2];
            
            if (isset($data[$condition]) && !empty($data[$condition])) {
                return $content;
            }
            
            return '';
        }, $template);
    }
    
    /**
     * Render basic section without template
     */
    private function renderBasicSection($section) {
        $html = '<div class="page-section section-' . $section['section_type'] . '">';
        
        if (!empty($section['title'])) {
            $html .= '<h3 class="section-title">' . htmlspecialchars($section['title']) . '</h3>';
        }
        
        if (!empty($section['content'])) {
            $html .= '<div class="section-content">' . $section['content'] . '</div>';
        }
        
        $html .= '</div>';
        
        return [
            'html' => $html,
            'css' => '.page-section { margin-bottom: 2rem; } .section-title { color: #198754; margin-bottom: 1rem; }',
            'js' => ''
        ];
    }
}

?>
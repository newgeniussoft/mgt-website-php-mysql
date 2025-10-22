<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/BladeEngine.php';
require_once __DIR__ . '/../../models/Page.php';
require_once __DIR__ . '/../../models/PageSection.php';
require_once __DIR__ . '/../../models/PageTemplate.php';

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
     * Show a page from the database using editable templates
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
        if (isset($page->use_sections) && $page->use_sections) {
            $pageSectionModel = new PageSection();
            $sections = $pageSectionModel->getByPageId($page->id, true); // Only active sections
            $sectionsHtml = $this->renderSections($sections);
        }
        
        // Render page using database template
        $renderedPage = $this->renderPageTemplate($page, [
            'title' => $page->meta_title ?: $page->title,
            'meta_description' => $page->meta_description ?: $page->excerpt,
            'meta_keywords' => $page->meta_keywords,
            'content' => $page->content,
            'excerpt' => $page->excerpt,
            'featured_image' => $page->featured_image,
            'menu_pages' => $this->renderMenuPages($menuPages),
            'translations' => $translations,
            'current_language' => $currentLanguage,
            'sections' => $sections,
            'sections_html' => $sectionsHtml,
            'use_sections' => $page->use_sections,
            'language' => $currentLanguage,
            'current_year' => date('Y')
        ]);
        
        // Output the rendered page directly
        echo $renderedPage;
    }
    
    /**
     * Render page using database template
     */
    private function renderPageTemplate($page, $data) {
        $pageTemplateModel = new PageTemplate();
        
        // Check if page has custom template
        if (!empty($page->custom_html)) {
            return $this->processPageTemplate($page->custom_html, $page->custom_css, $page->custom_js, $data, $page);
        }
        
        // Check if page has assigned template_id
        if (!empty($page->template_id)) {
            $template = $pageTemplateModel->getById($page->template_id);
            if ($template) {
                return $this->processPageTemplate($template['html_template'], $template['css_styles'], $template['js_scripts'], $data, $page);
            }
        }
        
        // Fallback to template based on page template field
        $templateSlug = $this->getTemplateSlugFromPageTemplate($page->template);
        $template = $pageTemplateModel->getBySlug($templateSlug);
        
        if ($template) {
            return $this->processPageTemplate($template['html_template'], $template['css_styles'], $template['js_scripts'], $data, $page);
        }
        
        // Final fallback to default template
        $defaultTemplate = $pageTemplateModel->getBySlug('default-page');
        if ($defaultTemplate) {
            return $this->processPageTemplate($defaultTemplate['html_template'], $defaultTemplate['css_styles'], $defaultTemplate['js_scripts'], $data, $page);
        }
        
        // Emergency fallback
        return $this->renderEmergencyTemplate($data);
    }
    
    /**
     * Get template slug from page template field
     */
    private function getTemplateSlugFromPageTemplate($template) {
        $templateMap = [
            'homepage' => 'homepage',
            'contact' => 'contact-page',
            'about' => 'default-page',
            'blog' => 'default-page',
            'services' => 'default-page',
            'gallery' => 'default-page',
            'default' => 'default-page'
        ];
        
        return $templateMap[$template] ?? 'default-page';
    }
    
    /**
     * Process page template with variables
     */
    private function processPageTemplate($htmlTemplate, $cssStyles, $jsScripts, $data, $page) {
        // Merge page template variables if available
        if (!empty($page->template_variables)) {
            $templateVars = json_decode($page->template_variables, true);
            if ($templateVars) {
                $data = array_merge($data, $templateVars);
            }
        }
        
        // Process HTML template
        $html = $this->processTemplateVariables($htmlTemplate, $data);
        
        // Process CSS with variables
        $css = $this->processTemplateVariables($cssStyles ?? '', $data);
        
        // Process JavaScript with variables
        $js = $this->processTemplateVariables($jsScripts ?? '', $data);
        
        // Replace custom CSS and JS placeholders
        $html = str_replace('{{ custom_css }}', $css, $html);
        $html = str_replace('{{ custom_js }}', $js, $html);
        
        return $html;
    }
    
    /**
     * Render menu pages as HTML
     */
    private function renderMenuPages($menuPages) {
        if (empty($menuPages)) {
            return '';
        }
        
        $html = '';
        foreach ($menuPages as $menuPage) {
            $activeClass = '';
            // You can add logic here to determine active page
            $html .= '<li class="nav-item">';
            $html .= '<a class="nav-link' . $activeClass . '" href="/' . $menuPage['slug'] . '">' . htmlspecialchars($menuPage['title']) . '</a>';
            $html .= '</li>';
        }
        
        return $html;
    }
    
    /**
     * Emergency fallback template
     */
    private function renderEmergencyTemplate($data) {
        return '<!DOCTYPE html>
<html>
<head>
    <title>' . htmlspecialchars($data['title'] ?? 'Page') . '</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>' . htmlspecialchars($data['title'] ?? 'Page') . '</h1>
        <div>' . ($data['content'] ?? '') . '</div>
        ' . ($data['sections_html'] ?? '') . '
    </div>
</body>
</html>';
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
     * Process template variables in content
     */
    private function processTemplateVariables($template, $data) {
        if (empty($template)) {
            return '';
        }
        
        // Handle section-specific data if it's a section
        if (isset($data['settings'])) {
            $settings = is_string($data['settings']) ? json_decode($data['settings'], true) : $data['settings'];
            if ($settings) {
                $data = array_merge($data, $settings);
            }
        }
        
        // Replace variables with default values support ({{ variable|default_value }})
        $template = preg_replace_callback('/\{\{\s*([^}|]+)(\|([^}]*))?\s*\}\}/', function($matches) use ($data) {
            $variable = trim($matches[1]);
            $defaultValue = isset($matches[3]) ? trim($matches[3]) : '';
            
            if (isset($data[$variable]) && $data[$variable] !== '') {
                return $data[$variable];
            }
            
            return $defaultValue;
        }, $template);
        
        // Process conditional blocks
        $template = $this->processConditionals($template, $data);
        
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
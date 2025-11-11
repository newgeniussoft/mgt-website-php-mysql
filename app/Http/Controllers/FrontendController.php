<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Template;
use App\Models\Section;
use App\View\View;

class FrontendController extends Controller
{
    /**
     * Show homepage
     */
    public function index()
    {
        // Try to get homepage from database
        $page = Page::getHomepage();
        
        if ($page) {
            return $this->showPage($page->slug);
        }
        
        // Fallback: show a simple welcome page
        echo '<h1>Welcome</h1><p>No homepage configured. Please create a page and set it as homepage in the admin panel.</p>';
        echo '<p><a href="/' . ($_ENV['APP_ADMIN_PREFIX'] ?? 'admin') . '/pages">Go to Admin Panel</a></p>';
        exit;
    }

    public function showPage($slug)
    {
        // If $slug is already a Page object (from index method)
        if ($slug instanceof Page) {
            $page = $slug;
        } else {
            // Get page by slug
            $page = Page::getBySlug($slug);
            
            if (!$page || $page->status !== 'published') {
                return $this->notFound();
            }
        }
        
        // Get template
        $template = null;
        if ($page->template_id) {
            $template = Template::find($page->template_id);
        }
        
        // Get sections
        $sections = Section::getByPage($page->id, true);
        if (!is_array($sections)) {
            $sections = [];
        }
        
        // Get menu pages for navigation
        $menuPages = Page::getMenuPages();
        if (!is_array($menuPages)) {
            $menuPages = [];
        }
        
        // Render page with template
        if ($template && $template->html_content) {
            return $this->renderWithTemplate($page, $template, $sections, $menuPages);
        }
        
        // Fallback: render without template
        return $this->renderBasic($page, $sections, $menuPages);
    }
    
    /**
     * Render page with template
     */
    protected function renderWithTemplate($page, $template, $sections, $menuPages)
    {
        // Build menu HTML
        $menuHtml = $this->buildMenuHtml($menuPages);
        
        // Render sections
        $sectionsHtml = $this->renderSections($sections);
        
        // Prepare template variables
        $variables = [
            'page_title' => $page->title,
            'meta_description' => $page->meta_description ?? '',
            'meta_keywords' => $page->meta_keywords ?? '',
            'site_name' => $_ENV['APP_NAME'] ?? 'My Website',
            'menu_items' => $menuHtml,
            'page_sections' => $sectionsHtml,
            'custom_css' => '',
            'custom_js' => ''
        ];
        
        // Render template HTML
        $html = $template->html_content ?? '';
        
        // Replace variables in template
        foreach ($variables as $key => $value) {
            $html = str_replace('{{ ' . $key . ' }}', $value, $html);
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }
        
        // Inject CSS if template has it
        if ($template->css_content) {
            $cssTag = '<style>' . $template->css_content . '</style>';
            // Try to inject before </head> or at the beginning
            if (strpos($html, '</head>') !== false) {
                $html = str_replace('</head>', $cssTag . '</head>', $html);
            } else {
                $html = $cssTag . $html;
            }
        }
        
        // Inject JS if template has it
        if ($template->js_content) {
            $jsTag = '<script>' . $template->js_content . '</script>';
            // Try to inject before </body> or at the end
            if (strpos($html, '</body>') !== false) {
                $html = str_replace('</body>', $jsTag . '</body>', $html);
            } else {
                $html .= $jsTag;
            }
        }
        
        // Return raw HTML
        echo $html;
        exit;
    }
    
    /**
     * Render page without template (basic)
     */
    protected function renderBasic($page, $sections, $menuPages)
    {
        return View::make('frontend.page', [
            'page' => $page,
            'sections' => $sections,
            'menuPages' => $menuPages
        ]);
    }
    
    /**
     * Build menu HTML
     */
    protected function buildMenuHtml($menuPages)
    {
        $html = '<ul class="navbar-nav header-styled gradient-border">';
        foreach ($menuPages as $menuPage) {
            $active = (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/' . $menuPage->slug) ? ' class="active"' : '';
            $url = $menuPage->is_homepage ? '/' : '/' . $menuPage->slug;
            $html .= '<li' . $active . ' class="nav-item"><a href="' . $url . '" class="nav-link">' . htmlspecialchars($menuPage->title) . '</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Get attributes from dom
     */

    public function attrFromTag($tag, $attr) {
        // example tag : <items name="tours" />

        preg_match('/' . $attr . '="([^"]+)"/i', $tag, $matches);
        return $matches[1] ?? '';
        

    }

    /**
     *  Get if tag in varibale {{ variables }}
     */

    public function tagVariables($content) {
        preg_match_all('/{{\s*([^}]+)\s*}}/', $content, $matches);
        return $matches[1] ?? [];
    }

    /**
     * Get Model by name
     */
    public function getModelByName($name) {
        $modelName = '\\App\\Models\\' . $name;
        $model = new $modelName();
        return $model;
    }

    public function createListHtml($items, $keys) {
        $html = '<ul>';
        foreach ($items as $item) {
            $key = $keys[0].'';
            $html .= '<li>';
            for ($i = 0; $i < count($keys); $i++) {
                $key = str_replace(' ', '', $keys[$i])."";
                $value = $item->$key;
                $html .= $value." ";
            }
           $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    
    /**
     * Render sections
     */
    protected function renderSections($sections)
    {
        $html = '';
        $css = '';
        $js = '';
        
        foreach ($sections as $section) {
            // Render section HTML
            $sectionHtml = $section->html_template ?? '';
            
            // Get section contents
            $contents = \App\Models\Content::getBySection($section->id, true);
            $contentHtml = '';
            
            foreach ($contents as $content) {
                if ($content->content_type === 'html') {
                    $contentHtml .= $content->content;
                } else {
                    $contentHtml .= '<div>' . nl2br(htmlspecialchars($content->content)) . '</div>';
                }
            }
            
            // Replace {{ content }} with actual content
            $sectionHtml = str_replace('{{ content }}', $contentHtml, $sectionHtml);
            foreach ($this->tagVariables($sectionHtml) as $variable) {
                $value = $this->attrFromTag($variable, 'name');
                $keys = $this->attrFromTag($variable, 'keys');
                $keys = explode(',', $keys);
                $model = $this->getModelByName(ucfirst($value));
                $items = $model->all();
                
                $sectionHtml = str_replace('{{ '.$variable.'}}', $this->createListHtml($items, $keys), $sectionHtml);
            }


            $html .= $sectionHtml;
            
            // Collect CSS and JS
            if ($section->css_styles) {
                $css .= $section->css_styles . "\n";
            }
            if ($section->js_scripts) {
                $js .= $section->js_scripts . "\n";
            }
        }
        
        // Wrap CSS and JS
        if ($css) {
            $html = '<style>' . $css . '</style>' . $html;
        }
        if ($js) {
            $html .= '<script>' . $js . '</script>';
        }
        
        return $html;
    }
    
    /**
     * 404 Not Found
     */
    protected function notFound()
    {
        http_response_code(404);
        return View::make('errors.404', [
            'title' => '404 - Page Not Found'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Template;
use App\Models\Section;
use App\Models\TemplateItem;
use App\View\View;
use App\View\Html;
use App\Localization\Lang;
use Exception;

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
        $menuHtml = Html::buildMenuHtml($menuPages);
        
        // Render sections
        
        $sectionsHtml = Html::renderSections($sections);
        
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

<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Template;
use App\Models\Section;
use App\Models\TemplateItem;
use App\Models\Tour;
use App\Models\Model;
use App\Models\Post;
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

            if ($page->is_menu_only) {
                return $this->notFound();
            }
            
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
            return Html::renderWithTemplate($page, $template, $sections, $menuPages);
        }
        
        // Fallback: render without template
        return $this->renderBasic($page, $sections, $menuPages);
    }

    public function renderPageItem($item) {
        
    }

    /**
     * Show model object if slug is found
     */
    public function showPageItem($slug, $item)
    {
        // If $slug is already a Page object (from index method)
        $model = Model::fromSlug($slug); 
        

        // Get menu pages for navigation
        $menuPages = Page::getMenuPages();
        if (!is_array($menuPages)) {
            $menuPages = [];
        }
        
        if ($model) {
            if ($model === Tour::class) {
                $baseList = Tour::where('slug', '=', $item);
                if (is_array($baseList) && count($baseList) > 0) {
                    $resolved = $baseList[0];
                    $lang = Lang::getLocale();
                    if ($resolved && $lang && $resolved->language !== $lang) {
                        $groupList = Tour::where('translation_group', '=', $resolved->translation_group);
                        if (is_array($groupList) && count($groupList) > 0) {
                            foreach ($groupList as $candidate) {
                                if ($candidate->language === $lang) {
                                    $resolved = $candidate;
                                    break;
                                }
                            }
                        }
                    }
                    return Html::renderItemWithTemplate($resolved, $menuPages);
                }
                return $this->notFound();
            } 

            $found = $model::where('slug', '=', $item);
            if (is_array($found) && count($found) > 0) {
                $found = $found[0];
            }

            if ($slug === "blog") {
                $lang = Lang::getLocale();
                if ($lang === "es") {
                    $found->title = $found->title_es;
                    $found->short_text = $found->short_text_es;
                    $found->description = $found->description_es;
                }
            }

            return Html::renderItemWithTemplate($found, $menuPages);

        } else {
            return $this->notFound();
        }
    }
    /**
     * Show Blog Post detail by slug or ID (Option A)
     */
    public function showPost($key)
    {
        $post = null;
        // Try slug first
        if (!ctype_digit((string)$key) && method_exists(Post::class, 'getBySlug')) {
            $post = Post::getBySlug($key);
        }
        // Fallback to numeric ID
        if (!$post && ctype_digit((string)$key)) {
            $post = Post::find((int)$key);
        }

        // If not found or not published (when status exists), 404
        if (!$post || (isset($post->status) && $post->status !== 'published')) {
            return $this->notFound();
        }

        // Choose a Template Item for the post detail
        $templateSlug = $_GET['template'] ?? null;
        $templateItem = null;
        if (!empty($templateSlug)) {
            $templateItem = TemplateItem::getBySlug($templateSlug);
        }
        if (!$templateItem) {
            $templateItem = TemplateItem::getDefaultForModel('post');
        }

        // Normalize to array for rendering
        $item = is_array($post) ? $post : (array)$post;

        // Render detail HTML
        $detailHtml = $templateItem
            ? $templateItem->render($item)
            : ('<article class="post-detail"><h1>' . htmlspecialchars($item['title'] ?? 'Post') . '</h1>' . ($item['content'] ?? '') . '</article>');

        // Wrap inside default Template
        $template = Template::getDefault();
        $menuPages = Page::getMenuPages();
        $menuHtml = Html::buildMenuHtml($menuPages);

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $currentUrl = $protocol . '://' . $host . $uri;

        $variables = [
            'page_title' => $item['title'] ?? 'Post',
            'meta_title' => $item['title'] ?? 'Post',
            'meta_description' => substr(strip_tags($item['content'] ?? ''), 0, 150),
            'meta_keywords' => '',
            'featured_image' => '',
            'site_name' => $_ENV['APP_NAME'] ?? 'My Website',
            'app_url' => $_ENV['APP_URL'] ?? 'http://localhost',
            'current_path' => $currentUrl,
            'current_path_es' => function_exists('currentUrlToEs') ? currentUrlToEs() : $currentUrl,
            'menu_items' => $menuHtml,
            'page_sections' => $detailHtml
        ];

        if ($template && $template->html_content) {
            $html = $template->render($variables);
            echo $html;
            exit;
        }

        echo '<nav>' . $menuHtml . '</nav>' . $detailHtml;
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

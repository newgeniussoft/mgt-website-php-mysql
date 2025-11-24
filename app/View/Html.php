<?php 

namespace App\View;
use App\Localization\Lang;
use App\Models\Model;
use App\Models\Template;

class Html {

    /**
     * Build menu html
     * 
     * @param string $menuPages The menu pages
     * @return string The menu html
     */
    
    public static function buildMenuHtml($menuPages)
    {
        $html = '<ul class="navbar-nav header-styled gradient-border">';
        $idHasChild = [];
        $idHasParent = [];
        $idHasItems = [];
        foreach ($menuPages as $menuPage) {
            if ($menuPage->parent_id != NULL && !$menuPage->has_items) {
                $idHasChild[] = $menuPage->parent_id;
                $idHasParent[] = $menuPage->id;
            }
            if ($menuPage->has_items) {
                $idHasItems[] = $menuPage->id;
            }
        }
        
        foreach ($menuPages as $menuPage) {
            $active = (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/' . $menuPage->slug) ? 'active' : '';
            $url = $menuPage->is_homepage ? '/' : '/' . $menuPage->slug;

            if (!in_array($menuPage->id, $idHasChild) && !in_array($menuPage->id, $idHasParent) && !in_array($menuPage->id, $idHasItems)) {
                $html .= '<li class="nav-item '.$active.'"><a href="' . url($url) . '" class="nav-link ">' . htmlspecialchars($menuPage->title) . '</a></li>';
            } else {
                if (in_array($menuPage->id, $idHasChild)) {
                    $html .= '<li class="nav-item '.$active.' dropdown"><a href="' . url($url) . '" class="nav-link dropdown-toggle" id="dropdown-' . $menuPage->id . '" role="button" aria-haspopup="true" aria-expanded="true">' . htmlspecialchars($menuPage->title) . '</a>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="dropdown-' . $menuPage->id . '">';
                    foreach($menuPages as $child) {
                        if ($child->parent_id  === $menuPage->id) {
                            $html .= '<a class="dropdown-item" href="'.url($child->slug).'">'.$child->title.'</a>';
                        }
                    }
                    $html .= '</div></li>';
                } 
                if (in_array($menuPage->id, $idHasItems)) {
                    $html .= '<li class="nav-item dropdown '.$active.'"><a href="' . url($url) . '" class="nav-link dropdown-toggle" id="dropdown-' . $menuPage->id . '" role="button" aria-haspopup="true" aria-expanded="true">' . htmlspecialchars($menuPage->title) . '</a>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="dropdown-' . $menuPage->id . '">';
                    $model = Model::fromSlug($menuPage->slug);
                    foreach($model::where('language', '=', Lang::getLocale()) as $child) {
                        $html .= '<a class="dropdown-item" href="'.url($menuPage->slug.'/'.$child->slug).'">'.$child->name.'</a>';
                    }
                    $html .= '</div></li>';
                } 
                
            }
        }
        $html .= '</ul>';
        $html .= '<ul class="navbar-nav navbar-right">
                    
                <li class="nav-item">
                        <a class="nav-link flag" href="'.switchLanguage('en').'"><img src="/uploads/media/images/logos/flag-uk.png" alt="United Kingdom flag" width="28" height="28">
                            <span>English</span>
                            </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link flag" href="'.switchLanguage('es').'"><img src="/uploads/media/images/logos/flag-spain.png" alt="Spain flag" width="28" height="28">
                            <span>Spanish</span>
                            </a>
                    </li>
                </ul>';
        return $html;
    }

    /**
     * Render sections
     * 
     * @param string $sections The sections
     * @return string The sections html
     */
    public static function renderSections($sections)
    {
        $html = '';
        $css = '';
        $js = '';
        
        foreach ($sections as $section) {
            // Render section HTML
            $sectionHtml = $section->html_template ?? '';
            
            // Get section contents with current language
            $contentHtml = '';
            $currentLanguage = Lang::getLocale();
            $contents = \App\Models\Content::getBySection($section->id, true, $currentLanguage);
            
            foreach ($contents as $content) {
                if ($content->content_type === 'html') {
                    $contentHtml .= $content->content;
                } else {
                    $contentHtml .= '<div>' . nl2br(htmlspecialchars($content->content)) . '</div>';
                }
            }
            
            // Replace {{ content }} with actual content
            $sectionHtml = str_replace('{{ content }}', $contentHtml, $sectionHtml);
            // Define your data sources

            $sectionHtml = self::renderItemsWithData($sectionHtml);
           
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
    * Extract items tag attributes as an object/array
    * 
    * @param string $html The HTML content containing the items tag
    * @return array|null Array of attributes or null if not not found
    */
    public static function extractItemsTag($html) {
        $pattern = '/<items\s+([^>]+)\/>/i';
    
        if (preg_match($pattern, $html, $matches)) {
            $attributesString = $matches[1];
            $attributes = [];
        
            $attrPattern = '/(\w+)="([^"]*)"/';
        
            if (preg_match_all($attrPattern, $attributesString, $attrMatches, PREG_SET_ORDER)) {
                foreach ($attrMatches as $match) {
                    $attributes[$match[1]] = $match[2];
                }
            }
        
            return $attributes;
        }
    
        return null;
    }

    /**
     * Find all items tags in HTML content
     * 
     * @param string $html The HTML content to search in
     * @return array Array of items, each containing 'tag' and 'attributes'
     */
    public static function findItemsTags($html) {
        $pattern = '/<items\s+[^>]+\/>/i';
        $results = [];
    
        if (preg_match_all($pattern, $html, $matches)) {
            foreach ($matches[0] as $tag) {
                $attributes = [];
            
                $attrPattern = '/(\w+)="([^"]*)"/';
                if (preg_match_all($attrPattern, $tag, $attrMatches, PREG_SET_ORDER)) {
                    foreach ($attrMatches as $match) {
                        $attributes[$match[1]] = $match[2];
                    }
                }
            
                $results[] = [
                    'tag' => $tag,
                    'attributes' => $attributes
                ];
            }
        }
    
        return $results;
    }

    /**
     * Render a single item using a template
     * 
     * @param string $template Template string with {{ $item.attribute }} placeholders
     * @param array $item Data array for the item
     * @return string Rendered HTML
     */
    public static function renderItemTemplate($template, $item) {
        $rendered = $template;
    
        // Replace {{ $item.attribute }} with actual values
        if (!is_string($item)) {
            foreach ($item as $key => $value) {
                $rendered = str_replace('{{ $item.' . $key . ' }}', htmlspecialchars($value), $rendered);
            }
        } else {
                $rendered = str_replace('{{ $item.value }}', htmlspecialchars($item), $rendered);
        }
    
        return $rendered;
    }


    /**
     * Advanced render function with data source and templates
     * 
     * @param string $html HTML content with <items> tags
     * @param array $dataSources Associative array of data sources (e.g., ['media' => $mediaArray])
     * @param array $templates Associative array of templates (e.g., ['media-grid' => $templateString])
     * @return string Rendered HTML
     */
    public static function renderItemsWithData($html) {
        $pattern = '/<items\s+[^>]+\/>/i';
        $dataSources = [];

        foreach(self::findItemsTags($html) as $item){
            
                $name = $item['attributes']['name'];
            if (isset($item['attributes']['data'])) {
            $data = str_replace("'", "\"", $item['attributes']['data']);
            $array = json_decode($data, true);
            $list = $array;

            } else {
                


                $modelName = '\\App\\Models\\' . ucfirst($name);
                $list = $modelName::all();
            
                $lang = Lang::getLocale();
                if ($name == 'tour') {
                    $list = $modelName::where('language', $lang);
                }

            }

            $listArray = [];
            
            foreach($list as $item){
                $items = [];
                if (!is_string($item)) {
                    foreach($item->toArray() as $key => $value){
                        $items[$key] = $value;
                    }
                    $listArray[] = $items;
                } else {
                    $listArray[] = $item;
                }
            }
            $dataSources[$name] = $listArray;
        }

        $templateItem = \App\Models\TemplateItem::all();
        $templates = [];
        foreach ($templateItem as $item) {
            $templates[$item->slug] = $item->html_template;
        }
    
        return preg_replace_callback($pattern, function($matches) use ($dataSources, $templates) {
            $tag = $matches[0];
            $attributes = [];
        
            // Extract attributes
            $attrPattern = '/(\w+)="([^"]*)"/';
            if (preg_match_all($attrPattern, $tag, $attrMatches, PREG_SET_ORDER)) {
                foreach ($attrMatches as $match) {
                    $attributes[$match[1]] = $match[2];
                }
            }
        
            // Get the data source name
            $dataName = $attributes['name'] ?? null;
            $templateName = $attributes['template'] ?? null;
            $limit = isset($attributes['limit']) ? (int)$attributes['limit'] : null;
        
            // Check if data source and template exist
            if (!$dataName || !isset($dataSources[$dataName])) {
                return '<!-- Data source "' . htmlspecialchars($dataName) . '" not found -->';
            }
        
            if (!$templateName || !isset($templates[$templateName])) {
                return '<!-- Template "' . htmlspecialchars($templateName) . '" not found -->';
            }
        
            $data = $dataSources[$dataName];
            $template = $templates[$templateName];
        
            // Apply limit if specified
            if ($limit !== null && $limit > 0) {
                $data = array_slice($data, 0, $limit);
            }
        
            // Render each item
            $output = '';
            foreach ($data as $item) {
                $output .= self::renderItemTemplate($template, $item);
            }
        
            return $output;
        
        }, $html);
    }

    
    /**
     * Render page with template
     */
    public static function renderWithTemplate($page, $template, $sections, $menuPages)
    {
        // Build menu HTML
        $menuHtml = Html::buildMenuHtml($menuPages);
        
        // Render sections
        
        $sectionsHtml = Html::renderSections($sections);

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        $currentUrl = $protocol . "://" . $host . $uri;
        
        // Prepare template variables
        $variables = [
            'page_title' => $page->page_title,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description ?? '',
            'meta_keywords' => $page->meta_keywords ?? '',
            'featured_image' => $page->featured_image ?? '',
            'site_name' => $_ENV['APP_NAME'] ?? 'My Website',
            'app_url' => $_ENV['APP_URL'] ?? 'http://localhost',
            'current_path' => $currentUrl,
            'current_path_es' => currentUrlToEs(),
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
     * Render page with template
     */
    public static function renderItemWithTemplate($item, $menuPages)
    {
        // Build menu HTML
        $menuHtml = Html::buildMenuHtml($menuPages);
        
        $template = Template::where("slug", "=", $item->template_slug);
        if (count($template) > 0) {
            $template = $template[0];
        }

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        $currentUrl = $protocol . "://" . $host . $uri;
        
        
        // Prepare template variables
        $variables = [
            'meta_title' => $item->meta_title,
            'meta_description' => $item->meta_description ?? '',
            'meta_keywords' => $item->meta_keywords ?? '',
            'featured_image' => $item->featured_image ?? '',
            'site_name' => $_ENV['APP_NAME'] ?? 'My Website',
            'app_url' => $_ENV['APP_URL'] ?? 'http://localhost',
            'current_path' => $currentUrl,
            'current_path_es' => currentUrlToEs(),
            'menu_items' => $menuHtml,
            'kml' => getKmlFiles('adventure_tour'),
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
        $item = $item->toArray();
        $keys = [];
        foreach ($item as $key => $value) {
            $html = str_replace('{{ $item.' . $key . ' }}', str_replace('"', "'", $value), $html);
            $html = str_replace('{{$item.' . $key . '}}', str_replace('"', "'", $value), $html);
        }

        
        $html = self::renderItemsWithData($html);
        
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
}
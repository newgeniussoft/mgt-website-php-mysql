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

            $menuKey = 'menu.' . trim($menuPage->slug, '/');
            $label = Lang::has($menuKey) ? Lang::get($menuKey) : $menuPage->title;

            if (!in_array($menuPage->id, $idHasChild) && !in_array($menuPage->id, $idHasParent) && !in_array($menuPage->id, $idHasItems)) {
                $icon_home = $menuPage->is_homepage ? '<i class="fa fa-home"></i> ' : '';
                $html .= '<li class="nav-item '.$active.'"><a href="' . url($url) . '" class="nav-link ">'. $icon_home . htmlspecialchars($label) . '</a></li>';
            } else {
                if (in_array($menuPage->id, $idHasChild)) {
                    $html .= '<li class="nav-item '.$active.' dropdown"><a href="' . url($url) . '" class="nav-link dropdown-toggle" id="dropdown-' . $menuPage->id . '" role="button" aria-haspopup="true" aria-expanded="true">' . htmlspecialchars($label) . '</a>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="dropdown-' . $menuPage->id . '">';
                    foreach($menuPages as $child) {
                        if ($child->parent_id  === $menuPage->id) {
                            $childKey = 'menu.' . trim($child->slug, '/');
                            $childLabel = Lang::has($childKey) ? Lang::get($childKey) : $child->title;
                            $html .= '<a class="dropdown-item" href="'.url($child->slug).'">'.htmlspecialchars($childLabel).'</a>';
                        }
                    }
                    $html .= '</div></li>';
                } 
                if (in_array($menuPage->id, $idHasItems)) {
                    $html .= '<li class="nav-item dropdown '.$active.'"><a href="' . url($url) . '" class="nav-link dropdown-toggle" id="dropdown-' . $menuPage->id . '" role="button" aria-haspopup="true" aria-expanded="true">' . htmlspecialchars($label) . '</a>';
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
        $enLabel = Lang::has('language.english') ? Lang::get('language.english') : 'English';
        $esLabel = Lang::has('language.spanish') ? Lang::get('language.spanish') : 'Spanish';
        $html .= '<ul class="navbar-nav navbar-right">'
                .    '<li class="nav-item">'
                .        '<a class="nav-link flag" href="'.switchLanguage('en').'"><img src="/uploads/media/images/logos/flag-uk.png" alt="United Kingdom flag" width="28" height="28">'
                .            '<span>'.htmlspecialchars($enLabel).'</span>'
                .            '</a>'
                .    '</li>'
                .    '<li class="nav-item">'
                .        '<a class="nav-link flag" href="'.switchLanguage('es').'"><img src="/uploads/media/images/logos/flag-spain.png" alt="Spain flag" width="28" height="28">'
                .            '<span>'.htmlspecialchars($esLabel).'</span>'
                .            '</a>'
                .    '</li>'
                .'</ul>';
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
            $sectionHtml = $section->html_template ?? '';
            
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
            
            $sectionHtml = str_replace('{{ content }}', $contentHtml, $sectionHtml);
            $sectionHtml = self::renderItemsWithData($sectionHtml);
           
            $html .= $sectionHtml;
            
            if ($section->css_styles) {
                $css .= $section->css_styles . "\n";
            }
            if ($section->js_scripts) {
                $js .= $section->js_scripts . "\n";
            }
        }
        
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
    public static function renderItemTemplate($index, $template, $item) {
        $rendered = $template;
    
        if (!is_string($item)) {
            if ($index == 0) {
                $rendered = str_replace('{{ $item.active }}', 'active', $rendered);
            } else {
                $rendered = str_replace('{{ $item.active }}', '', $rendered);
            }
            foreach ($item as $key => $value) {
                $rendered = str_replace('{{ $item.' . $key . ' }}', htmlspecialchars($value), $rendered);
                $rendered = str_replace('{{$item.' . $key . '}}', htmlspecialchars($value), $rendered);
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
                $modelName = '\\App\\Models\\' . toPascal($name);
                $list = $modelName::all();
                $lang = Lang::getLocale();
                
                if ($name == 'tour') {
                    $list = $modelName::where('language', $lang);
                } 

                if (isset($item['attributes']['sql'])) {
                    $exp = parseExpression($item['attributes']['sql']);
                    $list = $modelName::where($exp['key'], $exp['operator'], $exp['value']);
                }

            }

            $listArray = [];
            if ($list != null) {
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
        
            $attrPattern = '/(\w+)="([^"]*)"/';
            if (preg_match_all($attrPattern, $tag, $attrMatches, PREG_SET_ORDER)) {
                foreach ($attrMatches as $match) {
                    $attributes[$match[1]] = $match[2];
                }
            }
        
            $dataName = $attributes['name'] ?? null;
            $templateName = $attributes['template'] ?? null;
            $limit = isset($attributes['limit']) ? (int)$attributes['limit'] : null;
        
            if (!$dataName || !isset($dataSources[$dataName])) {
                return '<!-- Data source "' . htmlspecialchars($dataName) . '" not found -->';
            }
        
            if (!$templateName || !isset($templates[$templateName])) {
                return '<!-- Template "' . htmlspecialchars($templateName) . '" not found -->';
            }
        
            $data = $dataSources[$dataName];
            $template = $templates[$templateName];
        
            if ($limit !== null && $limit > 0) {
                $data = array_slice($data, 0, $limit);
            }
        
            $output = '';
            $i = 0; 
            foreach ($data as $item) {
                $output .= self::renderItemTemplate($i, $template, $item);
                $i += 1;
            }
            return $output;
        }, $html);
    }
    
    protected static function processInlineTranslationDefinitions($html) {
        $pattern = '/\{\s*([^}]*(?:\blang\s*=\s*"[^"]*"[^}]*)+)\s*\}/';
        return preg_replace_callback($pattern, function($m) {
            $attrs = [];
            if (preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $m[1], $am, PREG_SET_ORDER)) {
                foreach ($am as $a) { $attrs[$a[1]] = $a[2]; }
            }
            if (isset($attrs['lang'], $attrs['key'], $attrs['value'])) {
                try {
                    \App\Models\Translation::setValue($attrs['key'], $attrs['lang'], $attrs['value']);
                    return '';
                } catch (\Throwable $e) {
                    return '';
                }
            }
            return $m[0];
        }, $html);
    }

    protected static function renderTranslationTags($html) {
        $html = preg_replace_callback('/<t\s+([^>]*?)>(.*?)<\/t>/si', function($m) {
            $attrs = [];
            if (preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $m[1], $am, PREG_SET_ORDER)) {
                foreach ($am as $a) { $attrs[$a[1]] = $a[2]; }
            }
            $key = $attrs['key'] ?? null;
            $default = $attrs['default'] ?? $m[2];
            if (!$key) { return $m[2]; }
            $val = Lang::get($key);
            if ($val === $key && isset($default)) { $val = $default; }
            return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
        }, $html);
        $html = preg_replace_callback('/<t\s+([^>]*?)\/>/i', function($m) {
            $attrs = [];
            if (preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $m[1], $am, PREG_SET_ORDER)) {
                foreach ($am as $a) { $attrs[$a[1]] = $a[2]; }
            }
            $key = $attrs['key'] ?? null;
            $default = $attrs['default'] ?? '';
            if (!$key) { return ''; }
            $val = Lang::get($key);
            if ($val === $key && isset($default)) { $val = $default; }
            return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
        }, $html);

        return $html;
    }

    protected static function renderTransExpressions($html) {
        $patterns = [
            '/\{\{\s*trans\(\'([^\']+)\'\)\s*\}\}/',
            '/\{\{\s*trans\(\"([^\"]+)\"\)\s*\}\}/',
            '/\{\{\s*__\(\'([^\']+)\'\)\s*\}\}/',
            '/\{\{\s*__\(\"([^\"]+)\"\)\s*\}\}/',
        ];
        foreach ($patterns as $pattern) {
            $html = preg_replace_callback($pattern, function($m) {
                $val = Lang::get($m[1]);
                return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
            }, $html);
        }
        return $html;
    }

    protected static function processTranslations($html) {
        $html = self::processInlineTranslationDefinitions($html);
        $html = self::renderTranslationTags($html);
        $html = self::renderTransExpressions($html);
        return $html;
    }

    /**
     * Render page with template
     */
    public static function renderWithTemplate($page, $template, $sections, $menuPages)
    {
        self::processInlineTranslationDefinitions($template->html_content ?? '');
        $menuHtml = Html::buildMenuHtml($menuPages);
        
        $sectionsHtml = Html::renderSections($sections);

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        $currentUrl = $protocol . "://" . $host . $uri;
        
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
        
        $html = $template->html_content ?? '';
        
        foreach ($variables as $key => $value) {
            $html = str_replace('{{ ' . $key . ' }}', $value, $html);
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }
        
        $html = self::processTranslations($html);

        if ($template->css_content) {
            $cssTag = '<style>' . $template->css_content . '</style>';
            if (strpos($html, '</head>') !== false) {
                $html = str_replace('</head>', $cssTag . '</head>', $html);
            } else {
                $html = $cssTag . $html;
            }
        }
        
        if ($template->js_content) {
            $jsTag = '<script>' . $template->js_content . '</script>';
            if (strpos($html, '</body>') !== false) {
                $html = str_replace('</body>', $jsTag . '</body>', $html);
            } else {
                $html .= $jsTag;
            }
        }
        
        echo $html;
        exit;
    }

    /**
     * Render page with template
     */
    public static function renderItemWithTemplate($item, $menuPages)
    {
        $template = Template::where("slug", "=", $item->template_slug);
        if (count($template) > 0) {
            $template = $template[0];
        }

        self::processInlineTranslationDefinitions($template->html_content ?? '');
        $menuHtml = Html::buildMenuHtml($menuPages);

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        $currentUrl = $protocol . "://" . $host . $uri;

        $uri_array = explode("/", $uri);

        $kml_folder = $uri_array[count($uri_array)-1];
        
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
            'kml' => getKmlFiles($kml_folder),
            'custom_css' => '',
            'custom_js' => ''
        ];
        
        $html = $template->html_content ?? '';
        
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
        $html = self::processTranslations($html);

        if ($template->css_content) {
            $cssTag = '<style>' . $template->css_content . '</style>';
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
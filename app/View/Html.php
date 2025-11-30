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
            
            $contentHtmlAll = '';
            $currentLanguage = Lang::getLocale();
            $contents = \App\Models\Content::getBySection($section->id, true, $currentLanguage);
            
            $contentByIndex = [];
            $namedMap = [];
            foreach ($contents as $content) {
                if ($content->content_type === 'html') {
                    $entry = $content->content;
                } else {
                    $entry = '<div>' . nl2br(htmlspecialchars($content->content)) . '</div>';
                }
                $contentHtmlAll .= $entry;
                $contentByIndex[] = $entry;
                if (!empty($content->title)) {
                    if (!isset($namedMap[$content->title])) {
                        $namedMap[$content->title] = $entry;
                    }
                    $slug = self::slugifyContentKey($content->title);
                    if (!empty($slug) && !isset($namedMap[$slug])) {
                        $namedMap[$slug] = $entry;
                    }
                }
            }
            
            // Determine how to populate legacy {{ content }}
            $usesNumbered = preg_match('/\{\{\s*content\d+\s*\}\}/', $sectionHtml) === 1;
            $usesNamed = false;
            foreach ($namedMap as $k => $_) {
                if (strpos($sectionHtml, '{{ ' . $k . ' }}') !== false || strpos($sectionHtml, '{{' . $k . '}}') !== false) {
                    $usesNamed = true; break;
                }
            }
            $firstEntry = $contentByIndex[0] ?? '';
            $contentReplacement = $firstEntry;
            $sectionHtml = str_replace('{{ content }}', $contentReplacement, $sectionHtml);
            $sectionHtml = str_replace('{{content}}', $contentReplacement, $sectionHtml);

            $sectionHtml = str_replace('{{ pagination }}', pagination(9), $sectionHtml);
            // Always expose the concatenated form via a separate variable
            $sectionHtml = str_replace('{{ content_all }}', $contentHtmlAll, $sectionHtml);
            $sectionHtml = str_replace('{{content_all}}', $contentHtmlAll, $sectionHtml);
            
            $i = 1;
            foreach ($contentByIndex as $entry) {
                $key = 'content' . $i;
                $sectionHtml = str_replace('{{ ' . $key . ' }}', $entry, $sectionHtml);
                $sectionHtml = str_replace('{{' . $key . '}}', $entry, $sectionHtml);
                $i++;
            }
            foreach ($namedMap as $k => $v) {
                $sectionHtml = str_replace('{{ ' . $k . ' }}', $v, $sectionHtml);
                $sectionHtml = str_replace('{{' . $k . '}}', $v, $sectionHtml);
            }
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
        // helper to replace all spacing variations using regex
        $replace = function($html, $key, $replacement) {
            $pattern = '/\{\{\s*\$item\.' . preg_quote($key, '/') . '\s*\}\}/';
            return preg_replace_callback($pattern, function() use ($replacement) { return (string)$replacement; }, $html);
        };
        if (!is_string($item)) {
            // active handling
            $rendered = $replace($rendered, 'active', $index == 0 ? 'active' : '');
            foreach ($item as $key => $value) {
                if ($key === 'attributs_tag' || $key === 'list') {
                    $rendered = $replace($rendered, $key, (string)$value);
                    continue;
                }
                $converted = self::convertMarkdownLinks((string)$value);
                $repVal = ($converted !== null) ? $converted : htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
                $rendered = $replace($rendered, $key, $repVal);
            }
            // fallback for {{ $item.value }} when 'value' missing
            if (!array_key_exists('value', (array)$item)) {
                $fallback = '';
                foreach (['title','name','text','label'] as $cand) {
                    if (!empty($item[$cand])) { $fallback = (string)$item[$cand]; break; }
                }
                $converted = self::convertMarkdownLinks($fallback);
                $repVal = ($converted !== null) ? $converted : htmlspecialchars($fallback, ENT_QUOTES, 'UTF-8');
                $rendered = $replace($rendered, 'value', $repVal);
            }
        } else {
            $converted = self::convertMarkdownLinks((string)$item);
            $repVal = ($converted !== null) ? $converted : htmlspecialchars((string)$item, ENT_QUOTES, 'UTF-8');
            $rendered = $replace($rendered, 'value', $repVal);
        }
        return $rendered;
    }

    /**
     * Convert link-only Markdown [text](url) to safe HTML anchors. Non-link text is escaped.
     * Supports http/https URLs only. Returns null if no markdown link is found.
     */
    protected static function convertMarkdownLinks($text) {
        $pattern = '/\[(?<text>[^\]]+)\]\((?<url>https?:\/\/[^\)\s]+)\)/i';
        if (!preg_match($pattern, $text)) {
            return null;
        }
        $result = '';
        $offset = 0;
        while (preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE, $offset)) {
            $start = $m[0][1];
            // escape text before the match
            $before = substr($text, $offset, $start - $offset);
            $result .= htmlspecialchars($before, ENT_QUOTES, 'UTF-8');
            $rawText = $m['text'][0];
            $rawUrl = $m['url'][0];
            $escText = htmlspecialchars($rawText, ENT_QUOTES, 'UTF-8');
            $escUrl = htmlspecialchars($rawUrl, ENT_QUOTES, 'UTF-8');
            $result .= '<a href="' . $escUrl . '">' . $escText . '</a>';
            $offset = $start + strlen($m[0][0]);
        }
        // escape remainder
        if ($offset < strlen($text)) {
            $result .= htmlspecialchars(substr($text, $offset), ENT_QUOTES, 'UTF-8');
        }
        return $result;
    }

    protected static function slugifyContentKey($str) {
        $s = (string)$str;
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
            if ($converted !== false) { $s = $converted; }
        }
        $s = strtolower($s);
        $s = preg_replace('/[^a-z0-9]+/i', '_', $s);
        $s = trim($s, '_');
        return $s;
    }

    /**
     * Render <items .../> tags within the provided HTML.
     * - Supports model-backed and JSON-backed lists
     * - Supports inline single item from tag attributes
     * - Respects lang filtering (accepts 'sp' as alias of 'es' and comma-separated values)
     */
    public static function renderItemsWithData($html) {
        $pattern = '/<items\s+[^>]+\/>/i';
        // Preload templates once
        $templateItem = \App\Models\TemplateItem::all();
        $templates = [];
        foreach ($templateItem as $ti) {
            $templates[$ti->slug] = $ti->html_template;
        }

        return preg_replace_callback($pattern, function($matches) use ($templates) {
            $tag = $matches[0];
            $attributes = [];
            if (preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $tag, $attrMatches, PREG_SET_ORDER)) {
                foreach ($attrMatches as $m) { $attributes[$m[1]] = $m[2]; }
            }

            // Language filter
            if (isset($attributes['lang'])) {
                $curr = strtolower(Lang::getLocale());
                // reduce to primary language code (e.g., en-US -> en)
                $curr = explode('-', str_replace('_', '-', $curr))[0];
                $wanted = preg_split('/[\s,]+/', strtolower($attributes['lang']), -1, PREG_SPLIT_NO_EMPTY);

                $wanted = array_map(function($c){
                    $c = str_replace('_', '-', trim(strtolower($c)));
                    $c = ($c === 'sp') ? 'es' : $c; // support sp as Spanish
                    return explode('-', $c)[0];
                }, $wanted);
                if (!in_array($curr, $wanted, true)) { return ''; }
            }

            $dataName = $attributes['name'] ?? null;
            $templateName = $attributes['template'] ?? null;
            $limit = isset($attributes['limit']) ? (int)$attributes['limit'] : null;

            if (!$dataName) { return '<!-- Data source name missing -->'; }
            if (!$templateName || !isset($templates[$templateName])) {
                return '<!-- Template "' . htmlspecialchars($templateName) . '" not found -->';
            }

            // Build data for this tag
            $list = null;
            if (isset($attributes['data'])) {
                $json = str_replace("'", '"', $attributes['data']);
                $list = json_decode($json, true);
                if ($list === null) { $list = []; }
                if (is_array($list) && array_keys($list) !== range(0, count($list) - 1)) { $list = [$list]; }
            } elseif (isset($attributes['list'])) {
                // Expand inline list into multiple items; split on commas only so values can contain spaces
                $parts = preg_split('/_\s*/', $attributes['list'], -1, PREG_SPLIT_NO_EMPTY);

                $constants = [];
                $reserved = ['name','template','limit','sql','data','lang','list','tag'];
                foreach ($attributes as $k => $v) {
                    if (!in_array($k, $reserved, true)) { $constants[$k] = $v; }
                }
                $pairs = [];
                foreach ($constants as $k => $v) { $pairs[] = $k . '="' . $v . '"'; }
                $baseAttrTag = implode(' ', $pairs);
                $tagName = isset($attributes['tag']) && trim($attributes['tag']) !== '' ? trim($attributes['tag']) : null;
                if ($tagName) {
                    $itemsHtml = '';
                    foreach ($parts as $val) {
                        $text = trim($val);
                        $converted = self::convertMarkdownLinks($text);
                        $content = ($converted !== null) ? $converted : htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
                        $itemsHtml .= '<' . $tagName . '>' . $content . '</' . $tagName . '>';
                    }
                    $single = $constants;
                    $single['attributs_tag'] = $baseAttrTag;
                    $single['list'] = $itemsHtml;
                    $single['value'] = '';
                    $list = [$single];
                } else {
                    // Expand inline list into multiple items when no tag specified
                    $list = [];
                    foreach ($parts as $val) {
                        $item = $constants;
                        $item['value'] = trim($val);
                        $item['attributs_tag'] = $baseAttrTag;
                        $list[] = $item;
                    }
                }
            } else {
                $modelName = '\\App\\Models\\' . toPascal($dataName);
                if (class_exists($modelName)) {
                    $list = $modelName::all();
                    $lang = Lang::getLocale();
                    if ($dataName === 'tour') {
                        $list = $modelName::where('language', $lang);
                        if (isset($attributes['condition'])) {
                            $list = $modelName::condition("language='".$lang."' AND ".$attributes['condition']);
                        }
                    } elseif ($dataName === 'blog') {
                        $list = $modelName::all();
                        $currentLanguage = Lang::getLocale();
                        foreach($list as $item) {
                            $item->slug = toKebabCase($item->title);
                            if ($currentLanguage === "es") {
                                $item->short_texte = $item->short_texte_es;
                                $item->title = $item->title_es;
                                $item->slug = toKebabCase($item->slug_es);
                            }
                        }
                    }
                    if (isset($attributes['sql'])) {
                        $exp = parseExpression($attributes['sql']);
                        $list = $modelName::where($exp['key'], $exp['operator'], $exp['value']);
                    }
                    if (isset($attributes['pagination'])) {
                        $resultsPerPage = $attributes['pagination'];
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $resultsPerPage;
                        $condition = '';
                        if (isset($attributes['condition'])) {
                            $condition = $attributes['condition'];
                        }
                        $list = $modelName::all_limit_offset($attributes['pagination'], $offset, $condition);
                    }
                } else {
                    // Inline single item
                    $reserved = ['name','template','limit','sql','data','lang','list','tag'];
                    $single = [];
                    foreach ($attributes as $k => $v) {
                        if (!in_array($k, $reserved, true)) { $single[$k] = $v; }
                    }
                    $pairs = [];
                    foreach ($single as $k => $v) { $pairs[] = $k . '="' . $v . '"'; }
                    $single['attributs_tag'] = implode(' ', $pairs);
                    $list = [$single];
                }
            }

            // Normalize entries
            $data = [];
            if ($list != null) {
                foreach ($list as $entry) {
                    if (is_string($entry)) { $data[] = $entry; }
                    elseif (is_array($entry)) { $data[] = $entry; }
                    elseif (is_object($entry)) {
                        $data[] = method_exists($entry, 'toArray') ? $entry->toArray() : (array)$entry;
                    }
                }
            }

            if ($limit !== null && $limit > 0) { $data = array_slice($data, 0, $limit); }
            $template = $templates[$templateName];
            $out = '';
            $i = 0;
            foreach ($data as $item) { $out .= self::renderItemTemplate($i, $template, $item); $i++; }
            return $out;
        }, $html);
    }

    /**
     * Parse inline translation definitions like { lang="en" key="..." value="..." }
     */
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
        
        // Render any <items .../> tags in the template HTML itself
        $html = self::renderItemsWithData($html);
        // Then apply translations
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
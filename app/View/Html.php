<?php 

namespace App\View;
use App\Localization\Lang;
use App\Models\Model;

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
                        <a class="nav-link flag" href="'.switchLanguage('en').'"><img src="https://madagascar-green-tours.com/assets/img/logos/uk_rounded.png" alt="United Kingdom flag" width="28" height="28">
                            <span>English</span>
                            </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link flag" href="'.switchLanguage('es').'"><img src="https://madagascar-green-tours.com/assets/img/logos/sp_rounded.png" alt="Spain flag" width="28" height="28">
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
        foreach ($item as $key => $value) {
            $rendered = str_replace('{{ $item.' . $key . ' }}', htmlspecialchars($value), $rendered);
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
            $modelName = '\\App\\Models\\' . ucfirst($name);
            $list = $modelName::all();
            
            $lang = Lang::getLocale();
            if ($name == 'tour') {
                $list = $modelName::where('language', $lang);
            }

            $listArray = [];
            foreach($list as $media){
            
                $item = [];
                foreach($media->toArray() as $key => $value){
                    $item[$key] = $value;
                }
                $listArray[] = $item;
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
}
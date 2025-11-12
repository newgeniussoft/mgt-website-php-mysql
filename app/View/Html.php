<?php 

namespace App\View;
use App\Localization\Lang;

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
        foreach ($menuPages as $menuPage) {
            $active = (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/' . $menuPage->slug) ? ' class="active"' : '';
            $url = $menuPage->is_homepage ? '/' : '/' . $menuPage->slug;
            $html .= '<li' . $active . ' class="nav-item"><a href="' . $url . '" class="nav-link">' . htmlspecialchars($menuPage->title) . '</a></li>';
        }
        $html .= '</ul>';
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
            
            // Process <items> tags with template items
            $itemsTags = self::findItemsTags($sectionHtml);
            
            foreach ($itemsTags as $itemsTag) {
                echo var_dump($itemsTag);
               // $itemsTagHtml = self::processItemsTag($itemsTag);
               // $sectionHtml = str_replace($itemsTag['tag'], $itemsTagHtml, "test item");
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
 * Extract items tag attributes as an object/array
 * 
 * @param string $html The HTML content containing the items tag
 * @return array|null Array of attributes or null if not found
 */
function extractItemsTag($html) {
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
function findItemsTags($html) {
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
function renderItemTemplate($template, $item) {
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
function renderItemsWithData($html, $dataSources, $templates) {
    $pattern = '/<items\s+[^>]+\/>/i';
    
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
            $output .= renderItemTemplate($template, $item);
        }
        
        return $output;
        
    }, $html);
}
}
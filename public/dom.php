<?php

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

// ========================================
// EXAMPLE USAGE
// ========================================

// Define your data sources
$media = [
    ["original_filename" => "DSC_1000.jpg", "file_type" => "JPG", "url" => "./photos/DSC_1000.jpg"],
    ["original_filename" => "DSC_1080.png", "file_type" => "PNG", "url" => "./photos/DSC_1080.png"],
    ["original_filename" => "DSC_1070.png", "file_type" => "PNG", "url" => "./photos/DSC_1070.png"],
    ["original_filename" => "DSC_1180.png", "file_type" => "PNG", "url" => "./photos/DSC_1180.png"],
];

// Define your templates
$templates = [
    'media-grid' => '
    <div class="media-item col-md-4">
        <div class="media-thumbnail">
            <img src="{{ $item.url }}" alt="{{ $item.original_filename }}" />
        </div>
        <div class="media-info">
            <h4>{{ $item.original_filename }}</h4>
            <p class="media-type">{{ $item.file_type }}</p>
            <a href="{{ $item.url }}" class="btn-download" download>Download</a>
        </div>
    </div>',
    
    'media-list' => '
    <li class="media-list-item">
        <span class="filename">{{ $item.original_filename }}</span>
        <span class="type">{{ $item.file_type }}</span>
    </li>'
    
];

// Your HTML with items tag
$html = '<html>
<head>
    <title>Preview</title>
    <style>
        .media-item { display: inline-block; margin: 10px; padding: 15px; border: 1px solid #ddd; }
        .media-thumbnail img { max-width: 200px; height: auto; }
        .media-info h4 { margin: 10px 0 5px 0; }
        .btn-download { display: inline-block; margin-top: 10px; padding: 5px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Media Gallery</h1>
    <div class="row">
        <items name="media" template="media-grid" limit="3" />
    </div>
    <items name="media" template="media-list" limit="3" />
</body>
</html>';

// Render the HTML
$dataSources = [
    'media' => $media,
];

$renderedHtml = renderItemsWithData($html, $dataSources, $templates);

echo $renderedHtml;

/* 
OUTPUT will be:
<html>
<head>
    <title>Preview</title>
    <style>...</style>
</head>
<body>
    <h1>Media Gallery</h1>
    <div class="row">
        <div class="media-item col-md-4">
            <div class="media-thumbnail">
                <img src="./photos/DSC_1000.jpg" alt="DSC_1000.jpg" />
            </div>
            <div class="media-info">
                <h4>DSC_1000.jpg</h4>
                <p class="media-type">JPG</p>
                <a href="./photos/DSC_1000.jpg" class="btn-download" download>Download</a>
            </div>
        </div>
        <div class="media-item col-md-4">
            <div class="media-thumbnail">
                <img src="./photos/DSC_1080.png" alt="DSC_1080.png" />
            </div>
            <div class="media-info">
                <h4>DSC_1080.png</h4>
                <p class="media-type">PNG</p>
                <a href="./photos/DSC_1080.png" class="btn-download" download>Download</a>
            </div>
        </div>
    </div>
</body>
</html>
*/

?>
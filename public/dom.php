<?php

/**
 * Extract items tag attributes as an object/array
 * 
 * @param string $html The HTML content containing the items tag
 * @return array|null Array of attributes or null if not found
 */
function extractItemsTag($html) {
    // Pattern to match the items tag and capture its attributes
    $pattern = '/<items\s+([^>]+)\/>/i';
    
    if (preg_match($pattern, $html, $matches)) {
        $attributesString = $matches[1];
        $attributes = [];
        
        // Pattern to match attribute="value" pairs
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
            
            // Extract attributes from this tag
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

// Example usage:
$html = '<div><items name="media" template="media-grid" limit="6" /><p>Some text</p><items name="posts" template="list" limit="10" /></div>';

// Extract as object/array
$attributes = extractItemsTag($html);
print_r($attributes);
/* Output:
Array
(
    [name] => media
    [template] => media-grid
    [limit] => 6
)
*/

// Find all items tags
$allItems = findItemsTags($html);
print_r($allItems);
/* Output:
Array
(
    [0] => Array
        (
            [tag] => <items name="media" template="media-grid" limit="6" />
            [attributes] => Array
                (
                    [name] => media
                    [template] => media-grid
                    [limit] => 6
                )
        )
    [1] => Array
        (
            [tag] => <items name="posts" template="list" limit="10" />
            [attributes] => Array
                (
                    [name] => posts
                    [template] => list
                    [limit] => 10
                )
        )
)
*/

?>
Got it 👍
You want a PHP script that:

Reads an HTML file from your server.

Parses all tags.

Extracts their tag name, attributes, and children into a structured array.

### Here’s a clean way to do it using PHP’s DOMDocument (built-in, no external libraries needed):
```php
<?php 
/** * Recursively converts DOM nodes to array */ 
function domNodeToArray($node) { 
    $output = []; 
    if ($node->nodeType == XML_ELEMENT_NODE) { 
        $output['tag'] = $node->nodeName; 
        // Attributes 
        if ($node->hasAttributes()) { 
            foreach ($node->attributes as $attr) { 
                $output['attributes'][$attr->nodeName] = $attr->nodeValue; 
            } 
        } // Children 
        if ($node->hasChildNodes()) { 
            foreach ($node->childNodes as $child) { 
                if ($child->nodeType == XML_TEXT_NODE) { 
                    $text = trim($child->nodeValue); 
                    if ($text !== '') { 
                        $output['text'][] = $text; 
                        } 
                    } else { 
                        $output['children'][] = domNodeToArray($child); 
                    } 
                }
            } 
        } 
        return $output; 
    } 
    /** * Load HTML file and convert to array */ 
    function htmlFileToArray($filePath) { 
        if (!file_exists($filePath)) { 
            die("File not found: $filePath"); 
        }
        $dom = new DOMDocument(); libxml_use_internal_errors(true); 
        // Suppress warnings for malformed HTML 
        $dom->loadHTMLFile($filePath); 
        libxml_clear_errors(); 
        $body = $dom->getElementsByTagName("body")->item(0); 
        $result = []; 
        if ($body && $body->hasChildNodes()) { 
            foreach ($body->childNodes as $child) { 
                if ($child->nodeType == XML_ELEMENT_NODE) { 
                    $result[] = domNodeToArray($child); 
                }
            }
        } 
        return $result; } 
        // Example usage 
        $htmlArray = htmlFileToArray("example.html"); 
        echo "<pre>"; print_r($htmlArray); echo "</pre>"; 
```

✅ Example example.html

```html
<!DOCTYPE html> <html> <head> <title>Demo</title> </head> <body> <div id="main" class="container"> <h1>Hello World</h1> <p>This is <b>bold</b> text.</p> </div> </body> </html> 
```

✅ Example Output (simplified)
```php
Array ( [0] => Array ( [tag] => div [attributes] => Array ( [id] => main [class] => container ) [children] => Array ( [0] => Array ( [tag] => h1 [text] => Array ( [0] => Hello World ) ) [1] => Array ( [tag] => p [children] => Array ( [0] => Array ( [tag] => b [text] => Array ( [0] => bold ) ) ) [text] => Array ( [0] => This is [1] => text. ) ) ) ) ) 
```
👉 This way, every HTML tag, its attributes, and its children are mapped into a PHP array.

Do you also want the script to keep the <head> content (meta, title, etc.), or only parse the <body>?


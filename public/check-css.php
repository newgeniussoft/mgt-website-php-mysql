<?php
require_once __DIR__ . '/../bootstrap/app.php';

// Get the generated asset URL
$cssUrl = asset('css/styles.css');
$cssPath = __DIR__ . '/css/styles.css';

// Check if file exists
$fileExists = file_exists($cssPath);
$fileSize = $fileExists ? filesize($cssPath) : 0;

// Try to read first few lines
$cssContent = '';
if ($fileExists) {
    $cssContent = file_get_contents($cssPath, false, null, 0, 500);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Check</title>
    
    <!-- Try loading the CSS -->
    <link rel="stylesheet" href="<?php echo $cssUrl; ?>">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #2196F3;
        }
        .success {
            background: #e8f5e9;
            border-left-color: #4CAF50;
        }
        .error {
            background: #ffebee;
            border-left-color: #f44336;
        }
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CSS Loading Diagnostic</h1>
        
        <div class="info <?php echo $fileExists ? 'success' : 'error'; ?>">
            <strong>File Exists:</strong> <?php echo $fileExists ? 'YES' : 'NO'; ?>
        </div>
        
        <div class="info">
            <strong>File Path:</strong> <code><?php echo $cssPath; ?></code>
        </div>
        
        <div class="info">
            <strong>File Size:</strong> <?php echo number_format($fileSize); ?> bytes
        </div>
        
        <div class="info">
            <strong>Generated URL:</strong> <code><?php echo $cssUrl; ?></code>
        </div>
        
        <div class="info">
            <strong>APP_URL (.env):</strong> <code><?php echo env('APP_URL', 'NOT SET'); ?></code>
        </div>
        
        <div class="info">
            <strong>Config URL:</strong> <code><?php echo config('app.url'); ?></code>
        </div>
        
        <h2>Test if CSS is Applied</h2>
        <p class="text-primary font-inter-bold">This text should be GREEN and use Inter Bold font if CSS loaded correctly.</p>
        
        <h2>Direct Access Test</h2>
        <p>
            <a href="<?php echo $cssUrl; ?>" target="_blank">Click here to open styles.css directly</a>
        </p>
        
        <h2>CSS File Preview (first 500 bytes)</h2>
        <pre><?php echo htmlspecialchars($cssContent); ?></pre>
        
        <h2>Browser Console Check</h2>
        <p>Open browser DevTools (F12) and check:</p>
        <ul>
            <li><strong>Console tab:</strong> Look for any errors</li>
            <li><strong>Network tab:</strong> Check if styles.css loads with status 200</li>
            <li><strong>Elements tab:</strong> Inspect this text and check computed styles</li>
        </ul>
    </div>
    
    <script>
        console.log('=== CSS Loading Diagnostic ===');
        console.log('Generated CSS URL:', '<?php echo $cssUrl; ?>');
        console.log('File exists:', <?php echo $fileExists ? 'true' : 'false'; ?>);
        
        // Check if CSS loaded by inspecting computed styles
        window.addEventListener('load', function() {
            const testElement = document.querySelector('.text-primary');
            if (testElement) {
                const computedStyle = window.getComputedStyle(testElement);
                const color = computedStyle.color;
                const fontFamily = computedStyle.fontFamily;
                
                console.log('Computed color:', color);
                console.log('Computed font-family:', fontFamily);
                console.log('Expected color: rgb(25, 135, 84) or #198754');
                console.log('Expected font: Inter Bold');
                
                // Check if CSS variable is defined
                const primaryColor = computedStyle.getPropertyValue('--color-primary');
                console.log('CSS Variable --color-primary:', primaryColor || 'NOT DEFINED');
            }
            
            // Check all loaded stylesheets
            console.log('Loaded stylesheets:', document.styleSheets.length);
            for (let i = 0; i < document.styleSheets.length; i++) {
                const sheet = document.styleSheets[i];
                console.log(`Stylesheet ${i}:`, sheet.href || 'inline');
            }
        });
    </script>
</body>
</html>

<?php
/**
 * Create Media Upload Directories
 */

$baseDir = __DIR__ . '/uploads/';
$directories = [
    'images',
    'videos', 
    'audios',
    'documents',
    'others',
    'thumbnails'
];

echo "Creating media upload directories...\n";

foreach ($directories as $dir) {
    $fullPath = $baseDir . $dir;
    
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "✓ Created: uploads/$dir\n";
        } else {
            echo "✗ Failed to create: uploads/$dir\n";
        }
    } else {
        echo "- Already exists: uploads/$dir\n";
    }
    
    // Check if writable
    if (is_writable($fullPath)) {
        echo "  ✓ Directory is writable\n";
    } else {
        echo "  ✗ Directory is not writable\n";
    }
}

// Create .htaccess for security
$htaccessContent = "# Protect uploads directory\n";
$htaccessContent .= "Options -Indexes\n";
$htaccessContent .= "Options -ExecCGI\n";
$htaccessContent .= "\n";
$htaccessContent .= "# Allow only specific file types\n";
$htaccessContent .= "<FilesMatch \"\\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|sh|cgi)$\">\n";
$htaccessContent .= "    Order Allow,Deny\n";
$htaccessContent .= "    Deny from all\n";
$htaccessContent .= "</FilesMatch>\n";

$htaccessFile = $baseDir . '.htaccess';
if (file_put_contents($htaccessFile, $htaccessContent)) {
    echo "✓ Created security .htaccess file\n";
} else {
    echo "✗ Failed to create .htaccess file\n";
}

echo "\nDone! You can now delete this file: create_media_dirs.php\n";
?>

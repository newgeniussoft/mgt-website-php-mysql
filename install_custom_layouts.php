<?php
/**
 * Custom Layouts Installation Script
 * 
 * This script installs the custom layouts and page sections system
 * Run this after setting up the basic CMS
 */

// Database configuration
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", 
                   $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Installing Custom Layouts System...</h2>\n";
    
    // Read and execute the migration SQL
    $migrationSQL = file_get_contents('database/migrate_custom_layouts.sql');
    
    if (!$migrationSQL) {
        throw new Exception('Could not read migration file');
    }
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $migrationSQL)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "✓ Executed SQL statement successfully<br>\n";
            } catch (PDOException $e) {
                // Skip if table already exists
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo "⚠ Table already exists, skipping...<br>\n";
                } else {
                    throw $e;
                }
            }
        }
    }
    
    // Create uploads directory for layouts
    $uploadsDir = 'uploads/layouts';
    if (!is_dir($uploadsDir)) {
        if (mkdir($uploadsDir, 0755, true)) {
            echo "✓ Created uploads directory: {$uploadsDir}<br>\n";
        } else {
            echo "⚠ Could not create uploads directory: {$uploadsDir}<br>\n";
        }
    } else {
        echo "✓ Uploads directory already exists<br>\n";
    }
    
    // Create sections uploads directory
    $sectionsDir = 'uploads/sections';
    if (!is_dir($sectionsDir)) {
        if (mkdir($sectionsDir, 0755, true)) {
            echo "✓ Created sections directory: {$sectionsDir}<br>\n";
        } else {
            echo "⚠ Could not create sections directory: {$sectionsDir}<br>\n";
        }
    } else {
        echo "✓ Sections directory already exists<br>\n";
    }
    
    echo "<h3>✅ Custom Layouts System Installation Complete!</h3>\n";
    echo "<h4>What's New:</h4>\n";
    echo "<ul>\n";
    echo "<li><strong>Custom Layouts:</strong> Create your own page layouts with HTML, CSS, and JavaScript using CodeMirror editor</li>\n";
    echo "<li><strong>Page Sections:</strong> Build pages with modular, reorderable content blocks</li>\n";
    echo "<li><strong>Drag & Drop:</strong> Easily reorder page sections with drag-and-drop interface</li>\n";
    echo "<li><strong>Section Types:</strong> Text, Image, Gallery, Video, CTA, and Custom HTML sections</li>\n";
    echo "<li><strong>Layout Templates:</strong> Pre-built layouts including Default, Hero, and Two-Column</li>\n";
    echo "<li><strong>Visual Editor:</strong> Rich text editor for section content with image upload</li>\n";
    echo "</ul>\n";
    
    echo "<h4>Next Steps:</h4>\n";
    echo "<ol>\n";
    echo "<li>Visit <a href='/admin/layouts'>/admin/layouts</a> to manage custom layouts</li>\n";
    echo "<li>Edit any page and click 'Manage Sections' to add modular content</li>\n";
    echo "<li>Create custom layouts with the CodeMirror editor</li>\n";
    echo "<li>Use the drag-and-drop interface to reorder page sections</li>\n";
    echo "</ol>\n";
    
    echo "<h4>Available Routes:</h4>\n";
    echo "<ul>\n";
    echo "<li><code>/admin/layouts</code> - Layout management dashboard</li>\n";
    echo "<li><code>/admin/layouts/create</code> - Create new custom layout</li>\n";
    echo "<li><code>/admin/pages/sections?page_id=X</code> - Manage page sections</li>\n";
    echo "</ul>\n";
    
} catch (Exception $e) {
    echo "<h3>❌ Installation Failed!</h3>\n";
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
    echo "<p>Please check your database configuration and try again.</p>\n";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Layouts Installation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            line-height: 1.6;
            background: #f8f9fa;
        }
        h2, h3, h4 {
            color: #198754;
        }
        code {
            background: #e9ecef;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-family: 'Monaco', 'Consolas', monospace;
        }
        ul, ol {
            padding-left: 2rem;
        }
        li {
            margin-bottom: 0.5rem;
        }
        .success {
            color: #198754;
        }
        .warning {
            color: #fd7e14;
        }
        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Content is generated by PHP above -->
</body>
</html>

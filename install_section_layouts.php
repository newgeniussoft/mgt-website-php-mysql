<?php
/**
 * Section-Based Layout System Installation Script
 * 
 * This script installs the advanced section-based layout system with CodeMirror integration
 * Run this after setting up the basic CMS and custom layouts system
 */

// Database configuration
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", 
                   $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Installing Section-Based Layout System...</h2>\n";
    
    // Read and execute the migration SQL
    $migrationSQL = file_get_contents('database/migrate_section_layouts.sql');
    
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
                // Skip if table/column already exists
                if (strpos($e->getMessage(), 'already exists') !== false || 
                    strpos($e->getMessage(), 'Duplicate column') !== false) {
                    echo "⚠ Already exists, skipping...<br>\n";
                } else {
                    throw $e;
                }
            }
        }
    }
    
    // Create uploads directories
    $directories = [
        'uploads/sections',
        'uploads/section-templates',
        'uploads/section-thumbnails'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                echo "✓ Created directory: {$dir}<br>\n";
            } else {
                echo "⚠ Could not create directory: {$dir}<br>\n";
            }
        } else {
            echo "✓ Directory already exists: {$dir}<br>\n";
        }
    }
    
    echo "<h3>✅ Section-Based Layout System Installation Complete!</h3>\n";
    echo "<h4>🎨 What's New:</h4>\n";
    echo "<ul>\n";
    echo "<li><strong>Section Layout Templates:</strong> Pre-built section templates (Hero, Services, Gallery, Contact)</li>\n";
    echo "<li><strong>CodeMirror Integration:</strong> Professional code editor for HTML, CSS, and JavaScript</li>\n";
    echo "<li><strong>Advanced Section Builder:</strong> Visual interface with drag-and-drop and live preview</li>\n";
    echo "<li><strong>Template Variables:</strong> Dynamic content with {{ variable }} syntax</li>\n";
    echo "<li><strong>Conditional Rendering:</strong> @if conditions and @foreach loops</li>\n";
    echo "<li><strong>Live Preview:</strong> Real-time preview of section changes</li>\n";
    echo "<li><strong>Section Templates:</strong> Reusable section layouts with customizable variables</li>\n";
    echo "</ul>\n";
    
    echo "<h4>🚀 Available Section Templates:</h4>\n";
    echo "<ul>\n";
    echo "<li><strong>Hero Section:</strong> Full-width hero with background image, title, subtitle, and CTA button</li>\n";
    echo "<li><strong>Services Grid:</strong> Responsive grid layout for showcasing services with icons</li>\n";
    echo "<li><strong>Image Gallery:</strong> Responsive gallery with lightbox functionality</li>\n";
    echo "<li><strong>Contact Form:</strong> Contact form with company information and validation</li>\n";
    echo "</ul>\n";
    
    echo "<h4>🔧 How to Use:</h4>\n";
    echo "<ol>\n";
    echo "<li>Go to any page in <code>/admin/pages</code></li>\n";
    echo "<li>Click the <strong>Section Builder</strong> button (purple code icon)</li>\n";
    echo "<li>Click <strong>Add New Section</strong> and choose a template</li>\n";
    echo "<li>Customize the section with CodeMirror editors (HTML, CSS, JS)</li>\n";
    echo "<li>Use template variables like <code>{{ title }}</code>, <code>{{ subtitle }}</code></li>\n";
    echo "<li>Preview changes in real-time in the preview panel</li>\n";
    echo "<li>Drag and drop to reorder sections</li>\n";
    echo "<li>Save changes and preview the page</li>\n";
    echo "</ol>\n";
    
    echo "<h4>📋 Template Variables Examples:</h4>\n";
    echo "<ul>\n";
    echo "<li><code>{{ title|Default Title }}</code> - Variable with default value</li>\n";
    echo "<li><code>@if(button_text)...@endif</code> - Conditional content</li>\n";
    echo "<li><code>@foreach(services as service)...@endforeach</code> - Loop through arrays</li>\n";
    echo "<li><code>{{ service.title }}</code> - Access object properties in loops</li>\n";
    echo "</ul>\n";
    
    echo "<h4>🎯 Available Routes:</h4>\n";
    echo "<ul>\n";
    echo "<li><code>/admin/pages/section-builder?page_id=X</code> - Advanced section builder</li>\n";
    echo "<li><code>/admin/pages/sections?page_id=X</code> - Simple section manager</li>\n";
    echo "<li><code>/admin/api/section-templates</code> - API endpoint for templates</li>\n";
    echo "</ul>\n";
    
    echo "<h4>💡 Pro Tips:</h4>\n";
    echo "<ul>\n";
    echo "<li>Use the <strong>Hero Section</strong> template for landing pages</li>\n";
    echo "<li>Combine multiple sections to create complex page layouts</li>\n";
    echo "<li>Use template variables to make sections reusable</li>\n";
    echo "<li>The live preview updates as you type in the CodeMirror editors</li>\n";
    echo "<li>Each section can have its own custom HTML, CSS, and JavaScript</li>\n";
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
    <title>Section-Based Layout System Installation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 900px;
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
            font-size: 0.9rem;
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
        .highlight {
            background: #fff3cd;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #ffc107;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <!-- Content is generated by PHP above -->
    
    <div class="highlight">
        <h4>🎉 Ready to Build Amazing Pages!</h4>
        <p>Your section-based layout system is now ready. You can create pages with multiple customizable sections, each with its own layout, styling, and functionality.</p>
        <p><strong>Next:</strong> Visit <a href="/admin/pages">/admin/pages</a> and click the purple <strong>Section Builder</strong> button on any page to start building!</p>
    </div>
</body>
</html>

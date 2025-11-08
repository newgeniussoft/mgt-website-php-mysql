<?php
/**
 * Media System Installation Script
 * Run this file once to create the media tables and folders
 */

require_once __DIR__ . '/bootstrap/app.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Media System Installation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        .step {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class='container'>";

echo "<h1>📁 Media System Installation</h1>";

try {
    // Read SQL file
    $sqlFile = __DIR__ . '/database/migrations/create_media_table.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: {$sqlFile}");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Execute SQL
    echo "<div class='step'>";
    echo "<h3>Step 1: Creating media tables...</h3>";
    
    $db->exec($sql);
    
    echo "<div class='success'>✓ Media tables created successfully!</div>";
    echo "</div>";
    
    // Verify installation
    echo "<div class='step'>";
    echo "<h3>Step 2: Verifying installation...</h3>";
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM media");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $mediaCount = $result['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM media_folders");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $folderCount = $result['count'];
    
    echo "<div class='success'>✓ Found {$mediaCount} media files and {$folderCount} folders in database</div>";
    echo "</div>";
    
    // Create upload directories
    echo "<div class='step'>";
    echo "<h3>Step 3: Creating upload directories...</h3>";
    
    $directories = [
        __DIR__ . '/public/uploads/media',
        __DIR__ . '/public/uploads/media/thumbnails'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "<div class='success'>✓ Created directory: {$dir}</div>";
        } else {
            echo "<div class='info'>ℹ Directory already exists: {$dir}</div>";
        }
    }
    echo "</div>";
    
    // Summary
    echo "<div class='step'>";
    echo "<h3>📋 Installation Summary</h3>";
    echo "<ul>";
    echo "<li>✓ Media tables created (media, media_folders)</li>";
    echo "<li>✓ {$folderCount} default folders created</li>";
    echo "<li>✓ Upload directories created</li>";
    echo "<li>✓ Sample data inserted</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='success'>";
    echo "<h3>🎉 Installation Completed Successfully!</h3>";
    echo "<p>You can now access the media library in your admin panel:</p>";
    echo "<p><strong>URL:</strong> <code>" . url($_ENV['APP_ADMIN_PREFIX'] . '/media') . "</code></p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>📚 Features Available:</h4>";
    echo "<ul>";
    echo "<li><strong>Upload:</strong> Upload multiple files at once (images, videos, audio, documents)</li>";
    echo "<li><strong>Organize:</strong> Create folders to organize your media files</li>";
    echo "<li><strong>Edit:</strong> Add titles, alt text, and descriptions to your media</li>";
    echo "<li><strong>Search:</strong> Quickly find files using the search function</li>";
    echo "<li><strong>Filter:</strong> Filter by file type (images, videos, audio, documents)</li>";
    echo "<li><strong>Download:</strong> Download files with tracking</li>";
    echo "<li><strong>Statistics:</strong> View total files, size, and downloads</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>📁 Default Folders:</h4>";
    echo "<ul>";
    echo "<li><strong>Images:</strong> JPG, PNG, GIF, WebP, SVG, BMP</li>";
    echo "<li><strong>Documents:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV</li>";
    echo "<li><strong>Videos:</strong> MP4, AVI, MOV, WMV, FLV, WebM</li>";
    echo "<li><strong>Audio:</strong> MP3, WAV, OGG, AAC, FLAC</li>";
    echo "<li><strong>Others:</strong> All other file types</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<a href='" . admin_url('media') . "' class='btn'>Go to Media Library</a>";
    echo "<a href='" . admin_url('media/upload') . "' class='btn' style='background: #28a745;'>Upload Files</a>";
    echo "<a href='" . admin_url('dashboard') . "' class='btn' style='background: #6c757d;'>Go to Dashboard</a>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Installation Failed</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>Troubleshooting:</h4>";
    echo "<ul>";
    echo "<li>Make sure your database connection is configured correctly in <code>.env</code></li>";
    echo "<li>Ensure the database user has CREATE TABLE permissions</li>";
    echo "<li>Check if the media tables already exist</li>";
    echo "<li>Verify the SQL file exists at: <code>database/migrations/create_media_table.sql</code></li>";
    echo "<li>Ensure the <code>public/uploads</code> directory is writable</li>";
    echo "</ul>";
    echo "</div>";
}

echo "</div>
</body>
</html>";

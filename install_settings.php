<?php
/**
 * Settings Installation Script
 * Run this file once to create the settings table and insert default values
 */

require_once __DIR__ . '/bootstrap/app.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Settings Installation</title>
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

echo "<h1>⚙️ Settings System Installation</h1>";

try {
    // Read SQL file
    $sqlFile = __DIR__ . '/database/migrations/create_settings_table.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: {$sqlFile}");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Execute SQL
    echo "<div class='step'>";
    echo "<h3>Step 1: Creating settings table...</h3>";
    
    $db->exec($sql);
    
    echo "<div class='success'>✓ Settings table created successfully!</div>";
    echo "</div>";
    
    // Verify installation
    echo "<div class='step'>";
    echo "<h3>Step 2: Verifying installation...</h3>";
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM settings");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['count'];
    
    echo "<div class='success'>✓ Found {$count} default settings in database</div>";
    echo "</div>";
    
    // Create uploads directory
    echo "<div class='step'>";
    echo "<h3>Step 3: Creating uploads directory...</h3>";
    
    $uploadDir = __DIR__ . '/public/uploads/settings';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
        echo "<div class='success'>✓ Created directory: {$uploadDir}</div>";
    } else {
        echo "<div class='info'>ℹ Directory already exists: {$uploadDir}</div>";
    }
    echo "</div>";
    
    // Summary
    echo "<div class='step'>";
    echo "<h3>📋 Installation Summary</h3>";
    echo "<ul>";
    echo "<li>✓ Settings table created</li>";
    echo "<li>✓ {$count} default settings inserted</li>";
    echo "<li>✓ Upload directory created</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='success'>";
    echo "<h3>🎉 Installation Completed Successfully!</h3>";
    echo "<p>You can now access the settings page in your admin panel:</p>";
    echo "<p><strong>URL:</strong> <code>" . url($_ENV['APP_ADMIN_PREFIX'] . '/settings') . "</code></p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>📚 Settings Groups Available:</h4>";
    echo "<ul>";
    echo "<li><strong>General:</strong> Site name, logo, tagline, etc.</li>";
    echo "<li><strong>Contact:</strong> Email, phone, address, business hours</li>";
    echo "<li><strong>Social:</strong> Social media links (Facebook, Twitter, Instagram, etc.)</li>";
    echo "<li><strong>Email:</strong> SMTP configuration for sending emails</li>";
    echo "<li><strong>SEO:</strong> Meta tags, Google Analytics, site verification</li>";
    echo "<li><strong>Appearance:</strong> Theme colors, pagination, date formats</li>";
    echo "<li><strong>System:</strong> Maintenance mode, cache, debug settings</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>💡 Usage Examples:</h4>";
    echo "<pre><code>";
    echo "// Get a single setting\n";
    echo "\$siteName = setting('site_name');\n\n";
    echo "// Get setting with default value\n";
    echo "\$email = setting('contact_email', 'default@example.com');\n\n";
    echo "// Get all settings as array\n";
    echo "\$allSettings = settings();\n\n";
    echo "// Get settings by group\n";
    echo "\$socialSettings = settings('social');\n";
    echo "</code></pre>";
    echo "</div>";
    
    echo "<a href='" . admin_url('settings') . "' class='btn'>Go to Settings Page</a>";
    echo "<a href='" . admin_url('dashboard') . "' class='btn' style='background: #28a745;'>Go to Dashboard</a>";
    
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
    echo "<li>Check if the settings table already exists</li>";
    echo "<li>Verify the SQL file exists at: <code>database/migrations/create_settings_table.sql</code></li>";
    echo "</ul>";
    echo "</div>";
}

echo "</div>
</body>
</html>";

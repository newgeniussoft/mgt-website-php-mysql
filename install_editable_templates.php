<?php
/**
 * Installation script for Editable Templates System
 * 
 * This script sets up the database tables and sample data for the
 * fully editable page templates system with CodeMirror integration.
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Install Editable Templates System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #f0fff0; padding: 10px; border: 1px solid green; margin: 10px 0; }
        .error { color: red; background: #fff0f0; padding: 10px; border: 1px solid red; margin: 10px 0; }
        .info { color: blue; background: #f0f0ff; padding: 10px; border: 1px solid blue; margin: 10px 0; }
        .step { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>";

echo "<h1>🚀 Editable Templates System Installation</h1>";

try {
    $db = Database::getInstance()->getConn();
    
    echo "<div class='info'>Connected to database successfully!</div>";
    
    // Read and execute the migration SQL
    $sqlFile = __DIR__ . '/database/migrate_editable_templates.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("Migration file not found: $sqlFile");
    }
    
    echo "<div class='step'>";
    echo "<h3>📋 Step 1: Reading Migration File</h3>";
    
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Could not read migration file");
    }
    
    echo "<div class='success'>Migration file loaded successfully</div>";
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h3>🗄️ Step 2: Creating Database Tables</h3>";
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^\s*--/', $stmt);
        }
    );
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        try {
            $db->exec($statement);
            $successCount++;
        } catch (PDOException $e) {
            // Check if it's just a "table already exists" error
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "<div class='info'>Table already exists (skipping): " . substr($statement, 0, 50) . "...</div>";
            } else {
                echo "<div class='error'>Error executing statement: " . $e->getMessage() . "</div>";
                $errorCount++;
            }
        }
    }
    
    echo "<div class='success'>Executed $successCount SQL statements successfully</div>";
    if ($errorCount > 0) {
        echo "<div class='error'>$errorCount statements had errors</div>";
    }
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h3>📁 Step 3: Creating Upload Directories</h3>";
    
    $uploadDirs = [
        __DIR__ . '/uploads/page-templates',
        __DIR__ . '/uploads/layouts'
    ];
    
    foreach ($uploadDirs as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                echo "<div class='success'>Created directory: $dir</div>";
            } else {
                echo "<div class='error'>Failed to create directory: $dir</div>";
            }
        } else {
            echo "<div class='info'>Directory already exists: $dir</div>";
        }
    }
    echo "</div>";
    
    echo "<div class='step'>";
    echo "<h3>🔧 Step 4: Verifying Installation</h3>";
    
    // Check if tables were created
    $tables = ['page_templates', 'template_editor_settings'];
    $allTablesExist = true;
    
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "<div class='success'>✓ Table '$table' exists with $count records</div>";
        } catch (PDOException $e) {
            echo "<div class='error'>✗ Table '$table' not found</div>";
            $allTablesExist = false;
        }
    }
    
    // Check if pages table has new columns
    try {
        $stmt = $db->query("SHOW COLUMNS FROM pages LIKE 'template_id'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'>✓ Pages table updated with template_id column</div>";
        } else {
            echo "<div class='error'>✗ Pages table missing template_id column</div>";
            $allTablesExist = false;
        }
    } catch (PDOException $e) {
        echo "<div class='error'>✗ Could not verify pages table structure</div>";
        $allTablesExist = false;
    }
    
    echo "</div>";
    
    if ($allTablesExist) {
        echo "<div class='step'>";
        echo "<h3>🎉 Installation Complete!</h3>";
        echo "<div class='success'>";
        echo "<h4>✅ Editable Templates System has been installed successfully!</h4>";
        echo "<p><strong>What's New:</strong></p>";
        echo "<ul>";
        echo "<li>🎨 <strong>Database Templates:</strong> All page templates are now stored in the database and fully editable</li>";
        echo "<li>💻 <strong>CodeMirror Integration:</strong> Professional code editor with syntax highlighting, themes, and auto-completion</li>";
        echo "<li>🔧 <strong>Template Variables:</strong> Support for {{ variable|default }} syntax with automatic extraction</li>";
        echo "<li>📱 <strong>Responsive Preview:</strong> Live preview with desktop, tablet, and mobile views</li>";
        echo "<li>🎯 <strong>Custom Templates:</strong> Create unlimited page templates with HTML, CSS, and JavaScript</li>";
        echo "<li>⚡ <strong>Enhanced Performance:</strong> Direct template rendering without Blade file dependencies</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<h4>📚 Next Steps:</h4>";
        echo "<ol>";
        echo "<li><strong>Access Template Management:</strong> Go to <a href='/admin/page-templates'>/admin/page-templates</a></li>";
        echo "<li><strong>Create Custom Templates:</strong> Use the CodeMirror editor to create your own templates</li>";
        echo "<li><strong>Edit Existing Pages:</strong> Pages now use database templates automatically</li>";
        echo "<li><strong>Customize Variables:</strong> Use the variable extraction feature to manage template variables</li>";
        echo "</ol>";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<h4>🔗 Available Routes:</h4>";
        echo "<ul>";
        echo "<li><code>/admin/page-templates</code> - Template management dashboard</li>";
        echo "<li><code>/admin/page-templates/create</code> - Create new template with CodeMirror</li>";
        echo "<li><code>/admin/page-templates/edit?id=X</code> - Edit template</li>";
        echo "<li><code>/admin/page-templates/preview?id=X</code> - Preview template</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<h4>🎨 Template Features:</h4>";
        echo "<ul>";
        echo "<li><strong>Variable Syntax:</strong> <code>{{ variable|default_value }}</code></li>";
        echo "<li><strong>Conditional Blocks:</strong> <code>@if(variable)...@endif</code></li>";
        echo "<li><strong>Custom CSS:</strong> <code>{{ custom_css }}</code> placeholder</li>";
        echo "<li><strong>Custom JS:</strong> <code>{{ custom_js }}</code> placeholder</li>";
        echo "<li><strong>Sections Support:</strong> <code>{{ sections_html }}</code> for page sections</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "</div>";
    } else {
        echo "<div class='step'>";
        echo "<div class='error'>";
        echo "<h3>❌ Installation Issues Detected</h3>";
        echo "<p>Some components were not installed correctly. Please check the errors above and try running the installation again.</p>";
        echo "</div>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>💥 Installation Failed</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
    echo "</div>";
}

echo "<div class='step'>";
echo "<h3>📖 Documentation</h3>";
echo "<p>For detailed documentation on using the editable templates system, please refer to:</p>";
echo "<ul>";
echo "<li><strong>Template Variables:</strong> Use <code>{{ variable|default }}</code> syntax</li>";
echo "<li><strong>Conditional Rendering:</strong> Use <code>@if(variable)...@endif</code> blocks</li>";
echo "<li><strong>CodeMirror Shortcuts:</strong> F11 for fullscreen, Ctrl+Space for autocomplete</li>";
echo "<li><strong>Theme Selection:</strong> Choose from Default, Monokai, Material, and Dracula themes</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
?>

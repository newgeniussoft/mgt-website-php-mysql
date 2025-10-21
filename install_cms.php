<?php
/**
 * CMS Page Management System Installation Script
 * 
 * This script will create the pages tables and insert sample pages
 * Run this script after installing the authentication system
 */

require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    
    echo "<h2>Installing CMS Page Management System...</h2>\n";
    
    // Read and execute the SQL file
    $sql = file_get_contents(__DIR__ . '/database/pages.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $db->exec($statement);
                echo "✓ Executed: " . substr($statement, 0, 50) . "...\n<br>";
            } catch (PDOException $e) {
                // Skip if table already exists
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo "⚠️ Skipped (already exists): " . substr($statement, 0, 50) . "...\n<br>";
                } else {
                    throw $e;
                }
            }
        }
    }
    
    // Create uploads directory if it doesn't exist
    $uploadsDir = __DIR__ . '/uploads/pages';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
        echo "✓ Created uploads directory: /uploads/pages/\n<br>";
    }
    
    echo "<br><h3>✅ CMS Page Management System Installed Successfully!</h3>\n";
    echo "<p><strong>Features Installed:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>✅ Pages table with full CMS functionality</li>\n";
    echo "<li>✅ Page categories system</li>\n";
    echo "<li>✅ SEO-friendly URLs and meta tags</li>\n";
    echo "<li>✅ Template system support</li>\n";
    echo "<li>✅ Menu management</li>\n";
    echo "<li>✅ Featured images support</li>\n";
    echo "<li>✅ Sample pages (Home, About, Contact)</li>\n";
    echo "</ul>\n";
    
    echo "<p><strong>Available Routes:</strong></p>\n";
    echo "<ul>\n";
    echo "<li><code>/admin/pages</code> - Page management dashboard</li>\n";
    echo "<li><code>/admin/pages/create</code> - Create new page</li>\n";
    echo "<li><code>/admin/pages/edit?id=X</code> - Edit existing page</li>\n";
    echo "<li><code>/admin/pages/preview?id=X</code> - Preview page</li>\n";
    echo "</ul>\n";
    
    echo "<p><strong>Next Steps:</strong></p>\n";
    echo "<ol>\n";
    echo "<li>Visit <a href='/admin/pages' style='color: #3B82F6;'>/admin/pages</a> to manage your pages</li>\n";
    echo "<li>Create new pages or edit the sample pages</li>\n";
    echo "<li>Set up your homepage and menu structure</li>\n";
    echo "<li>Configure SEO settings for better search visibility</li>\n";
    echo "</ol>\n";
    
    echo "<p><a href='/admin/dashboard' style='background: #3B82F6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Go to Admin Dashboard</a>";
    echo "<a href='/admin/pages' style='background: #059669; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Manage Pages</a></p>\n";
    
} catch (Exception $e) {
    echo "<h3>❌ Installation Failed!</h3>\n";
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
    echo "<p>Please check:</p>\n";
    echo "<ul>\n";
    echo "<li>Database connection is working</li>\n";
    echo "<li>Authentication system is installed first</li>\n";
    echo "<li>Database user has CREATE and INSERT permissions</li>\n";
    echo "</ul>\n";
}
?>

<?php
/**
 * Quick test to check database connection and table existence
 */

require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    
    // Check if page_templates table exists
    $stmt = $db->query("SHOW TABLES LIKE 'page_templates'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ page_templates table exists</p>";
        
        // Check if there are any templates
        $stmt = $db->query("SELECT COUNT(*) as count FROM page_templates");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>📊 Templates in database: " . $result['count'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ page_templates table does not exist</p>";
        echo "<p><strong>Action needed:</strong> Run the installation script: <a href='install_editable_templates.php'>install_editable_templates.php</a></p>";
    }
    
    // Check if pages table has new columns
    $stmt = $db->query("SHOW COLUMNS FROM pages LIKE 'template_id'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ pages table has template_id column</p>";
    } else {
        echo "<p style='color: red;'>❌ pages table missing template_id column</p>";
        echo "<p><strong>Action needed:</strong> Run the installation script: <a href='install_editable_templates.php'>install_editable_templates.php</a></p>";
    }
    
    echo "<hr>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>If tables are missing, run: <a href='install_editable_templates.php'>install_editable_templates.php</a></li>";
    echo "<li>Then access: <a href='/admin/page-templates'>/admin/page-templates</a></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}
?>

<?php
/**
 * Installation script for Custom Layouts and Page Sections System
 * Run this script to add custom layout functionality to your CMS
 */

require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    
    echo "<h2>Installing Custom Layouts and Page Sections System</h2>";
    
    // Read and execute the migration SQL
    $sqlFile = 'database/migrate_custom_layouts.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("Migration file not found: " . $sqlFile);
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $db->beginTransaction();
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue; // Skip empty statements and comments
        }
        
        try {
            echo "Executing: " . substr($statement, 0, 50) . "...<br>";
            $db->exec($statement);
            echo "✓ Success<br><br>";
        } catch (PDOException $e) {
            // If it's an "already exists" error, that's okay
            if (strpos($e->getMessage(), 'already exists') !== false || 
                strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "⚠ Already exists (skipping)<br><br>";
            } else {
                throw $e;
            }
        }
    }
    
    $db->commit();
    
    echo "<h3>✅ Installation completed successfully!</h3>";
    echo "<p>The custom layouts and page sections system has been installed.</p>";
    echo "<p><a href='check_db_structure.php'>Check Database Structure</a></p>";
    echo "<p><a href='admin/pages'>Go to Page Management</a></p>";
    
} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    echo "<h3>❌ Installation failed!</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
}
?>

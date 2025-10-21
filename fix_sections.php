<?php
/**
 * Quick fix script to ensure use_sections column exists
 */

require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    
    echo "<h2>Fixing Page Sections Support</h2>";
    
    // Check if use_sections column exists
    $stmt = $db->query("SHOW COLUMNS FROM pages LIKE 'use_sections'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        echo "Adding use_sections column to pages table...<br>";
        $db->exec("ALTER TABLE pages ADD COLUMN use_sections tinyint(1) DEFAULT 0 AFTER template");
        echo "✅ use_sections column added successfully!<br>";
    } else {
        echo "✅ use_sections column already exists<br>";
    }
    
    // Check if layout_id column exists
    $stmt = $db->query("SHOW COLUMNS FROM pages LIKE 'layout_id'");
    $layoutColumnExists = $stmt->rowCount() > 0;
    
    if (!$layoutColumnExists) {
        echo "Adding layout_id column to pages table...<br>";
        $db->exec("ALTER TABLE pages ADD COLUMN layout_id int(11) DEFAULT NULL AFTER template");
        echo "✅ layout_id column added successfully!<br>";
    } else {
        echo "✅ layout_id column already exists<br>";
    }
    
    // Check if page_sections table exists
    try {
        $stmt = $db->query("SELECT 1 FROM page_sections LIMIT 1");
        echo "✅ page_sections table exists<br>";
    } catch (Exception $e) {
        echo "Creating page_sections table...<br>";
        $createTableSQL = "
        CREATE TABLE page_sections (
            id int(11) NOT NULL AUTO_INCREMENT,
            page_id int(11) NOT NULL,
            section_type varchar(50) NOT NULL,
            title varchar(255),
            content longtext,
            section_html longtext,
            section_css longtext,
            section_js longtext,
            settings json,
            sort_order int(11) DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_page_id (page_id),
            KEY idx_sort_order (sort_order),
            KEY idx_section_type (section_type),
            FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($createTableSQL);
        echo "✅ page_sections table created successfully!<br>";
    }
    
    echo "<h3>🎉 All fixes applied successfully!</h3>";
    echo "<p><a href='check_db_structure.php'>Check Database Structure</a></p>";
    echo "<p><a href='admin/pages'>Go to Page Management</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Fix failed!</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

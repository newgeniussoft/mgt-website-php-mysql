<?php
require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    
    echo "<h2>Database Structure Check</h2>";
    
    // Check if use_sections column exists in pages table
    echo "<h3>Pages Table Structure:</h3>";
    $stmt = $db->query("DESCRIBE pages");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $useSectionsExists = false;
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
        
        if ($column['Field'] === 'use_sections') {
            $useSectionsExists = true;
        }
    }
    echo "</table>";
    
    echo "<h3>use_sections column exists: " . ($useSectionsExists ? "YES" : "NO") . "</h3>";
    
    // Check if page_sections table exists
    echo "<h3>Page Sections Table Check:</h3>";
    try {
        $stmt = $db->query("SELECT COUNT(*) as count FROM page_sections");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "page_sections table exists with " . $result['count'] . " records<br>";
    } catch (Exception $e) {
        echo "page_sections table does NOT exist: " . $e->getMessage() . "<br>";
    }
    
    // Check sample page data
    echo "<h3>Sample Page Data:</h3>";
    $stmt = $db->query("SELECT id, title, use_sections FROM pages LIMIT 5");
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Title</th><th>use_sections</th></tr>";
    foreach ($pages as $page) {
        echo "<tr>";
        echo "<td>" . $page['id'] . "</td>";
        echo "<td>" . $page['title'] . "</td>";
        echo "<td>" . ($page['use_sections'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

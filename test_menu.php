<?php
/**
 * Test script to verify menu functionality
 */

require_once 'config/database.php';
require_once 'app/models/Page.php';

try {
    $pageModel = new Page();
    
    echo "<h2>Menu Pages Test</h2>";
    
    // Test English menu
    echo "<h3>English Menu:</h3>";
    $menuPages = $pageModel->getMenuPages('en');
    
    echo "<p><strong>Total menu items: " . count($menuPages) . "</strong></p>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Display Title</th><th>Original Title</th><th>Slug</th><th>Is Homepage</th><th>Menu Order</th><th>Menu URL</th></tr>";
    
    // Get original titles for comparison
    $db = Database::getInstance()->getConn();
    $originalTitles = [];
    $stmt = $db->query("SELECT id, title FROM pages");
    while ($row = $stmt->fetch()) {
        $originalTitles[$row['id']] = $row['title'];
    }
    
    foreach ($menuPages as $page) {
        $menuUrl = $page['is_homepage'] ? '/' : '/' . $page['slug'];
        $originalTitle = $originalTitles[$page['id']] ?? 'Unknown';
        echo "<tr>";
        echo "<td>" . $page['id'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($page['title']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($originalTitle) . "</td>";
        echo "<td>" . htmlspecialchars($page['slug']) . "</td>";
        echo "<td>" . ($page['is_homepage'] ? '<span style="color: green;">YES</span>' : 'NO') . "</td>";
        echo "<td>" . $page['menu_order'] . "</td>";
        echo "<td><a href='" . $menuUrl . "' target='_blank'>" . $menuUrl . "</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check for duplicates
    $ids = array_column($menuPages, 'id');
    $duplicateIds = array_diff_assoc($ids, array_unique($ids));
    if (!empty($duplicateIds)) {
        echo "<p style='color: red;'><strong>⚠️ Duplicate IDs found: " . implode(', ', $duplicateIds) . "</strong></p>";
    } else {
        echo "<p style='color: green;'><strong>✅ No duplicates found!</strong></p>";
    }
    
    // Test Spanish menu
    echo "<h3>Spanish Menu:</h3>";
    $menuPagesEs = $pageModel->getMenuPages('es');
    
    if (empty($menuPagesEs)) {
        echo "<p>No Spanish pages found.</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Title</th><th>Slug</th><th>Is Homepage</th><th>Menu Order</th><th>Show in Menu</th><th>Menu URL</th></tr>";
        
        foreach ($menuPagesEs as $page) {
            $menuUrl = $page['is_homepage'] ? '/' : '/' . $page['slug'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($page['title']) . "</td>";
            echo "<td>" . htmlspecialchars($page['slug']) . "</td>";
            echo "<td>" . ($page['is_homepage'] ? 'YES' : 'NO') . "</td>";
            echo "<td>" . $page['menu_order'] . "</td>";
            echo "<td>" . (isset($page['show_in_menu']) ? ($page['show_in_menu'] ? 'YES' : 'NO') : 'N/A') . "</td>";
            echo "<td><a href='" . $menuUrl . "' target='_blank'>" . $menuUrl . "</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Show all pages for reference
    echo "<h3>All Published Pages:</h3>";
    $allPages = $pageModel->getAll(['status' => 'published'], 1, 50);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Language</th><th>Is Homepage</th><th>Show in Menu</th><th>Menu Order</th></tr>";
    
    foreach ($allPages as $page) {
        echo "<tr>";
        echo "<td>" . $page['id'] . "</td>";
        echo "<td>" . htmlspecialchars($page['title']) . "</td>";
        echo "<td>" . htmlspecialchars($page['slug']) . "</td>";
        echo "<td>" . ($page['language'] ?? 'en') . "</td>";
        echo "<td>" . ($page['is_homepage'] ? 'YES' : 'NO') . "</td>";
        echo "<td>" . ($page['show_in_menu'] ? 'YES' : 'NO') . "</td>";
        echo "<td>" . $page['menu_order'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error!</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
table { border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f0f0f0; }
</style>

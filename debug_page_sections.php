<?php
/**
 * Debug script to check page sections data
 */

require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    
    echo "<h2>Page Sections Debug</h2>";
    
    // Get page ID from URL parameter
    $pageId = $_GET['page_id'] ?? null;
    
    if (!$pageId) {
        echo "<p>Please provide a page_id parameter: ?page_id=1</p>";
        
        // Show all pages with their section status
        echo "<h3>All Pages:</h3>";
        $stmt = $db->query("SELECT id, title, use_sections, status FROM pages ORDER BY id DESC LIMIT 10");
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Title</th><th>use_sections</th><th>Status</th><th>Debug Link</th></tr>";
        foreach ($pages as $page) {
            echo "<tr>";
            echo "<td>" . $page['id'] . "</td>";
            echo "<td>" . htmlspecialchars($page['title']) . "</td>";
            echo "<td>" . ($page['use_sections'] ? 'YES' : 'NO') . "</td>";
            echo "<td>" . $page['status'] . "</td>";
            echo "<td><a href='?page_id=" . $page['id'] . "'>Debug</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        exit;
    }
    
    // Get specific page data
    echo "<h3>Page Data for ID: $pageId</h3>";
    $stmt = $db->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->execute([$pageId]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$page) {
        echo "Page not found!";
        exit;
    }
    
    echo "<table border='1'>";
    foreach ($page as $key => $value) {
        echo "<tr><td><strong>$key</strong></td><td>" . htmlspecialchars($value ?? 'NULL') . "</td></tr>";
    }
    echo "</table>";
    
    // Get sections for this page
    echo "<h3>Sections for this page:</h3>";
    $stmt = $db->prepare("SELECT * FROM page_sections WHERE page_id = ? ORDER BY sort_order");
    $stmt->execute([$pageId]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($sections)) {
        echo "<p>No sections found for this page.</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Type</th><th>Title</th><th>Content (first 100 chars)</th><th>Active</th><th>Sort Order</th></tr>";
        foreach ($sections as $section) {
            echo "<tr>";
            echo "<td>" . $section['id'] . "</td>";
            echo "<td>" . $section['section_type'] . "</td>";
            echo "<td>" . htmlspecialchars($section['title'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars(substr($section['content'] ?? '', 0, 100)) . "</td>";
            echo "<td>" . ($section['is_active'] ? 'YES' : 'NO') . "</td>";
            echo "<td>" . $section['sort_order'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test frontend URL
    echo "<h3>Frontend Test:</h3>";
    echo "<p><a href='/" . $page['slug'] . "' target='_blank'>View page on frontend</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error!</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

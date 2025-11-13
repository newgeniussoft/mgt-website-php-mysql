<?php
/**
 * Test Page Rendering
 * This file helps debug page rendering issues
 */

require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Page;
use App\Models\Template;
use App\Models\Section;

echo "<h1>Page Rendering Debug</h1>";
echo "<style>body { font-family: Arial; padding: 20px; } table { border-collapse: collapse; width: 100%; margin: 20px 0; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background: #4CAF50; color: white; } pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }</style>";

// Test 1: Check if pages exist
echo "<h2>1. Pages in Database</h2>";
$pages = Page::all();
if (empty($pages)) {
    echo "<p style='color: red;'>❌ No pages found in database!</p>";
    echo "<p>Please create a page in the admin panel: <a href='/admin/pages/create'>/admin/pages/create</a></p>";
} else {
    echo "<p style='color: green;'>✅ Found " . count($pages) . " page(s)</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Status</th><th>Template ID</th><th>Homepage</th><th>In Menu</th></tr>";
    foreach ($pages as $page) {
        echo "<tr>";
        echo "<td>{$page->id}</td>";
        echo "<td>{$page->title}</td>";
        echo "<td>{$page->slug}</td>";
        echo "<td>{$page->status}</td>";
        echo "<td>{$page->template_id}</td>";
        echo "<td>" . ($page->is_homepage ? 'Yes' : 'No') . "</td>";
        echo "<td>" . ($page->show_in_menu ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 2: Check templates
echo "<h2>2. Templates in Database</h2>";
$templates = Template::all();
if (empty($templates)) {
    echo "<p style='color: orange;'>⚠️ No templates found in database!</p>";
    echo "<p>Pages will use the default template. You can create templates at: <a href='/admin/templates/create'>/admin/templates/create</a></p>";
} else {
    echo "<p style='color: green;'>✅ Found " . count($templates) . " template(s)</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Slug</th><th>Status</th><th>Has HTML</th><th>Has CSS</th><th>Has JS</th></tr>";
    foreach ($templates as $template) {
        echo "<tr>";
        echo "<td>{$template->id}</td>";
        echo "<td>{$template->name}</td>";
        echo "<td>{$template->slug}</td>";
        echo "<td>{$template->status}</td>";
        echo "<td>" . (!empty($template->html_content) ? 'Yes' : 'No') . "</td>";
        echo "<td>" . (!empty($template->css_content) ? 'Yes' : 'No') . "</td>";
        echo "<td>" . (!empty($template->js_content) ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 3: Check sections
echo "<h2>3. Sections in Database</h2>";
$sections = Section::all();
if (empty($sections)) {
    echo "<p style='color: orange;'>⚠️ No sections found in database!</p>";
    echo "<p>Pages will display without sections. You can add sections to pages in the admin panel.</p>";
} else {
    echo "<p style='color: green;'>✅ Found " . count($sections) . " section(s)</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Page ID</th><th>Name</th><th>Type</th><th>Active</th><th>Has HTML</th><th>Has CSS</th></tr>";
    foreach ($sections as $section) {
        echo "<tr>";
        echo "<td>{$section->id}</td>";
        echo "<td>{$section->page_id}</td>";
        echo "<td>{$section->name}</td>";
        echo "<td>{$section->type}</td>";
        echo "<td>" . ($section->is_active ? 'Yes' : 'No') . "</td>";
        echo "<td>" . (!empty($section->html_template) ? 'Yes' : 'No') . "</td>";
        echo "<td>" . (!empty($section->css_styles) ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 4: Test homepage
echo "<h2>4. Homepage Test</h2>";
$homepage = Page::getHomepage();
if (!$homepage) {
    echo "<p style='color: orange;'>⚠️ No homepage set!</p>";
    echo "<p>Please edit a page and check 'Set as Homepage'</p>";
} else {
    echo "<p style='color: green;'>✅ Homepage found: <strong>{$homepage->title}</strong></p>";
    echo "<p>Slug: {$homepage->slug}</p>";
    echo "<p>Status: {$homepage->status}</p>";
    echo "<p><a href='/' target='_blank'>View Homepage</a></p>";
}

// Test 5: Test menu pages
echo "<h2>5. Menu Pages Test</h2>";
$menuPages = Page::getMenuPages();
if (empty($menuPages)) {
    echo "<p style='color: orange;'>⚠️ No menu pages found!</p>";
    echo "<p>Check 'Show in Menu' when editing pages to add them to navigation.</p>";
} else {
    echo "<p style='color: green;'>✅ Found " . count($menuPages) . " menu page(s)</p>";
    echo "<ul>";
    foreach ($menuPages as $menuPage) {
        $url = $menuPage->is_homepage ? '/' : '/' . $menuPage->slug;
        echo "<li><a href='{$url}' target='_blank'>{$menuPage->title}</a> (Order: {$menuPage->menu_order})</li>";
    }
    echo "</ul>";
}

// Test 6: Test a specific page
if (!empty($pages)) {
    $testPage = $pages[0];
    echo "<h2>6. Test Page Rendering: {$testPage->title}</h2>";
    
    $testUrl = $testPage->is_homepage ? '/' : '/' . $testPage->slug;
    echo "<p><a href='{$testUrl}' target='_blank'>View Page: {$testPage->title}</a></p>";
    
    // Check template
    if ($testPage->template_id) {
        $testTemplate = Template::find($testPage->template_id);
        if ($testTemplate) {
            echo "<p>✅ Template assigned: {$testTemplate->name}</p>";
            if (empty($testTemplate->html_content)) {
                echo "<p style='color: red;'>❌ Template has no HTML content!</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Template not found (ID: {$testPage->template_id})</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ No template assigned (will use default)</p>";
    }
    
    // Check sections
    $testSections = Section::getByPage($testPage->id, true);
    if (empty($testSections)) {
        echo "<p style='color: orange;'>⚠️ No sections found for this page</p>";
    } else {
        echo "<p>✅ Found " . count($testSections) . " section(s)</p>";
    }
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p>If pages are showing blank:</p>";
echo "<ol>";
echo "<li>Make sure page status is 'published'</li>";
echo "<li>If using a template, make sure it has HTML content</li>";
echo "<li>Check browser console for JavaScript errors</li>";
echo "<li>Check if sections are active and have content</li>";
echo "</ol>";

echo "<p><a href='/admin/pages'>Go to Admin Panel</a></p>";

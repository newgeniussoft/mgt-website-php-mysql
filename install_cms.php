<?php
/**
 * CMS Installation Script
 * Installs the complete Page Management System with Templates, Sections, and Content
 */

require_once __DIR__ . '/bootstrap/app.php';

// Database connection
try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']}",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

echo "=== CMS Installation Script ===\n\n";

// Read and execute migration file
$migrationFile = __DIR__ . '/database/migrations/005_create_cms_tables.sql';

if (!file_exists($migrationFile)) {
    die("Error: Migration file not found at $migrationFile\n");
}

echo "Reading migration file...\n";
$sql = file_get_contents($migrationFile);

// Split SQL statements
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function($stmt) {
        return !empty($stmt) && !preg_match('/^--/', $stmt);
    }
);

echo "Executing " . count($statements) . " SQL statements...\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    try {
        $pdo->exec($statement);
        
        // Extract table name for better feedback
        if (preg_match('/CREATE TABLE.*?`(\w+)`/i', $statement, $matches)) {
            echo "✓ Created table: {$matches[1]}\n";
        } elseif (preg_match('/INSERT INTO.*?`(\w+)`/i', $statement, $matches)) {
            echo "✓ Inserted data into: {$matches[1]}\n";
        } else {
            echo "✓ Executed statement\n";
        }
        
        $successCount++;
    } catch (PDOException $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n=== Installation Summary ===\n";
echo "Successful: $successCount\n";
echo "Errors: $errorCount\n";

// Create upload directories
echo "\n=== Creating Upload Directories ===\n";

$directories = [
    __DIR__ . '/public/uploads/pages',
    __DIR__ . '/public/uploads/templates',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created directory: $dir\n";
        } else {
            echo "✗ Failed to create directory: $dir\n";
        }
    } else {
        echo "✓ Directory already exists: $dir\n";
    }
}

echo "\n=== Installation Complete ===\n";
echo "\nCMS Features Installed:\n";
echo "- Pages Management (CRUD)\n";
echo "- Template Management with Monaco Editor\n";
echo "- Section Management with Monaco Editor\n";
echo "- Content Management with Summernote\n";
echo "\nAccess the CMS at: " . $_ENV['APP_URL'] . "/" . $_ENV['APP_ADMIN_PREFIX'] . "/pages\n";
echo "\nStructure: Page → Template → Section → Content\n";
echo "\n";

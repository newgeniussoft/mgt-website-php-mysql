<?php

// Migration runner script
require_once __DIR__ . '/../bootstrap/app.php';

echo "Running database migrations...\n";

try {
    // Get all migration files
    $migrationFiles = glob(__DIR__ . '/migrations/*.sql');
    
    foreach ($migrationFiles as $file) {
        echo "Running migration: " . basename($file) . "\n";
        
        $sql = file_get_contents($file);
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        echo "Migration completed: " . basename($file) . "\n";
    }
    
    echo "All migrations completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

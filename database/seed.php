<?php

// Seeder runner script
require_once __DIR__ . '/../bootstrap/app.php';

echo "Running database seeders...\n";

try {
    // Run UserSeeder
    echo "Running UserSeeder...\n";
    \Database\Seeds\UserSeeder::run();
    
    // Run PostSeeder
    echo "Running PostSeeder...\n";
    \Database\Seeds\PostSeeder::run();
    
    echo "All seeders completed successfully!\n";
    
} catch (Exception $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}

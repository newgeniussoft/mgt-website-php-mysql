<?php
/**
 * Authentication System Installation Script
 * 
 * This script will create the users table and insert a default admin user
 * Run this script once to set up the authentication system
 */

require_once 'config/database.php';

try {
    $db = Database::getInstance()->getConn();
    
    echo "<h2>Installing Authentication System...</h2>\n";
    
    // Read and execute the SQL file
    $sql = file_get_contents(__DIR__ . '/database/users.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            $db->exec($statement);
            echo "✓ Executed: " . substr($statement, 0, 50) . "...\n<br>";
        }
    }
    
    echo "<br><h3>✅ Authentication System Installed Successfully!</h3>\n";
    echo "<p><strong>Default Admin Credentials:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>Email: admin@example.com</li>\n";
    echo "<li>Password: admin123</li>\n";
    echo "</ul>\n";
    echo "<p><strong style='color: red;'>⚠️ IMPORTANT: Please change the default password immediately after first login!</strong></p>\n";
    echo "<p><a href='/admin/login' style='background: #3B82F6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a></p>\n";
    
} catch (Exception $e) {
    echo "<h3>❌ Installation Failed!</h3>\n";
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
    echo "<p>Please check your database configuration in config/database.php</p>\n";
}
?>

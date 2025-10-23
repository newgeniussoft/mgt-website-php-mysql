<?php
/**
 * Media Management System Installation Script
 * 
 * This script sets up the media management system for the CMS.
 * It creates database tables, directories, and sample data.
 */

// Include database configuration
require_once 'app/core/Database.php';

// Start output buffering for clean display
ob_start();

echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Media Management System Installation</title>\n";
echo "    <script src='https://cdn.tailwindcss.com'></script>\n";
echo "    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>\n";
echo "</head>\n";
echo "<body class='bg-gray-100'>\n";
echo "    <div class='container mx-auto px-4 py-8'>\n";
echo "        <div class='max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden'>\n";
echo "            <div class='bg-blue-600 text-white p-6'>\n";
echo "                <h1 class='text-3xl font-bold'><i class='fas fa-images mr-3'></i>Media Management System Installation</h1>\n";
echo "                <p class='mt-2 opacity-90'>Setting up media library and file management features</p>\n";
echo "            </div>\n";
echo "            <div class='p-6'>\n";

$errors = [];
$success = [];

try {
    // Get database connection
    $db = Database::getInstance()->getConnection();
    
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-database text-blue-600 mr-2'></i>Database Setup</h2>\n";
    
    // Read and execute migration SQL
    $migrationFile = __DIR__ . '/migrate_media.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }
    
    $sql = file_get_contents($migrationFile);
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $db->exec($statement);
                
                // Extract table name for display
                if (preg_match('/CREATE TABLE.*?`([^`]+)`/i', $statement, $matches)) {
                    echo "    <div class='flex items-center text-green-600 mb-2'>\n";
                    echo "        <i class='fas fa-check-circle mr-2'></i>\n";
                    echo "        <span>Created table: {$matches[1]}</span>\n";
                    echo "    </div>\n";
                } elseif (preg_match('/INSERT INTO.*?`([^`]+)`/i', $statement, $matches)) {
                    echo "    <div class='flex items-center text-blue-600 mb-2'>\n";
                    echo "        <i class='fas fa-plus-circle mr-2'></i>\n";
                    echo "        <span>Inserted sample data into: {$matches[1]}</span>\n";
                    echo "    </div>\n";
                } elseif (preg_match('/ALTER TABLE.*?`([^`]+)`/i', $statement, $matches)) {
                    echo "    <div class='flex items-center text-purple-600 mb-2'>\n";
                    echo "        <i class='fas fa-edit mr-2'></i>\n";
                    echo "        <span>Modified table: {$matches[1]}</span>\n";
                    echo "    </div>\n";
                }
            } catch (PDOException $e) {
                // Check if error is about table already existing
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    if (preg_match('/CREATE TABLE.*?`([^`]+)`/i', $statement, $matches)) {
                        echo "    <div class='flex items-center text-yellow-600 mb-2'>\n";
                        echo "        <i class='fas fa-exclamation-triangle mr-2'></i>\n";
                        echo "        <span>Table already exists: {$matches[1]}</span>\n";
                        echo "    </div>\n";
                    }
                } else {
                    throw $e;
                }
            }
        }
    }
    
    $success[] = "Database tables created successfully";
    echo "</div>\n";
    
    // Create upload directories
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-folder text-green-600 mr-2'></i>Directory Setup</h2>\n";
    
    $directories = [
        'uploads',
        'uploads/images',
        'uploads/videos', 
        'uploads/audios',
        'uploads/documents',
        'uploads/others',
        'uploads/thumbnails'
    ];
    
    foreach ($directories as $dir) {
        $fullPath = __DIR__ . '/' . $dir;
        
        if (!is_dir($fullPath)) {
            if (mkdir($fullPath, 0755, true)) {
                echo "    <div class='flex items-center text-green-600 mb-2'>\n";
                echo "        <i class='fas fa-check-circle mr-2'></i>\n";
                echo "        <span>Created directory: $dir</span>\n";
                echo "    </div>\n";
            } else {
                echo "    <div class='flex items-center text-red-600 mb-2'>\n";
                echo "        <i class='fas fa-times-circle mr-2'></i>\n";
                echo "        <span>Failed to create directory: $dir</span>\n";
                echo "    </div>\n";
                $errors[] = "Failed to create directory: $dir";
            }
        } else {
            echo "    <div class='flex items-center text-yellow-600 mb-2'>\n";
            echo "        <i class='fas fa-folder mr-2'></i>\n";
            echo "        <span>Directory already exists: $dir</span>\n";
            echo "    </div>\n";
        }
        
        // Check permissions
        if (is_dir($fullPath)) {
            if (is_writable($fullPath)) {
                echo "    <div class='flex items-center text-green-600 mb-2 ml-6'>\n";
                echo "        <i class='fas fa-lock-open mr-2'></i>\n";
                echo "        <span>Directory is writable</span>\n";
                echo "    </div>\n";
            } else {
                echo "    <div class='flex items-center text-red-600 mb-2 ml-6'>\n";
                echo "        <i class='fas fa-lock mr-2'></i>\n";
                echo "        <span>Directory is not writable - please check permissions</span>\n";
                echo "    </div>\n";
                $errors[] = "Directory not writable: $dir";
            }
        }
    }
    
    $success[] = "Upload directories created successfully";
    echo "</div>\n";
    
    // Create .htaccess file for uploads directory
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-shield-alt text-purple-600 mr-2'></i>Security Setup</h2>\n";
    
    $htaccessContent = "# Protect uploads directory\n";
    $htaccessContent .= "Options -Indexes\n";
    $htaccessContent .= "Options -ExecCGI\n";
    $htaccessContent .= "\n";
    $htaccessContent .= "# Allow only specific file types\n";
    $htaccessContent .= "<FilesMatch \"\\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|sh|cgi)$\">\n";
    $htaccessContent .= "    Order Allow,Deny\n";
    $htaccessContent .= "    Deny from all\n";
    $htaccessContent .= "</FilesMatch>\n";
    $htaccessContent .= "\n";
    $htaccessContent .= "# Set proper MIME types\n";
    $htaccessContent .= "<IfModule mod_mime.c>\n";
    $htaccessContent .= "    AddType image/jpeg .jpg .jpeg\n";
    $htaccessContent .= "    AddType image/png .png\n";
    $htaccessContent .= "    AddType image/gif .gif\n";
    $htaccessContent .= "    AddType image/webp .webp\n";
    $htaccessContent .= "    AddType application/pdf .pdf\n";
    $htaccessContent .= "</IfModule>\n";
    
    $htaccessFile = __DIR__ . '/uploads/.htaccess';
    
    if (file_put_contents($htaccessFile, $htaccessContent)) {
        echo "    <div class='flex items-center text-green-600 mb-2'>\n";
        echo "        <i class='fas fa-check-circle mr-2'></i>\n";
        echo "        <span>Created security configuration (.htaccess)</span>\n";
        echo "    </div>\n";
    } else {
        echo "    <div class='flex items-center text-red-600 mb-2'>\n";
        echo "        <i class='fas fa-times-circle mr-2'></i>\n";
        echo "        <span>Failed to create .htaccess file</span>\n";
        echo "    </div>\n";
        $errors[] = "Failed to create .htaccess file";
    }
    
    $success[] = "Security configuration applied";
    echo "</div>\n";
    
    // Check PHP extensions
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-cogs text-orange-600 mr-2'></i>PHP Extensions Check</h2>\n";
    
    $requiredExtensions = [
        'gd' => 'Image processing and thumbnail generation',
        'fileinfo' => 'File type detection',
        'mbstring' => 'String handling for file names'
    ];
    
    foreach ($requiredExtensions as $extension => $description) {
        if (extension_loaded($extension)) {
            echo "    <div class='flex items-center text-green-600 mb-2'>\n";
            echo "        <i class='fas fa-check-circle mr-2'></i>\n";
            echo "        <span>$extension extension loaded - $description</span>\n";
            echo "    </div>\n";
        } else {
            echo "    <div class='flex items-center text-red-600 mb-2'>\n";
            echo "        <i class='fas fa-times-circle mr-2'></i>\n";
            echo "        <span>$extension extension missing - $description</span>\n";
            echo "    </div>\n";
            $errors[] = "Missing PHP extension: $extension";
        }
    }
    
    echo "</div>\n";
    
    // Installation summary
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-clipboard-check text-indigo-600 mr-2'></i>Installation Summary</h2>\n";
    
    if (empty($errors)) {
        echo "    <div class='bg-green-50 border border-green-200 rounded-lg p-4'>\n";
        echo "        <div class='flex items-center'>\n";
        echo "            <i class='fas fa-check-circle text-green-600 text-2xl mr-3'></i>\n";
        echo "            <div>\n";
        echo "                <h3 class='text-lg font-medium text-green-800'>Installation Completed Successfully!</h3>\n";
        echo "                <p class='text-green-700 mt-1'>The media management system has been installed and configured.</p>\n";
        echo "            </div>\n";
        echo "        </div>\n";
        echo "        <div class='mt-4'>\n";
        echo "            <h4 class='font-medium text-green-800 mb-2'>What was installed:</h4>\n";
        echo "            <ul class='list-disc list-inside text-green-700 space-y-1'>\n";
        foreach ($success as $item) {
            echo "                <li>$item</li>\n";
        }
        echo "            </ul>\n";
        echo "        </div>\n";
        echo "    </div>\n";
    } else {
        echo "    <div class='bg-red-50 border border-red-200 rounded-lg p-4'>\n";
        echo "        <div class='flex items-center'>\n";
        echo "            <i class='fas fa-exclamation-triangle text-red-600 text-2xl mr-3'></i>\n";
        echo "            <div>\n";
        echo "                <h3 class='text-lg font-medium text-red-800'>Installation Completed with Errors</h3>\n";
        echo "                <p class='text-red-700 mt-1'>Some issues were encountered during installation.</p>\n";
        echo "            </div>\n";
        echo "        </div>\n";
        echo "        <div class='mt-4'>\n";
        echo "            <h4 class='font-medium text-red-800 mb-2'>Errors encountered:</h4>\n";
        echo "            <ul class='list-disc list-inside text-red-700 space-y-1'>\n";
        foreach ($errors as $error) {
            echo "                <li>$error</li>\n";
        }
        echo "            </ul>\n";
        echo "        </div>\n";
        echo "    </div>\n";
    }
    
    echo "</div>\n";
    
    // Next steps
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-arrow-right text-blue-600 mr-2'></i>Next Steps</h2>\n";
    echo "    <div class='bg-blue-50 border border-blue-200 rounded-lg p-4'>\n";
    echo "        <ol class='list-decimal list-inside text-blue-800 space-y-2'>\n";
    echo "            <li>Access the admin panel at <a href='/admin' class='underline font-medium'>/admin</a></li>\n";
    echo "            <li>Navigate to the Media section in the sidebar</li>\n";
    echo "            <li>Start uploading your media files</li>\n";
    echo "            <li>Organize files using folders</li>\n";
    echo "            <li>Use the media picker in pages and sections</li>\n";
    echo "        </ol>\n";
    echo "    </div>\n";
    echo "</div>\n";
    
    // Features overview
    echo "<div class='mb-6'>\n";
    echo "    <h2 class='text-xl font-semibold text-gray-800 mb-4'><i class='fas fa-star text-yellow-600 mr-2'></i>Media Management Features</h2>\n";
    echo "    <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>\n";
    echo "        <div class='bg-gray-50 rounded-lg p-4'>\n";
    echo "            <h4 class='font-medium text-gray-800 mb-2'><i class='fas fa-upload text-green-600 mr-2'></i>File Upload</h4>\n";
    echo "            <ul class='text-sm text-gray-600 space-y-1'>\n";
    echo "                <li>• Drag and drop interface</li>\n";
    echo "                <li>• Multiple file upload</li>\n";
    echo "                <li>• File type validation</li>\n";
    echo "                <li>• Automatic thumbnail generation</li>\n";
    echo "            </ul>\n";
    echo "        </div>\n";
    echo "        <div class='bg-gray-50 rounded-lg p-4'>\n";
    echo "            <h4 class='font-medium text-gray-800 mb-2'><i class='fas fa-folder text-blue-600 mr-2'></i>Organization</h4>\n";
    echo "            <ul class='text-sm text-gray-600 space-y-1'>\n";
    echo "                <li>• Folder management</li>\n";
    echo "                <li>• File categorization</li>\n";
    echo "                <li>• Search and filtering</li>\n";
    echo "                <li>• Bulk operations</li>\n";
    echo "            </ul>\n";
    echo "        </div>\n";
    echo "        <div class='bg-gray-50 rounded-lg p-4'>\n";
    echo "            <h4 class='font-medium text-gray-800 mb-2'><i class='fas fa-edit text-purple-600 mr-2'></i>Management</h4>\n";
    echo "            <ul class='text-sm text-gray-600 space-y-1'>\n";
    echo "                <li>• File information editing</li>\n";
    echo "                <li>• Alt text for accessibility</li>\n";
    echo "                <li>• Privacy controls</li>\n";
    echo "                <li>• Download tracking</li>\n";
    echo "            </ul>\n";
    echo "        </div>\n";
    echo "        <div class='bg-gray-50 rounded-lg p-4'>\n";
    echo "            <h4 class='font-medium text-gray-800 mb-2'><i class='fas fa-mouse-pointer text-orange-600 mr-2'></i>Integration</h4>\n";
    echo "            <ul class='text-sm text-gray-600 space-y-1'>\n";
    echo "                <li>• Media picker modal</li>\n";
    echo "                <li>• Page integration</li>\n";
    echo "                <li>• Section integration</li>\n";
    echo "                <li>• Direct URL access</li>\n";
    echo "            </ul>\n";
    echo "        </div>\n";
    echo "    </div>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<div class='bg-red-50 border border-red-200 rounded-lg p-4'>\n";
    echo "    <div class='flex items-center'>\n";
    echo "        <i class='fas fa-times-circle text-red-600 text-2xl mr-3'></i>\n";
    echo "        <div>\n";
    echo "            <h3 class='text-lg font-medium text-red-800'>Installation Failed</h3>\n";
    echo "            <p class='text-red-700 mt-1'>An error occurred during installation:</p>\n";
    echo "            <p class='text-red-600 mt-2 font-mono text-sm bg-red-100 p-2 rounded'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "        </div>\n";
    echo "    </div>\n";
    echo "</div>\n";
}

// Action buttons
echo "<div class='flex justify-center space-x-4'>\n";
echo "    <a href='/admin' class='bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center'>\n";
echo "        <i class='fas fa-tachometer-alt mr-2'></i>\n";
echo "        Go to Admin Panel\n";
echo "    </a>\n";
echo "    <a href='/admin/media' class='bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg inline-flex items-center'>\n";
echo "        <i class='fas fa-images mr-2'></i>\n";
echo "        Open Media Library\n";
echo "    </a>\n";
echo "</div>\n";

echo "            </div>\n";
echo "        </div>\n";
echo "    </div>\n";
echo "</body>\n";
echo "</html>\n";

// Flush output buffer
ob_end_flush();
?>

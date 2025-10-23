<?php
/**
 * Debug Upload Issues
 */

// Start session and enable error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Upload Debug Information</h2>";

// Check PHP upload settings
echo "<h3>PHP Upload Configuration:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br><br>";

// Check directory permissions
echo "<h3>Directory Permissions:</h3>";
$dirs = ['uploads', 'uploads/images', 'uploads/videos', 'uploads/audios', 'uploads/documents', 'uploads/others', 'uploads/thumbnails'];

foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        echo "$dir: ";
        echo "Exists ✓ ";
        echo (is_readable($path) ? "Readable ✓ " : "Not Readable ✗ ");
        echo (is_writable($path) ? "Writable ✓" : "Not Writable ✗");
        echo " (Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . ")";
        echo "<br>";
    } else {
        echo "$dir: Does not exist ✗<br>";
    }
}

// Check if this is a POST request (file upload attempt)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Upload Attempt Debug:</h3>";
    
    echo "<h4>POST Data:</h4>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h4>FILES Data:</h4>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    if (isset($_FILES['files'])) {
        echo "<h4>File Upload Errors:</h4>";
        $files = $_FILES['files'];
        
        if (is_array($files['error'])) {
            foreach ($files['error'] as $i => $error) {
                echo "File $i ({$files['name'][$i]}): ";
                switch ($error) {
                    case UPLOAD_ERR_OK:
                        echo "No error ✓";
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                        echo "File too large (exceeds upload_max_filesize)";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        echo "File too large (exceeds MAX_FILE_SIZE)";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        echo "File partially uploaded";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        echo "No file uploaded";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        echo "Missing temporary folder";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        echo "Failed to write file to disk";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        echo "Upload stopped by extension";
                        break;
                    default:
                        echo "Unknown error ($error)";
                }
                echo "<br>";
            }
        } else {
            echo "Single file error: ";
            switch ($files['error']) {
                case UPLOAD_ERR_OK:
                    echo "No error ✓";
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    echo "File too large (exceeds upload_max_filesize)";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    echo "File too large (exceeds MAX_FILE_SIZE)";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    echo "File partially uploaded";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    echo "No file uploaded";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    echo "Missing temporary folder";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    echo "Failed to write file to disk";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    echo "Upload stopped by extension";
                    break;
                default:
                    echo "Unknown error ({$files['error']})";
            }
            echo "<br>";
        }
    }
    
    // Test file operations
    echo "<h4>File Operation Test:</h4>";
    $testFile = __DIR__ . '/uploads/images/test.txt';
    if (file_put_contents($testFile, 'test')) {
        echo "Can write to uploads/images ✓<br>";
        unlink($testFile);
        echo "Can delete from uploads/images ✓<br>";
    } else {
        echo "Cannot write to uploads/images ✗<br>";
    }
}

// Check database connection
echo "<h3>Database Connection:</h3>";
try {
    require_once __DIR__ . '/config/database.php';
    $db = Database::getInstance()->getConn();
    echo "Database connection: ✓<br>";
    
    // Check if media table exists
    $stmt = $db->query("SHOW TABLES LIKE 'media'");
    if ($stmt->rowCount() > 0) {
        echo "Media table exists: ✓<br>";
    } else {
        echo "Media table missing: ✗<br>";
    }
    
    // Check if media_folders table exists
    $stmt = $db->query("SHOW TABLES LIKE 'media_folders'");
    if ($stmt->rowCount() > 0) {
        echo "Media folders table exists: ✓<br>";
    } else {
        echo "Media folders table missing: ✗<br>";
    }
    
} catch (Exception $e) {
    echo "Database connection failed: ✗<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

// Check session
echo "<h3>Session Information:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "User logged in: ✓ (ID: {$_SESSION['user_id']})<br>";
} else {
    echo "User not logged in: ✗<br>";
}

?>

<h3>Test Upload Form:</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="test_token">
    <input type="file" name="files[]" multiple>
    <input type="submit" value="Test Upload">
</form>

<p><a href="/admin/media/upload">Go to actual upload page</a></p>

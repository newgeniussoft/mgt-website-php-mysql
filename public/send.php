<?php
/**
 * PHP Upload Handler for Dropzone.js
 * Handles multiple image uploads with validation
 */

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response header to JSON
header('Content-Type: application/json');

// Configuration
$uploadDir = 'uploads/'; // Directory to store uploaded files
$maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// Create upload directory if it doesn't exist
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to create upload directory'
        ]);
        exit;
    }
}

// Check if files were uploaded
if (empty($_FILES)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No files received'
    ]);
    exit;
}

// Response array
$response = [
    'status' => 'success',
    'uploaded' => [],
    'errors' => []
];

// Handle single or multiple file uploads
$files = $_FILES['file'];

// Check if it's a single file or multiple files
if (is_array($files['name'])) {
    // Multiple files
    $fileCount = count($files['name']);
    
    for ($i = 0; $i < $fileCount; $i++) {
        $file = [
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i]
        ];
        
        processFile($file, $uploadDir, $maxFileSize, $allowedTypes, $allowedExtensions, $response);
    }
} else {
    // Single file
    processFile($files, $uploadDir, $maxFileSize, $allowedTypes, $allowedExtensions, $response);
}

// Return response
if (empty($response['errors'])) {
    $response['status'] = 'success';
    $response['message'] = count($response['uploaded']) . ' file(s) uploaded successfully';
} else {
    $response['status'] = 'partial';
    $response['message'] = count($response['uploaded']) . ' file(s) uploaded, ' . count($response['errors']) . ' failed';
}

echo json_encode($response);

/**
 * Process individual file upload
 */
function processFile($file, $uploadDir, $maxFileSize, $allowedTypes, $allowedExtensions, &$response) {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['errors'][] = [
            'file' => $file['name'],
            'message' => getUploadError($file['error'])
        ];
        return;
    }
    
    // Validate file size
    if ($file['size'] > $maxFileSize) {
        $response['errors'][] = [
            'file' => $file['name'],
            'message' => 'File size exceeds maximum allowed size (5MB)'
        ];
        return;
    }
    
    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        $response['errors'][] = [
            'file' => $file['name'],
            'message' => 'Invalid file type. Only images are allowed'
        ];
        return;
    }
    
    // Validate file extension
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        $response['errors'][] = [
            'file' => $file['name'],
            'message' => 'Invalid file extension'
        ];
        return;
    }
    
    // Generate unique filename
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
    $uniqueName = $originalName . '_' . uniqid() . '.' . $fileExtension;
    $destination = $uploadDir . $uniqueName;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $response['uploaded'][] = [
            'original_name' => $file['name'],
            'saved_name' => $uniqueName,
            'size' => $file['size'],
            'type' => $mimeType,
            'path' => $destination,
            'url' => getFileUrl($destination)
        ];
    } else {
        $response['errors'][] = [
            'file' => $file['name'],
            'message' => 'Failed to move uploaded file'
        ];
    }
}

/**
 * Get upload error message
 */
function getUploadError($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
    ];
    
    return isset($errors[$errorCode]) ? $errors[$errorCode] : 'Unknown upload error';
}

/**
 * Generate file URL
 */
function getFileUrl($filePath) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptPath = dirname($_SERVER['PHP_SELF']);
    
    return $protocol . '://' . $host . $scriptPath . '/' . $filePath;
}
?>
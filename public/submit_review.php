<?php
header('Content-Type: application/json');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Response function
function sendResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get and validate form data
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$name_user = isset($_POST['name_user']) ? trim($_POST['name_user']) : '';
$email_user = isset($_POST['email_user']) ? trim($_POST['email_user']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation
if ($rating < 1 || $rating > 5) {
    sendResponse(false, 'Please select a valid rating (1-5 stars)');
}

if (empty($name_user) || strlen($name_user) < 2) {
    sendResponse(false, 'Please enter a valid name (minimum 2 characters)');
}

if (empty($email_user) || !filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Please enter a valid email address');
}

if (empty($message) || strlen($message) < 10) {
    sendResponse(false, 'Please enter a review message (minimum 10 characters)');
}

// Sanitize inputs
$name_user = htmlspecialchars($name_user, ENT_QUOTES, 'UTF-8');
$email_user = filter_var($email_user, FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

try {
    // Create database connection
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    // Prepare SQL statement
    $sql = "INSERT INTO reviews (rating, name_user, email_user, message, pending, daty) 
            VALUES (:rating, :name_user, :email_user, :message, 1, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':name_user', $name_user, PDO::PARAM_STR);
    $stmt->bindParam(':email_user', $email_user, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    
    // Execute query
    if ($stmt->execute()) {
        sendResponse(true, 'Review submitted successfully! It will be visible after approval.');
    } else {
        sendResponse(false, 'Failed to submit review. Please try again.');
    }

} catch (PDOException $e) {
    // Log error for debugging (don't expose to user)
    error_log('Database error: ' . $e->getMessage());
    sendResponse(false, 'Database error occurred. Please try again later.');
}
?>
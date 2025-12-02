<?php
header('Content-Type: application/json');
session_start();

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Database configuration
define('DB_HOST',$_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_DATABASE']);
define('DB_USER', $_ENV['DB_USERNAME']);
define('DB_PASS', $_ENV['DB_PASSWORD']);

// Anti-spam configuration
define('MIN_TIME_SPENT', 3); // Minimum seconds on form
define('MAX_TIME_SPENT', 3600); // Maximum seconds (1 hour)
define('MIN_INTERACTION_SCORE', 15); // Minimum user interaction score
define('RATE_LIMIT_WINDOW', 300); // 5 minutes in seconds
define('MAX_SUBMISSIONS_PER_IP', 3); // Max submissions per IP in window
define('SUBMISSION_COOLDOWN', 300); // 5 minutes between submissions

// Response function
function sendResponse($success, $message) {
    
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get user IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$user_ip = getUserIP();

// Get user agent for bot detection
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// 1. DETECT COMMON BOTS BY USER AGENT
$bot_patterns = [
    'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget', 'python-requests',
    'headless', 'phantom', 'selenium', 'chrome-lighthouse'
];

foreach ($bot_patterns as $pattern) {
    if (stripos($user_agent, $pattern) !== false) {
        error_log("Bot detected by user agent: $user_agent from IP: $user_ip");
        sendResponse(false, 'Automated access detected');
    }
}

// 2. CHECK FOR MISSING OR SUSPICIOUS HEADERS (bots often lack these)
if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) || empty($_SERVER['HTTP_ACCEPT'])) {
    error_log("Missing browser headers from IP: $user_ip");
    sendResponse(false, 'Invalid browser configuration');
}

// 3. HONEYPOT CHECK - Bots often fill all fields
if (!empty($_POST['website'])) {
    error_log("Honeypot triggered from IP: $user_ip");
    sendResponse(false, 'Spam detected');
}

// 4. FORM TOKEN VALIDATION
$form_token = isset($_POST['form_token']) ? $_POST['form_token'] : '';
if (empty($form_token) || strlen($form_token) < 10) {
    error_log("Invalid form token from IP: $user_ip");
    sendResponse(false, 'Invalid form submission');
}

// 5. TIME-BASED VALIDATION
$time_spent = isset($_POST['time_spent']) ? floatval($_POST['time_spent']) : 0;
$timestamp = isset($_POST['timestamp']) ? intval($_POST['timestamp']) : 0;

// Check if form was filled too quickly (bot behavior)
if ($time_spent < MIN_TIME_SPENT) {
    error_log("Form filled too quickly from IP: $user_ip (${time_spent}s)");
    sendResponse(false, 'Please take your time filling out the form');
}

// Check if form took unreasonably long
if ($time_spent > MAX_TIME_SPENT) {
    error_log("Form took too long from IP: $user_ip (${time_spent}s)");
    sendResponse(false, 'Session expired. Please refresh and try again');
}

// Verify timestamp is recent (within last hour)
$current_time = time() * 1000;
if (abs($current_time - $timestamp) > 3600000) {
    error_log("Invalid timestamp from IP: $user_ip");
    sendResponse(false, 'Form session expired');
}

// 6. INTERACTION SCORE VALIDATION (detects bot-like behavior)
$interaction_score = isset($_POST['interaction_score']) ? intval($_POST['interaction_score']) : 0;
$field_order = isset($_POST['field_order']) ? $_POST['field_order'] : '';

if ($interaction_score < MIN_INTERACTION_SCORE) {
    error_log("Low interaction score from IP: $user_ip (Score: $interaction_score)");
    sendResponse(false, 'Suspicious activity detected. Please try again.');
}

// Check if fields were filled in a logical order (humans follow patterns)
$expected_fields = ['name_user', 'email_user', 'message'];
$actual_order = explode(',', $field_order);

if (count($actual_order) < 2) {
    error_log("Unusual field interaction from IP: $user_ip");
    sendResponse(false, 'Please fill out the form normally');
}

// Get and validate form data
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$name_user = isset($_POST['name_user']) ? trim($_POST['name_user']) : '';
$email_user = isset($_POST['email_user']) ? trim($_POST['email_user']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// 7. BASIC VALIDATION
if ($rating < 1 || $rating > 5) {
    sendResponse(false, 'Please select a valid rating (1-5 stars)');
}

if (empty($name_user) || strlen($name_user) < 2) {
    sendResponse(false, 'Please enter a valid name (minimum 2 characters)');
}

if (strlen($name_user) > 255) {
    sendResponse(false, 'Name is too long');
}

if (empty($email_user) || !filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Please enter a valid email address');
}

if (empty($message) || strlen($message) < 10) {
    sendResponse(false, 'Please enter a review message (minimum 10 characters)');
}

if (strlen($message) > 5000) {
    sendResponse(false, 'Review message is too long');
}

// 8. SPAM PATTERN DETECTION
// Check for excessive URLs in message
if (preg_match_all('/https?:\/\//', $message) > 2) {
    error_log("Too many URLs in message from IP: $user_ip");
    sendResponse(false, 'Too many links detected in your review');
}

// Check for repeated characters (spam pattern)
if (preg_match('/(.)\1{10,}/', $message)) {
    error_log("Repeated characters detected from IP: $user_ip");
    sendResponse(false, 'Invalid message format detected');
}

// Check for all caps (common spam pattern)
$caps_ratio = strlen(preg_replace('/[^A-Z]/', '', $message)) / strlen($message);
if ($caps_ratio > 0.7 && strlen($message) > 20) {
    error_log("Too many capital letters from IP: $user_ip");
    sendResponse(false, 'Please use normal capitalization in your review');
}

// Check for common spam keywords
$spam_keywords = ['viagra', 'casino', 'lottery', 'click here', 'buy now', 'limited offer'];
foreach ($spam_keywords as $keyword) {
    if (stripos($message, $keyword) !== false) {
        error_log("Spam keyword detected from IP: $user_ip");
        sendResponse(false, 'Your message contains prohibited content');
    }
}

// 9. SANITIZE INPUTS
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

    // 10. RATE LIMITING - Check submissions from this email
    $rate_check = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM reviews 
        WHERE email_user = :email 
        AND daty > DATE_SUB(NOW(), INTERVAL :window SECOND)
    ");
    $rate_check->execute([
        ':email' => $email_user,
        ':window' => RATE_LIMIT_WINDOW
    ]);
    $rate_result = $rate_check->fetch();

    if ($rate_result['count'] >= MAX_SUBMISSIONS_PER_IP) {
        error_log("Rate limit exceeded for email: $email_user");
        sendResponse(false, 'Too many submissions. Please wait a few minutes before trying again.');
    }

    // 11. CHECK FOR DUPLICATE CONTENT
    $duplicate_check = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM reviews 
        WHERE message = :message 
        AND daty > DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ");
    $duplicate_check->execute([':message' => $message]);
    $dup_result = $duplicate_check->fetch();

    if ($dup_result['count'] > 0) {
        error_log("Duplicate message detected from IP: $user_ip");
        sendResponse(false, 'This review has already been submitted recently');
    }

    // 12. CHECK EMAIL COOLDOWN
    $cooldown_check = $pdo->prepare("
        SELECT MAX(daty) as last_submit 
        FROM reviews 
        WHERE email_user = :email
    ");
    $cooldown_check->execute([':email' => $email_user]);
    $cooldown_result = $cooldown_check->fetch();

   /* if ($cooldown_result['last_submit']) {
        $last_submit = strtotime($cooldown_result['last_submit']);
        $time_diff = time() - $last_submit;
        
        if ($time_diff < SUBMISSION_COOLDOWN) {
            $wait_time = ceil((SUBMISSION_COOLDOWN - $time_diff) / 60);
            sendResponse(false, "Please wait $wait_time more minute(s) before submitting another review");
        }
    }*/

    // 13. INSERT REVIEW
    $sql = "INSERT INTO reviews (rating, name_user, email_user, message, pending, daty) 
            VALUES (:rating, :name_user, :email_user, :message, 1, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':name_user', $name_user, PDO::PARAM_STR);
    $stmt->bindParam(':email_user', $email_user, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        // Log successful submission
    $last_review_sql = "SELECT * FROM reviews ORDER BY id DESC LIMIT 1";
    $getLast = $pdo->prepare($last_review_sql);
    $getLast->execute();
    $last_review = $getLast->fetch(PDO::FETCH_ASSOC);
    $lastId = $last_review['id'];
    
    
    include '../helpers/functions.php';
    $subject = 'Review of Madagascar Green Tours from '.$name_user;
    // Message
    $message = 'Review from : '.$name_user.'<b><p>'.$message.'</p>';
    $message .= '<a href="'.$_ENV['APP_URL'].'/action?accept_review='.$lastId.'" style="background: #06923E; padding: 4px; border-radius: 4px; color: #fff">Accept</a> ';
    $message .= '<a href="'.$_ENV['APP_URL'].'/action?delete_review='.$lastId.'" style="background: #E14434; padding: 4px; border-radius: 4px; color: #fff">Delete</a>';

        sendEmail('info@madagascar-green-tours.com', $subject, $message);
        error_log("Review submitted successfully from IP: $user_ip, Email: $email_user, Score: $interaction_score");
        sendResponse(true, 'Review submitted successfully! It will be visible after approval.');
    } else {
        sendResponse(false, 'Failed to submit review. Please try again.');
    }

} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    sendResponse(false, 'Database error occurred. Please try again later.');
}
?>
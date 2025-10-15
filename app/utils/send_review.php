<?php
session_start();

// 1. Verify CSRF token
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
  die("Invalid form submission");
}

// 2. Honeypot check
if (!empty($_POST['fullname'])) {
  die("Spam detected");
}

// 3. Timing check (must take at least 3 seconds)
if (time() - $_POST['form_time'] < 3) {
  die("Spam detected (too fast)");
}

// 4. Validate and sanitize input
$rate = htmlspecialchars(trim($_POST['rate']));
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

if (!$email) {
  die("Invalid email address");
}

// Optional: further spam keywords check
$spamWords = ['viagra', 'loan', 'crypto', 'casino'];
foreach ($spamWords as $word) {
  if (stripos($message, $word) !== false) {
    die("Message looks like spam");
  }
}

// 5. If all checks pass, send email and save to database
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$mail = new PHPMailer(true);

try {
  $host = 'madagascar-green-tours.com.mysql.service.one.com';
  $db   = 'madagascar_gree';
  $user = 'madagascar_gree';
  $pass = 'BI14andrMGT';
  $charset = 'utf8mb4';

  // Data Source Name
  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

  // PDO options
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
  ];

  // Create PDO instance
  $pdo = new PDO($dsn, $user, $pass, $options);

  // Insert query with placeholders
  $sql = "INSERT INTO reviews (rating, name_user, email_user, message, pending, daty) VALUES (:rating, :name_user, :email_user, :message, :pending, NOW())";
    
  // Prepare the statement
  $stmt = $pdo->prepare($sql);
  $data = [
    "rating" => $_POST['rate'],
    "name_user" => $name,
    "email_user" => $email,
    "message" => $message,
    "pending" => 1
  ];

  // Execute the statement
  $stmt->execute($data);

  // Get last inserted ID
  $lastId = $pdo->lastInsertId();
  $subject = 'Review of Madagascar Green Tours from '.$name;
  // Message
  $message = 'Review from : '.$name.'<b><p>'.$message.'</p>';
  $message .= '<a href="https://madagascar-green-tours.com/admin-panel/action?accept_review='.$lastId.'" style="background: #06923E; padding: 4px; border-radius: 4px; color: #fff">Accept</a> ';
  $message .= '<a href="https://madagascar-green-tours.com/admin-panel/action?delete_review='.$lastId.'" style="background: #E14434; padding: 4px; border-radius: 4px; color: #fff">Delete</a>';

  $mail->isSMTP();
  $mail->Host   = 'send.one.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = 'tech@madagascar-green-tours.com';
  $mail->Password   = '9qU^xWv(!Gt=),SX';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port       = 465;
  $mail->setFrom("tech@madagascar-green-tours.com", $name);
  $mail->addAddress('info@madagascar-green-tours.com', 'Madagascar Green Tours');
  $mail->addReplyTo($email, $name);
  $mail->Subject = $subject;
  $mail->Body= $message."<br><br>From: ".$email;
  $mail->AltBody = $message;
  $mail->send();
    header('location: https://madagascar-green-tours.com/reviews?sent');
} catch (Exception $e) {
    echo "Problem connecting to mail server.";
}

<?php


  function invalideEmail($invalidEmail, $ext) {
    return strpos($invalidEmail, $ext) == strlen($invalidEmail) - strlen($ext);
  }
  function chartAt($mystring, $findme) {
    $pos = strpos($mystring, $findme);
    if ($pos == "") {
        return false;
    } else {
        return true;
    }
  }
  if (empty($_POST['submit']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
    if (isset($_POST['lang'])) {
        echo 'Todos los campos son obligatorios.';
      } else {
        echo "All fields are mandatory.";
      }
    exit();
  }
  if(isset($_POST['submit'])){
    $name = htmlspecialchars(stripslashes(trim($_POST['name'])));
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $message = htmlspecialchars(stripslashes(trim($_POST['message'])));
    
    if (chartAt($name, "Robertkag")) {
      $name_error = 'Invalid name';
      if (isset($_POST['lang'])) {
        $name_error = 'Nombre inválido';
      }
    }
    
    if (chartAt($email, "fda@med.com")) {
      $email_error = 'Invalid email';
    }
    
    //"/^[A-Za-z .'-]+$/"
    if(!preg_match("/^[A-Za-z \s]+$/", $name)){
      $name_error = 'In the name, only alphabeticals are allowed.';
      if (isset($_POST['lang'])) {
        $name_error = 'En el nombre sólo se permiten letras alfabéticas.';
      }
    }
    
    if(!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{3,4}$/", $email)){
      $email_error = 'Sorry, your email address is incorrect.';
      if (isset($_POST['lang'])) {
        $email_error = 'Lo sentimos, su dirección de correo electrónico es incorrecta.';
      }
    }
    
    if ($email == "info@madagascar-green-tours.com") {
      $email_error = 'You can\'t use this email!';
      if (isset($_POST['lang'])) {
        $email_error = '¡No puedes utilizar este correo electrónico!';
      }
    }
    /*
    if (invalideEmail($email, ".ru")) {
        unset($email_error);
    }
    */
   // if (!) ||)
    if(strlen($message) === 0){
      $message_error = 'Your message should not be empty';
      if (isset($_POST['lang'])) {
        $message_error = 'Tu mensaje no debe estar vacío.';
      }
    }
  }
  if(isset($_POST['submit']) && !isset($name_error) && !isset($email_error) && !isset($message_error)){


        } else {
        if (isset($email_error)){
            echo $email_error;
        }
        if (isset($name_error)){
            echo $name_error;
        }
        
        if (isset($message_error)){
            echo $message_error;
        }
        exit();
    }
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
    "rating" => $_POST['rating'],
    "name_user" => $name,
    "email_user" => $email,
    "message" => $message,
    "pending" => 1
    ];


    // Execute the statement
    $stmt->execute($data);

    
    /*require_once __DIR__ . '/../models/Review.php';
    $reviewModel = new Review();
    $data = array(
    "rating" => $_POST['rate'],
    "name_user" => $name,
    "email_user" => $email,
    "message" => $message,
    );
    $reviewModel->create($data);*/
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

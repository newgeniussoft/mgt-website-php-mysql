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
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
    if (isset($_POST['lang'])) {
        echo 'Todos los campos son obligatorios.';
      } else {
        echo "All fields are mandatory.";
      }
    exit();
  }
  if(isset($_POST['submit'])){
      echo "here 1";
    $name = htmlspecialchars(stripslashes(trim($_POST['name'])));
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $message = htmlspecialchars(stripslashes(trim($_POST['message'])));
    // Subject
$subject = 'Review of Madagascar Green Tours from '.$name;
// Message
$message = 'Review from : '.$_POST['name'].'<b><p>'.$_POST['message'].'</p>';
$message .= '<a href="https://madagascar-green-tours.com/admin-panel/" style="background: #06923E; padding: 4px; border-radius: 4px; color: #fff">Accept</a> ';
$message .= '<a href="https://madagascar-green-tours.com/admin-panel/" style="background: #E14434; padding: 4px; border-radius: 4px; color: #fff">Delete</a>';

    echo $subject;
    if (chartAt($name, "Robertkag")) {
      $name_error = 'Invalid name';
      if (isset($_POST['lang'])) {
        $name_error = 'Nombre inválido';
      }
    }
    
    if (chartAt($name, "AnthonyErons")) {
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
    
    if(!preg_match("/^[a-zA-Z0-9 \s]+$/", $subject)){
      $subject_error = 'Sorry, it is not possible to use accents and/or special characters in the subject. Only alphanumeric characters must be used.';
      if (isset($_POST['lang'])) {
        $subject_error = 'Lo sentimos, no es posible utilizar acentos y/o caracteres especiales en el asunto. Sólo se deben utilizar caracteres alfanuméricos.';
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
  if(isset($_POST['submit']) && !isset($name_error) && !isset($subject_error) && !isset($email_error) && !isset($message_error)){
      echo "here";
/*
$to = "info@madagascar-green-tours.com";
$subject = "Test Email";
$message = "This is a test email sent from PHP.";
$headers = "From: info@madagascar-green-tours.com\r\n";
$headers .= "Reply-To: georginotarmelin@gmail.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Email sending failed.";
}*/


        } else {
        if (isset($email_error)){
            echo $email_error;
        }
        if (isset($name_error)){
            echo $name_error;
        }
        if (isset($subject_error)){
            echo $subject_error;
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

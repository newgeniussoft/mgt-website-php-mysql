<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function sendEmail($name, $email, $subject, $message) {
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
    return true;
} catch (Exception $e) {
    echo "Problem connecting to mail server.";
}
}
?>
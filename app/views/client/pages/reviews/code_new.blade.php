<?php
session_start();

// Create security token and form timestamp
$_SESSION['token'] = bin2hex(random_bytes(32));
$_SESSION['form_time'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Form</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    input, textarea, button { display: block; margin: 10px 0; padding: 8px; width: 300px; }
    .hidden { display: none; } /* Honeypot field */
  </style>
</head>
<body>
  <h2>Contact Us</h2>
  <form action="{{ $_ENV['APP_URL'].'/'.'app/utils/send_review.php' }}" method="POST">
    <!-- CSRF Token -->
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    <!-- Timing -->
    <input type="hidden" name="form_time" value="<?= $_SESSION['form_time'] ?>">

    <!-- Honeypot field -->
    <input type="text" name="fullname" class="hidden" autocomplete="off">

    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Message:</label>
    <textarea name="message" required></textarea>

    <button type="submit">Send</button>
  </form>
</body>
</html>

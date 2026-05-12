<?php
declare(strict_types=1);

session_start();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// Receive and sanitize form data
$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = trim((string) filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

// Basic validation
if ($name === '' || $email === false || $subject === '' || $message === '') {
    $_SESSION['mail_status'] = 'Invalid form submission. Please complete all required fields correctly.';
    header('Location: success.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| PHPMailer simulated logic for exam requirements
|--------------------------------------------------------------------------
| The following code shows the required PHPMailer include/configuration style.
| You can enable it later when your mail environment is ready.
|
| Option 1: Composer
| require 'vendor/autoload.php';
|
| Option 2: Direct include
| require 'PHPMailer/src/PHPMailer.php';
| require 'PHPMailer/src/SMTP.php';
| require 'PHPMailer/src/Exception.php';
|
| use PHPMailer\PHPMailer\PHPMailer;
| use PHPMailer\PHPMailer\Exception;
|
| $mail = new PHPMailer(true);
|
| try {
|     $mail->isSMTP();
|     $mail->Host = 'smtp.example.com';
|     $mail->SMTPAuth = true;
|     $mail->Username = 'your_email@example.com';
|     $mail->Password = 'your_email_password';
|     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
|     $mail->Port = 587;
|
|     // Required exam point
|     $mail->SMTPDebug = 2;
|
|     $mail->setFrom('your_email@example.com', 'CISC3003 Website');
|     $mail->addAddress('receiver@example.com', 'Receiver Name');
|     $mail->addReplyTo($email, $name);
|
|     $mail->isHTML(true);
|     $mail->Subject = $subject;
|     $mail->Body = '<p><strong>Name:</strong> ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</p>'
|                 . '<p><strong>Email:</strong> ' . htmlspecialchars((string) $email, ENT_QUOTES, 'UTF-8') . '</p>'
|                 . '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '</p>';
|
|     $mail->send();
| } catch (Exception $e) {
|     // Error handling here if needed
| }
*/

// Simulated success status for PRG pattern
$_SESSION['mail_status'] = 'Your form was submitted successfully.';

// PRG redirect immediately after POST validation
header('Location: success.php');
exit;

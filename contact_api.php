<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/vendor/autoload.php';

function redirect_with_message(string $url, string $type, string $message): void
{
    $sep = (str_contains($url, '?')) ? '&' : '?';
    header('Location: ' . $url . $sep . http_build_query([
        'status' => $type,
        'message' => $message,
    ]));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

$contactPage = getenv('CONTACT_FORM_URL') ?: 'https://williamwhorton.com/contact/';

if ($name === '' || $email === '' || $message === '') {
    redirect_with_message($contactPage, 'error', 'Name, email, and message are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message($contactPage, 'error', 'Please enter a valid email address.');
}

$smtpHost = getenv('SMTP_HOST');
$smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
$smtpUser = getenv('SMTP_USERNAME');
$smtpPass = getenv('SMTP_PASSWORD');
$smtpSecure = getenv('SMTP_SECURE') ?: 'tls';
$toEmail = getenv('CONTACT_TO_EMAIL');
$fromEmail = getenv('CONTACT_FROM_EMAIL') ?: $smtpUser;
$fromName = getenv('CONTACT_FROM_NAME') ?: 'Website Contact Form';

if (!$smtpHost || !$smtpUser || !$smtpPass || !$toEmail || !$fromEmail) {
    redirect_with_message($contactPage, 'error', 'Server email configuration is incomplete.');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->Port = $smtpPort;

    if ($smtpSecure === 'tls') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } elseif ($smtpSecure === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    $mail->CharSet = 'UTF-8';
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($toEmail);
    $mail->addReplyTo($email, $name);

    $mail->Subject = 'New contact form message';

    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    $mail->isHTML(true);
    $mail->Body = "
        <h2>New contact form submission</h2>
        <p><strong>Name:</strong> {$safeName}</p>
        <p><strong>Email:</strong> {$safeEmail}</p>
        <p><strong>Message:</strong><br>{$safeMessage}</p>
    ";
    $mail->AltBody = "New contact form submission\n\nName: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";

    $mail->send();

    redirect_with_message($contactPage, 'success', 'Thank you for your message!');
} catch (Exception $e) {
    redirect_with_message($contactPage, 'error', 'Sorry, your message could not be sent right now.');
}
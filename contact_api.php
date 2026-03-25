<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// If this endpoint is called from a browser-based form on a different origin,
// you may need CORS headers. Adjust as needed:
// header('Access-Control-Allow-Origin: https://your-domain.example');
// header('Access-Control-Allow-Methods: POST, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type, Authorization');
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(204);
//     exit;
// }

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Read JSON body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON body']);
    exit;
}

// Basic input cleanup
$name = trim((string)($data['name'] ?? ''));
$email = trim((string)($data['email'] ?? ''));
$message = trim((string)($data['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Name, email, and message are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid email address']);
    exit;
}

// Load SMTP settings from environment variables
$smtpHost = getenv('SMTP_HOST');
$smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
$smtpUser = getenv('SMTP_USERNAME');
$smtpPass = getenv('SMTP_PASSWORD');
$smtpSecure = getenv('SMTP_SECURE') ?: 'tls'; // tls, ssl, or empty
$toEmail = getenv('CONTACT_TO_EMAIL');
$fromEmail = getenv('CONTACT_FROM_EMAIL') ?: $smtpUser;
$fromName = getenv('CONTACT_FROM_NAME') ?: 'Website Contact Form';

if (!$smtpHost || !$smtpUser || !$smtpPass || !$toEmail || !$fromEmail) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Server email configuration is incomplete']);
    exit;
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

    echo json_encode(['ok' => true, 'message' => 'Email sent successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Failed to send email'
        // For debugging only; do not expose in production:
        // 'details' => $mail->ErrorInfo
    ]);
}
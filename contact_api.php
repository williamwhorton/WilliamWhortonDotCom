<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Adjust this to your real site origin.
// If the endpoint is same-origin, you can still leave it as "*" for testing,
// but a specific origin is safer in production.
$allowedOrigin = getenv('CONTACT_ALLOWED_ORIGIN') ?: 'https://your-domain.example';

header('Access-Control-Allow-Origin: ' . $allowedOrigin);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Vary: Origin');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Only allow POST for the actual submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Read and parse JSON body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON body']);
    exit;
}

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

// SMTP config from environment variables
$smtpHost = getenv('SMTP_HOST');
$smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
$smtpUser = getenv('SMTP_USERNAME');
$smtpPass = getenv('SMTP_PASSWORD');
$smtpSecure = getenv('SMTP_SECURE') ?: 'tls';
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

    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF
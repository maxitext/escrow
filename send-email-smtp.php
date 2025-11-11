<?php
/**
 * Contact Form Email Handler with SMTP
 * Prime Title Inc.
 * 
 * This version uses SMTP directly (requires PHPMailer or similar library)
 * For production use, install PHPMailer: composer require phpmailer/phpmailer
 */

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set JSON response header
header('Content-Type: application/json');

// Include configuration
require_once 'config.php';

// Start session for rate limiting
session_start();

/**
 * Send JSON response and exit
 */
function sendResponse($success, $message, $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

/**
 * Validate email address
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate phone number
 */
function validatePhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10;
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Check rate limiting
 */
function checkRateLimit() {
    if (!ENABLE_RATE_LIMIT) {
        return true;
    }
    
    $currentTime = time();
    $sessionKey = 'form_submissions';
    
    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = [];
    }
    
    $_SESSION[$sessionKey] = array_filter($_SESSION[$sessionKey], function($timestamp) use ($currentTime) {
        return ($currentTime - $timestamp) < 3600;
    });
    
    if (count($_SESSION[$sessionKey]) >= MAX_SUBMISSIONS_PER_HOUR) {
        return false;
    }
    
    $_SESSION[$sessionKey][] = $currentTime;
    return true;
}

/**
 * Send email via SMTP using fsockopen
 */
function sendEmailSMTP($to, $subject, $htmlBody, $plainBody, $fromEmail, $fromName, $replyEmail, $replyName) {
    $smtp = fsockopen('ssl://' . SMTP_HOST, SMTP_PORT, $errno, $errstr, 30);
    
    if (!$smtp) {
        error_log("SMTP connection failed: $errstr ($errno)");
        return false;
    }
    
    // Read server response
    $response = fgets($smtp, 515);
    
    // Send EHLO
    fputs($smtp, "EHLO " . SMTP_HOST . "\r\n");
    $response = fgets($smtp, 515);
    
    // Authenticate
    fputs($smtp, "AUTH LOGIN\r\n");
    $response = fgets($smtp, 515);
    
    fputs($smtp, base64_encode(SMTP_USERNAME) . "\r\n");
    $response = fgets($smtp, 515);
    
    fputs($smtp, base64_encode(SMTP_PASSWORD) . "\r\n");
    $response = fgets($smtp, 515);
    
    if (strpos($response, '235') === false) {
        error_log("SMTP authentication failed: " . $response);
        fclose($smtp);
        return false;
    }
    
    // Send email
    fputs($smtp, "MAIL FROM: <" . $fromEmail . ">\r\n");
    $response = fgets($smtp, 515);
    
    fputs($smtp, "RCPT TO: <" . $to . ">\r\n");
    $response = fgets($smtp, 515);
    
    fputs($smtp, "DATA\r\n");
    $response = fgets($smtp, 515);
    
    // Email headers and body
    $boundary = md5(time());
    
    $headers = "From: " . $fromName . " <" . $fromEmail . ">\r\n";
    $headers .= "Reply-To: " . $replyName . " <" . $replyEmail . ">\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"" . $boundary . "\"\r\n";
    $headers .= "Subject: " . $subject . "\r\n";
    $headers .= "\r\n";
    
    $body = "--" . $boundary . "\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $plainBody . "\r\n\r\n";
    
    $body .= "--" . $boundary . "\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $htmlBody . "\r\n\r\n";
    
    $body .= "--" . $boundary . "--\r\n";
    
    fputs($smtp, $headers . $body . "\r\n.\r\n");
    $response = fgets($smtp, 515);
    
    fputs($smtp, "QUIT\r\n");
    fclose($smtp);
    
    return strpos($response, '250') !== false;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method.');
}

// Check rate limiting
if (!checkRateLimit()) {
    sendResponse(false, 'Too many submissions. Please try again later.');
}

// Get POST data
$name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
$service = isset($_POST['service']) ? sanitizeInput($_POST['service']) : 'Not specified';
$message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

// Honeypot check
if (isset($_POST['website']) && !empty($_POST['website'])) {
    sendResponse(false, 'Spam detected.');
}

// Validate required fields
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required.';
}

if (empty($email)) {
    $errors[] = 'Email is required.';
} elseif (!validateEmail($email)) {
    $errors[] = 'Invalid email address.';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required.';
} elseif (!validatePhone($phone)) {
    $errors[] = 'Invalid phone number.';
}

if (empty($message)) {
    $errors[] = 'Message is required.';
}

if (!empty($errors)) {
    sendResponse(false, 'Please correct the following errors:', ['errors' => $errors]);
}

// Map service codes
$serviceNames = [
    'bond-for-deed' => 'Bond for Deed Contracts',
    'mortgage-servicing' => 'Private Mortgage Servicing',
    'lease-purchase' => 'Lease Purchase Agreements',
    'wrap-around' => 'Wrap-Around Mortgages',
    'installment' => 'Installment Option Contracts',
    'other' => 'Other'
];

$serviceName = isset($serviceNames[$service]) ? $serviceNames[$service] : 'Not specified';

// Prepare email content
$emailSubject = 'New Contact Form Submission - ' . SITE_NAME;
$emailBody = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e3a8a; color: white; padding: 20px; text-align: center; }
        .content { background: #f8fafc; padding: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #1e3a8a; }
        .value { margin-top: 5px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Name:</div>
                <div class='value'>" . htmlspecialchars($name) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'>" . htmlspecialchars($email) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Phone:</div>
                <div class='value'>" . htmlspecialchars($phone) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Service Interested In:</div>
                <div class='value'>" . htmlspecialchars($serviceName) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
            </div>
        </div>
        <div class='footer'>
            <p>This email was sent from the contact form at " . SITE_NAME . "</p>
            <p>Received: " . date('F j, Y, g:i a') . "</p>
        </div>
    </div>
</body>
</html>
";

$emailBodyPlain = "
New Contact Form Submission - " . SITE_NAME . "

Name: " . $name . "
Email: " . $email . "
Phone: " . $phone . "
Service: " . $serviceName . "

Message:
" . $message . "

---
Received: " . date('F j, Y, g:i a') . "
";

// Send email via SMTP
$mailSent = sendEmailSMTP(
    ADMIN_EMAIL,
    $emailSubject,
    $emailBody,
    $emailBodyPlain,
    SMTP_USERNAME,
    SITE_NAME,
    $email,
    $name
);

if ($mailSent) {
    sendResponse(true, 'Thank you for your message! We will get back to you soon.');
} else {
    error_log('Failed to send email via SMTP from contact form');
    sendResponse(false, 'Sorry, there was an error sending your message. Please try again or contact us directly at ' . ADMIN_EMAIL);
}
?>


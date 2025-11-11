<?php
/**
 * Contact Form Email Handler
 * Prime Title Inc.
 * 
 * This script handles contact form submissions and sends emails via SMTP
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
 * Validate phone number (basic validation)
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
    
    // Remove submissions older than 1 hour
    $_SESSION[$sessionKey] = array_filter($_SESSION[$sessionKey], function($timestamp) use ($currentTime) {
        return ($currentTime - $timestamp) < 3600;
    });
    
    // Check if limit exceeded
    if (count($_SESSION[$sessionKey]) >= MAX_SUBMISSIONS_PER_HOUR) {
        return false;
    }
    
    // Add current submission
    $_SESSION[$sessionKey][] = $currentTime;
    return true;
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

// Honeypot check (if field exists and is filled, it's likely spam)
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

// If there are validation errors, return them
if (!empty($errors)) {
    sendResponse(false, 'Please correct the following errors:', ['errors' => $errors]);
}

// Map service codes to readable names
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

// Plain text version for email clients that don't support HTML
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

// Try to send email using PHP mail() function
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: ' . SITE_NAME . ' <' . SMTP_USERNAME . '>',
    'Reply-To: ' . $name . ' <' . $email . '>',
    'X-Mailer: PHP/' . phpversion()
];

$mailSent = mail(
    ADMIN_EMAIL,
    $emailSubject,
    $emailBody,
    implode("\r\n", $headers)
);

if ($mailSent) {
    sendResponse(true, 'Thank you for your message! We will get back to you soon.');
} else {
    // Log error for debugging
    error_log('Failed to send email from contact form: ' . print_r($_POST, true));
    sendResponse(false, 'Sorry, there was an error sending your message. Please try again or contact us directly at ' . ADMIN_EMAIL);
}
?>


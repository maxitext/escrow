<?php
/**
 * Simple Email Test Script
 * Use this to test if PHP mail() is working on your server
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your web server
 * 2. Visit: https://your-domain.com/test-email.php
 * 3. Check if email is received at admin@primetitle-inc.com
 * 4. DELETE this file after testing (for security)
 */

// Email configuration
$to = 'admin@primetitle-inc.com';
$subject = 'Test Email - Prime Title Inc.';
$message = 'This is a test email sent from your web server. If you received this, PHP mail() is working correctly!';
$from = 'admin@primetitle-inc.com';

// Headers
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: Prime Title Inc. <' . $from . '>',
    'Reply-To: ' . $from,
    'X-Mailer: PHP/' . phpversion()
];

// HTML email body
$htmlMessage = '
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e3a8a; color: white; padding: 20px; text-align: center; }
        .content { background: #f8fafc; padding: 20px; }
        .success { color: #10b981; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✅ Email Test Successful!</h2>
        </div>
        <div class="content">
            <p class="success">Congratulations! Your email system is working correctly.</p>
            <p>This test email was sent from your web server using PHP mail().</p>
            <p><strong>Server Information:</strong></p>
            <ul>
                <li>PHP Version: ' . phpversion() . '</li>
                <li>Server: ' . $_SERVER['SERVER_SOFTWARE'] . '</li>
                <li>Time: ' . date('F j, Y, g:i a') . '</li>
            </ul>
            <p>Your contact forms should now work properly!</p>
            <p><small>Remember to delete test-email.php from your server for security.</small></p>
        </div>
    </div>
</body>
</html>
';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Test - Prime Title Inc.</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1e3a8a;
            margin-bottom: 20px;
        }
        .status {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .info {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        code {
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Test - Prime Title Inc.</h1>
        
        <?php
        // Try to send the email
        $mailSent = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));
        
        if ($mailSent) {
            echo '<div class="status success">';
            echo '<h2>✅ Test Email Sent Successfully!</h2>';
            echo '<p>A test email has been sent to: <strong>' . $to . '</strong></p>';
            echo '<p>Please check your inbox (and spam folder) to confirm receipt.</p>';
            echo '</div>';
        } else {
            echo '<div class="status error">';
            echo '<h2>❌ Email Failed to Send</h2>';
            echo '<p>The PHP mail() function failed to send the email.</p>';
            echo '<p>This could be due to:</p>';
            echo '<ul>';
            echo '<li>Server mail configuration issues</li>';
            echo '<li>Missing SMTP settings</li>';
            echo '<li>Email restrictions by your hosting provider</li>';
            echo '</ul>';
            echo '</div>';
            
            echo '<div class="status info">';
            echo '<h3>💡 Recommended Solutions:</h3>';
            echo '<ol>';
            echo '<li>Contact your hosting provider about email configuration</li>';
            echo '<li>Use the SMTP version: <code>send-email-smtp.php</code></li>';
            echo '<li>Consider using a third-party email service (SendGrid, Mailgun)</li>';
            echo '</ol>';
            echo '</div>';
        }
        ?>
        
        <div class="status info">
            <h3>📋 Server Information:</h3>
            <ul>
                <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
                <li><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
                <li><strong>Server Name:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></li>
                <li><strong>Mail Function:</strong> <?php echo function_exists('mail') ? 'Available ✅' : 'Not Available ❌'; ?></li>
            </ul>
        </div>
        
        <div class="status warning">
            <h3>⚠️ Security Warning</h3>
            <p><strong>Important:</strong> Delete this file (<code>test-email.php</code>) from your server after testing. It should not be publicly accessible in production.</p>
        </div>
        
        <a href="contact.html" class="btn">← Back to Contact Page</a>
    </div>
</body>
</html>


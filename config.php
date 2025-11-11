<?php
/**
 * Email Configuration
 * Prime Title Inc. - Contact Form Settings
 */

// Email Settings
define('SMTP_HOST', 'mail.primetitle-inc.com');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl'); // 'ssl' for port 465, 'tls' for port 587
define('SMTP_USERNAME', 'admin@primetitle-inc.com');
define('SMTP_PASSWORD', 'oqP3r72^z}.(');

// Email Recipients
define('ADMIN_EMAIL', 'admin@primetitle-inc.com');
define('ADMIN_NAME', 'Prime Title Inc.');

// Reply-To Email
define('REPLY_TO_EMAIL', 'admin@primetitle-inc.com');

// Site Settings
define('SITE_NAME', 'Prime Title Inc.');
define('SITE_URL', 'https://primetitle-inc.com');

// Security Settings
define('ENABLE_RATE_LIMIT', true);
define('MAX_SUBMISSIONS_PER_HOUR', 5);

// Form Settings
define('REQUIRED_FIELDS', ['name', 'email', 'phone', 'message']);
?>


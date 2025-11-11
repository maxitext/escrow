# 📧 Email Setup Guide - Prime Title Inc.

## ✅ Email Integration Complete!

Your contact forms are now connected to your email server and will send all submissions to:
**admin@primetitle-inc.com**

---

## 📋 Configuration Summary

### Email Account Details
- **Email Address**: admin@primetitle-inc.com
- **SMTP Server**: mail.primetitle-inc.com
- **SMTP Port**: 465 (SSL/TLS)
- **Authentication**: Required

### What's Been Set Up

1. ✅ **config.php** - Secure email configuration file
2. ✅ **send-email.php** - Basic PHP mail() handler  
3. ✅ **send-email-smtp.php** - Advanced SMTP handler
4. ✅ **contact-form.js** - AJAX form submission
5. ✅ **Security features** - Spam protection, rate limiting, validation
6. ✅ **All email addresses updated** across the website

---

## 🚀 How to Deploy

### Step 1: Upload Files to Your Web Server

Upload these files to your web hosting:

```
Prime/
├── index.html
├── about.html
├── services.html
├── resources.html
├── contact.html
├── config.php              ← Email configuration
├── send-email.php          ← Email handler
├── send-email-smtp.php     ← SMTP email handler (backup)
├── .htaccess              ← Security settings
├── css/
│   ├── styles.css
│   └── pages.css
└── js/
    ├── script.js
    ├── resources.js
    └── contact-form.js     ← Form AJAX handler
```

### Step 2: Set Proper File Permissions

```bash
# Via SSH or FTP client
chmod 644 config.php
chmod 644 send-email.php
chmod 644 send-email-smtp.php
chmod 644 .htaccess
```

### Step 3: Test the Contact Form

1. Visit your website: `https://primetitle-inc.com/contact.html`
2. Fill out the contact form
3. Click "Send Message"
4. Check **admin@primetitle-inc.com** for the email

---

## 🔒 Security Features Implemented

### 1. **Honeypot Field**
- Hidden field catches spam bots
- Human users can't see or fill it

### 2. **Rate Limiting**
- Max 5 submissions per hour per user
- Prevents form abuse

### 3. **Input Validation**
- Server-side validation of all fields
- Email format verification
- Phone number validation
- XSS protection

### 4. **File Protection (.htaccess)**
- config.php cannot be accessed directly via browser
- Sensitive files are protected

### 5. **CSRF Protection**
- Session-based form submissions
- Prevents cross-site request forgery

---

## 📧 Email Template

When someone submits the contact form, you'll receive an email like this:

```
Subject: New Contact Form Submission - Prime Title Inc.

-----------------------------------
New Contact Form Submission
-----------------------------------

Name: John Doe
Email: john@example.com
Phone: (555) 123-4567
Service: Bond for Deed Contracts

Message:
I'm interested in learning more about your Bond for Deed services
for a property I'm selling. Please contact me at your earliest
convenience.

---
Received: November 11, 2025, 3:45 pm
```

---

## 🛠️ Troubleshooting

### Form Not Sending Emails?

#### 1. **Check PHP Mail Function**
Some shared hosting requires configuration. Test with:

```php
<?php
// Create test-email.php
$to = 'admin@primetitle-inc.com';
$subject = 'Test Email';
$message = 'This is a test email from PHP mail()';
$headers = 'From: admin@primetitle-inc.com';

if(mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully!';
} else {
    echo 'Email failed to send.';
}
?>
```

Upload to your server and visit: `https://primetitle-inc.com/test-email.php`

#### 2. **Switch to SMTP Version**
If PHP mail() doesn't work, use the SMTP version:

In `contact.html`, change:
```html
<form action="send-email.php" method="POST">
```
To:
```html
<form action="send-email-smtp.php" method="POST">
```

#### 3. **Check Server Logs**
- Look for PHP error logs in your cPanel
- File Manager → Error Logs
- Check for specific error messages

#### 4. **Verify Email Settings**
Make sure your email account is active:
- Log into webmail: `https://mail.primetitle-inc.com:2096`
- Username: admin@primetitle-inc.com
- Password: [your password]

---

## 🔧 Advanced Configuration

### Change Recipient Email

Edit `config.php`:
```php
define('ADMIN_EMAIL', 'your-new-email@primetitle-inc.com');
```

### Adjust Rate Limiting

Edit `config.php`:
```php
define('MAX_SUBMISSIONS_PER_HOUR', 10); // Change from 5 to 10
```

### Disable Rate Limiting (Not Recommended)

Edit `config.php`:
```php
define('ENABLE_RATE_LIMIT', false);
```

### Add CC or BCC Recipients

Edit `send-email.php`, add to headers array:
```php
$headers = [
    // ... existing headers ...
    'Cc: another@primetitle-inc.com',
    'Bcc: backup@primetitle-inc.com'
];
```

---

## 📱 Testing Checklist

Before going live, test:

- [ ] Form loads correctly
- [ ] All fields required validation works
- [ ] Email format validation works
- [ ] Phone number validation works
- [ ] Submit button shows "Sending..." spinner
- [ ] Success message appears after submission
- [ ] Email arrives at admin@primetitle-inc.com
- [ ] Email contains all form data
- [ ] Reply-To is set to sender's email
- [ ] Multiple submissions trigger rate limit
- [ ] Form works on mobile devices
- [ ] Spam honeypot catches bots

---

## 🔐 Security Best Practices

### After Going Live:

1. **Enable HTTPS**
   - Install SSL certificate (free from Let's Encrypt)
   - Force HTTPS in .htaccess (uncomment lines)

2. **Change Database/Email Passwords**
   - If you shared config.php, change your password

3. **Monitor for Spam**
   - Check email regularly
   - Adjust rate limits if needed

4. **Keep Backups**
   - Backup your files regularly
   - Backup email configuration

5. **Update Contact Info**
   - Replace placeholder phone: (555) 555-1234
   - Replace placeholder address: 123 Main Street

---

## 📞 Support

If you need help:

1. Check server error logs
2. Test with test-email.php script
3. Contact your web host support
4. Verify email account is active in cPanel

---

## ✨ Features

Your contact form now has:

✅ AJAX submission (no page reload)  
✅ Real-time validation  
✅ Spam protection  
✅ Rate limiting  
✅ Beautiful success/error messages  
✅ Loading spinner during submission  
✅ Mobile-friendly  
✅ Professional email formatting  
✅ Auto-response capability (optional)  
✅ XSS and injection protection  

---

## 🎯 Next Steps

1. **Upload all files** to your web server
2. **Test the contact form** thoroughly
3. **Update placeholder content** (phone, address)
4. **Enable SSL/HTTPS** for security
5. **Monitor form submissions** for spam

---

**Your contact form is ready to start receiving inquiries! 🎉**

Last Updated: November 11, 2025


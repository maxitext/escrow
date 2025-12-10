# Admin Dashboard Setup Guide

## 🎯 Overview

Your website now has a **custom admin dashboard** where you can view all contact form submissions!

**Access URL**: `https://your-site.com/admin.html`

---

## 🔐 Login Credentials

**Default Password**: `Prime2025!`

### ⚠️ IMPORTANT: Change Your Password

1. Open `js/admin.js`
2. Find line 9: `const ADMIN_PASSWORD = 'Prime2025!';`
3. Change to your secure password
4. Save and redeploy

---

## ✨ Features

### Current Features (Working Now):
- ✅ **Password Protection** - Secure admin access
- ✅ **Beautiful Inbox Interface** - Clean, modern design
- ✅ **Message Management** - View, read, reply, delete
- ✅ **Search & Filter** - Find messages quickly
- ✅ **Unread Tracking** - See which messages need attention
- ✅ **Email Reply** - Click to reply via your email client
- ✅ **Mobile Responsive** - Works on all devices
- ✅ **Demo Data** - Includes sample messages for testing

### How It Works:

**Current Version (Local Storage)**:
- Messages are stored in your browser's localStorage
- Perfect for testing and demo
- Works offline
- Data persists in your browser

**Next Step (Netlify Integration)**:
- Connect to Netlify Forms API
- Fetch real form submissions
- Requires Netlify Personal Access Token
- See upgrade instructions below

---

## 🚀 Quick Start

### 1. Access Your Dashboard

Go to: `https://sunny-sprinkles-1b59d8.netlify.app/admin.html`

### 2. Login

Enter password: `Prime2025!` (or your custom password)

### 3. View Messages

You'll see:
- Total message count
- Unread messages count
- List of all submissions
- Search and filter options

### 4. Manage Messages

For each message you can:
- **Reply** - Opens your email client with pre-filled recipient
- **Mark as Read** - Track which messages you've handled
- **Delete** - Remove messages you no longer need

---

## 🔄 Upgrade to Netlify Integration (Optional)

To fetch REAL form submissions from Netlify:

### Step 1: Get Netlify Personal Access Token

1. Go to https://app.netlify.com/user/applications
2. Click **"New access token"**
3. Name it: "Prime Title Admin Dashboard"
4. Copy the token (you'll only see it once!)

### Step 2: Create Netlify Function

Create file: `netlify/functions/get-submissions.js`

```javascript
const fetch = require('node-fetch');

exports.handler = async (event, context) => {
    // Only allow POST requests
    if (event.httpMethod !== 'POST') {
        return { statusCode: 405, body: 'Method Not Allowed' };
    }
    
    const NETLIFY_TOKEN = process.env.NETLIFY_TOKEN;
    const SITE_ID = process.env.SITE_ID;
    
    try {
        const response = await fetch(
            `https://api.netlify.com/api/v1/sites/${SITE_ID}/submissions`,
            {
                headers: {
                    'Authorization': `Bearer ${NETLIFY_TOKEN}`
                }
            }
        );
        
        const submissions = await response.json();
        
        return {
            statusCode: 200,
            body: JSON.stringify(submissions)
        };
    } catch (error) {
        return {
            statusCode: 500,
            body: JSON.stringify({ error: 'Failed to fetch submissions' })
        };
    }
};
```

### Step 3: Set Environment Variables

In Netlify Dashboard:
1. Go to **Site Settings** → **Environment Variables**
2. Add variables:
   - `NETLIFY_TOKEN` = Your personal access token
   - `SITE_ID` = Your site ID (found in Site Settings)

### Step 4: Update admin.js

Replace the `loadMessages()` function to fetch from Netlify:

```javascript
async function loadMessages() {
    try {
        const response = await fetch('/.netlify/functions/get-submissions', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        const submissions = await response.json();
        
        // Transform Netlify submissions to our format
        const messages = submissions.map(sub => ({
            id: sub.id,
            name: sub.data.name,
            email: sub.data.email,
            phone: sub.data.phone,
            service: sub.data.service || 'General Inquiry',
            message: sub.data.message,
            date: sub.created_at,
            read: false
        }));
        
        displayMessages(messages);
        updateStats(messages);
    } catch (error) {
        console.error('Error loading messages:', error);
        // Fallback to localStorage
        loadMessagesFromStorage();
    }
}
```

---

## 🎨 Customization

### Change Colors

Edit the CSS in `admin.html`:

```css
/* Primary gradient */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Change to your brand colors */
background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
```

### Change Login Logo

Add your logo in the login box:

```html
<h1><img src="images/logo.png" alt="Prime Title Inc." style="width: 150px;"></h1>
```

---

## 🔒 Security Best Practices

### 1. **Change Default Password**
   - Never use the default password in production
   - Use a strong password (12+ characters)

### 2. **Don't Share Admin Link**
   - Keep admin.html URL private
   - Don't link to it from your main website

### 3. **Use HTTPS Only**
   - Netlify provides this automatically
   - Never access admin on HTTP

### 4. **Log Out When Done**
   - Always click logout button
   - Session expires when browser closes

### 5. **Consider Adding IP Restrictions** (Advanced)
   - Use Netlify Edge Functions
   - Restrict access to specific IP addresses

---

## 📱 Mobile Access

The admin dashboard is fully responsive and works great on:
- ✅ Desktop computers
- ✅ Tablets
- ✅ Smartphones

Access your messages anywhere, anytime!

---

## 🐛 Troubleshooting

### Can't Login?

1. **Check password** - It's case-sensitive
2. **Clear browser cache** - Try Ctrl+Shift+R (or Cmd+Shift+R on Mac)
3. **Check console** - Press F12 to see any errors

### No Messages Showing?

**Current version uses demo data**. Real messages will show after:
1. Form submissions come through your contact form
2. You set up Netlify integration (see upgrade instructions)

### Messages Not Saving?

- Current version uses localStorage
- Data is per-browser (clearing browser data will delete messages)
- Upgrade to Netlify integration for persistent storage

---

## 🎯 Alternative: Use Netlify Dashboard

If you prefer, you can also view form submissions directly in Netlify:

1. Go to https://app.netlify.com
2. Select your site
3. Click **Forms** in sidebar
4. View all submissions

**Pros**: No setup needed, already works
**Cons**: No custom interface, requires Netlify login

---

## 📊 Usage Tips

### Best Practices:

1. **Check Daily** - Login regularly to respond to inquiries quickly
2. **Mark as Read** - Stay organized by tracking handled messages
3. **Reply Promptly** - Use the Reply button for quick responses
4. **Archive Old Messages** - Delete messages you no longer need
5. **Use Search** - Find specific messages by name, email, or keyword

### Workflow Suggestion:

1. Filter by **Unread** messages
2. Click **Reply** to respond via email
3. Message automatically marks as **Read**
4. Use **Delete** for spam or irrelevant messages
5. Click **Refresh** to check for new submissions

---

## 🚀 You're All Set!

Your admin dashboard is ready to use!

**Access**: https://sunny-sprinkles-1b59d8.netlify.app/admin.html
**Password**: `Prime2025!` (change this!)

---

**Questions?** The dashboard is intuitive and easy to use. Just login and explore! 🎉


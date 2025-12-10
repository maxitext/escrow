/**
 * Admin Dashboard JavaScript
 * Prime Title Inc.
 */

// Configuration
const ADMIN_PASSWORD = 'Prime2025!'; // Change this to your desired password
const STORAGE_KEY = 'primeAdminAuth';
const MESSAGES_KEY = 'primeMessages';

// Current filter
let currentFilter = 'all';

// Check if already logged in
document.addEventListener('DOMContentLoaded', function() {
    const isAuthenticated = sessionStorage.getItem(STORAGE_KEY);
    if (isAuthenticated === 'true') {
        showDashboard();
    }
});

// Login form handler
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('loginError');
    
    if (password === ADMIN_PASSWORD) {
        sessionStorage.setItem(STORAGE_KEY, 'true');
        showDashboard();
    } else {
        errorDiv.textContent = 'Invalid password. Please try again.';
        errorDiv.style.display = 'block';
        document.getElementById('password').value = '';
    }
});

// Show dashboard
function showDashboard() {
    document.getElementById('loginBox').style.display = 'none';
    document.getElementById('dashboard').style.display = 'block';
    loadMessages();
}

// Logout
function logout() {
    sessionStorage.removeItem(STORAGE_KEY);
    document.getElementById('loginBox').style.display = 'block';
    document.getElementById('dashboard').style.display = 'none';
    document.getElementById('password').value = '';
}

// Load messages from localStorage (demo data for now)
function loadMessages() {
    let messages = getMessagesFromStorage();
    
    // If no messages exist, create some demo data
    if (messages.length === 0) {
        messages = [
            {
                id: 1,
                name: 'John Smith',
                email: 'john.smith@example.com',
                phone: '(702) 555-0123',
                service: 'Bond for Deed Contracts',
                message: 'Hello, I am interested in learning more about your Bond for Deed services. I am looking to purchase a property in Las Vegas and would like to discuss financing options. Please contact me at your earliest convenience.',
                date: new Date().toISOString(),
                read: false
            },
            {
                id: 2,
                name: 'Sarah Johnson',
                email: 'sarah.j@example.com',
                phone: '(702) 555-0456',
                service: 'Private Mortgage Servicing',
                message: 'I currently have a private mortgage and need assistance with servicing. Can you help me with payment tracking and escrow management?',
                date: new Date(Date.now() - 86400000).toISOString(),
                read: true
            },
            {
                id: 3,
                name: 'Michael Brown',
                email: 'mbrown@example.com',
                phone: '(702) 555-0789',
                service: 'Lease Purchase Agreements',
                message: 'I\'m interested in a lease purchase agreement for a commercial property. What are your rates and what documents do I need to get started?',
                date: new Date(Date.now() - 172800000).toISOString(),
                read: false
            }
        ];
        saveMessagesToStorage(messages);
    }
    
    displayMessages(messages);
    updateStats(messages);
}

// Get messages from localStorage
function getMessagesFromStorage() {
    const stored = localStorage.getItem(MESSAGES_KEY);
    return stored ? JSON.parse(stored) : [];
}

// Save messages to localStorage
function saveMessagesToStorage(messages) {
    localStorage.setItem(MESSAGES_KEY, JSON.stringify(messages));
}

// Display messages
function displayMessages(messages) {
    const container = document.getElementById('messagesContainer');
    
    // Apply current filter
    let filteredMessages = messages;
    if (currentFilter === 'unread') {
        filteredMessages = messages.filter(m => !m.read);
    } else if (currentFilter === 'read') {
        filteredMessages = messages.filter(m => m.read);
    }
    
    // Apply search filter
    const searchTerm = document.getElementById('searchBox')?.value.toLowerCase();
    if (searchTerm) {
        filteredMessages = filteredMessages.filter(m => 
            m.name.toLowerCase().includes(searchTerm) ||
            m.email.toLowerCase().includes(searchTerm) ||
            m.phone.includes(searchTerm) ||
            m.message.toLowerCase().includes(searchTerm) ||
            m.service.toLowerCase().includes(searchTerm)
        );
    }
    
    // Sort by date (newest first)
    filteredMessages.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    if (filteredMessages.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No messages found</h3>
                <p>There are no messages matching your criteria.</p>
            </div>
        `;
        return;
    }
    
    const messagesHTML = filteredMessages.map(message => `
        <div class="message-card ${!message.read ? 'unread' : ''}" data-id="${message.id}">
            <div class="message-header">
                <div class="message-from">
                    <h3>${message.name}</h3>
                    <div class="contact-info">
                        <span><i class="fas fa-envelope"></i> ${message.email}</span>
                        <span><i class="fas fa-phone"></i> ${message.phone}</span>
                    </div>
                </div>
                <div class="message-date">
                    <i class="fas fa-clock"></i> ${formatDate(message.date)}
                </div>
            </div>
            <div class="message-service">${message.service}</div>
            <div class="message-preview">${message.message}</div>
            <div class="message-actions">
                <button class="btn-action btn-reply" onclick="replyToMessage(${message.id})">
                    <i class="fas fa-reply"></i> Reply via Email
                </button>
                ${!message.read ? `
                    <button class="btn-action btn-mark-read" onclick="markAsRead(${message.id})">
                        <i class="fas fa-check"></i> Mark as Read
                    </button>
                ` : ''}
                <button class="btn-action btn-delete" onclick="deleteMessage(${message.id})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = `<div class="messages-list">${messagesHTML}</div>`;
}

// Update statistics
function updateStats(messages) {
    const total = messages.length;
    const unread = messages.filter(m => !m.read).length;
    
    document.getElementById('totalMessages').textContent = total;
    document.getElementById('unreadMessages').textContent = unread;
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} min${diffMins > 1 ? 's' : ''} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined 
    });
}

// Filter messages
function filterMessages(filter) {
    currentFilter = filter;
    
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.filter-btn').classList.add('active');
    
    const messages = getMessagesFromStorage();
    displayMessages(messages);
}

// Search messages
function searchMessages() {
    const messages = getMessagesFromStorage();
    displayMessages(messages);
}

// Mark message as read
function markAsRead(id) {
    const messages = getMessagesFromStorage();
    const message = messages.find(m => m.id === id);
    if (message) {
        message.read = true;
        saveMessagesToStorage(messages);
        displayMessages(messages);
        updateStats(messages);
    }
}

// Reply to message (opens email client)
function replyToMessage(id) {
    const messages = getMessagesFromStorage();
    const message = messages.find(m => m.id === id);
    if (message) {
        const subject = encodeURIComponent(`Re: ${message.service} Inquiry`);
        const body = encodeURIComponent(`Hi ${message.name},\n\nThank you for contacting Prime Title Inc.\n\n`);
        window.location.href = `mailto:${message.email}?subject=${subject}&body=${body}`;
        
        // Mark as read
        markAsRead(id);
    }
}

// Delete message
function deleteMessage(id) {
    if (confirm('Are you sure you want to delete this message?')) {
        let messages = getMessagesFromStorage();
        messages = messages.filter(m => m.id !== id);
        saveMessagesToStorage(messages);
        displayMessages(messages);
        updateStats(messages);
    }
}

// Refresh messages
function refreshMessages() {
    const btn = event.target.closest('.filter-btn');
    const icon = btn.querySelector('i');
    icon.style.animation = 'spin 1s linear';
    
    setTimeout(() => {
        loadMessages();
        icon.style.animation = '';
    }, 500);
}

// Note: In production, you would integrate with Netlify Forms API
// This is a demo version using localStorage for local storage
// For real Netlify integration, see the documentation in ADMIN_SETUP.md


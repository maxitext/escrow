/**
 * Contact Form Handler for Netlify Forms
 * Prime Title Inc.
 */

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        // For Netlify Forms, we don't intercept the submission
        // The form will submit normally to Netlify's servers
        // This script just provides UI feedback during submission
        
        contactForm.addEventListener('submit', function(e) {
            // Get submit button and show loading state
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            
            if (submitBtn && btnText && btnLoading) {
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-block';
            }
            
            // Let the form submit normally to Netlify
            // User will be redirected to thank-you.html
        });
    }
});


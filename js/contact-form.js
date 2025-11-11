/**
 * Contact Form Handler with AJAX
 * Prime Title Inc.
 */

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form elements
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            const formResponse = document.getElementById('formResponse');
            
            // Clear previous response
            formResponse.innerHTML = '';
            formResponse.className = '';
            
            // Disable submit button
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            
            // Get form data
            const formData = new FormData(contactForm);
            
            // Send AJAX request
            fetch('send-email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable button
                submitBtn.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                
                if (data.success) {
                    // Success message
                    formResponse.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> ${data.message}
                        </div>
                    `;
                    
                    // Reset form
                    contactForm.reset();
                    
                    // Scroll to response
                    formResponse.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    
                    // Hide success message after 5 seconds
                    setTimeout(() => {
                        formResponse.innerHTML = '';
                    }, 5000);
                    
                } else {
                    // Error message
                    let errorHtml = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> ${data.message}
                    `;
                    
                    // Show specific errors if available
                    if (data.data && data.data.errors && data.data.errors.length > 0) {
                        errorHtml += '<ul class="error-list">';
                        data.data.errors.forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                        errorHtml += '</ul>';
                    }
                    
                    errorHtml += '</div>';
                    formResponse.innerHTML = errorHtml;
                    
                    // Scroll to response
                    formResponse.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(error => {
                // Network or other error
                submitBtn.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                
                formResponse.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i> 
                        An error occurred. Please try again or contact us directly at 
                        <a href="mailto:admin@primetitle-inc.com">admin@primetitle-inc.com</a>
                    </div>
                `;
                
                console.error('Form submission error:', error);
            });
        });
    }
});


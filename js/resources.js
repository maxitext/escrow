// =============================================
// FAQ ACCORDION FUNCTIONALITY
// =============================================
document.querySelectorAll('.faq-item').forEach(item => {
    const question = item.querySelector('.faq-question');
    
    question.addEventListener('click', () => {
        // Close other open FAQs
        document.querySelectorAll('.faq-item').forEach(otherItem => {
            if (otherItem !== item && otherItem.classList.contains('active')) {
                otherItem.classList.remove('active');
            }
        });
        
        // Toggle current FAQ
        item.classList.toggle('active');
    });
});

// =============================================
// ACCOUNT LOOKUP FORM
// =============================================
const lookupForm = document.querySelector('.lookup-form');

if (lookupForm) {
    lookupForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const accountNumber = document.getElementById('accountNumber').value;
        const lastName = document.getElementById('lastName').value;
        
        // Here you would typically make an API call to verify credentials
        console.log('Account lookup:', { accountNumber, lastName });
        
        // Show a demo message
        alert('Account lookup feature would connect to your backend system here. This is a demo version.');
        
        // In production, you would redirect to an account dashboard or show an error
    });
}

// =============================================
// SMOOTH SCROLL TO SECTIONS FROM HASH
// =============================================
window.addEventListener('load', () => {
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            setTimeout(() => {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }, 100);
        }
    }
});


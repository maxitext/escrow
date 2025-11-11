// =============================================
// MOBILE NAVIGATION TOGGLE
// =============================================
const mobileToggle = document.getElementById('mobileToggle');
const navMenu = document.getElementById('navMenu');

if (mobileToggle) {
    mobileToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        mobileToggle.classList.toggle('active');
    });
}

// =============================================
// STICKY HEADER ON SCROLL
// =============================================
window.addEventListener('scroll', () => {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// =============================================
// SMOOTH SCROLLING FOR ANCHOR LINKS
// =============================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '#services' && href !== '#resources') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// =============================================
// ANIMATED COUNTER FOR STATS
// =============================================
const animateCounter = (element, target, duration = 2000) => {
    let start = 0;
    const increment = target / (duration / 16);
    
    const updateCounter = () => {
        start += increment;
        if (start < target) {
            element.textContent = Math.floor(start).toLocaleString();
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target.toLocaleString() + (element.closest('.stat-card').querySelector('.stat-label').textContent.includes('Rate') ? '%' : '+');
        }
    };
    
    updateCounter();
};

// Intersection Observer for counter animation
const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px'
};

const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            const target = parseInt(entry.target.getAttribute('data-target'));
            animateCounter(entry.target, target);
            entry.target.classList.add('animated');
        }
    });
}, observerOptions);

// Observe all stat numbers
document.querySelectorAll('.stat-number').forEach(stat => {
    statObserver.observe(stat);
});

// =============================================
// FADE IN ANIMATION ON SCROLL
// =============================================
const fadeInElements = document.querySelectorAll('.feature-card, .service-card, .benefit-card, .testimonial-card');

const fadeInObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
});

fadeInElements.forEach(element => {
    element.style.opacity = '0';
    element.style.transform = 'translateY(30px)';
    element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    fadeInObserver.observe(element);
});

// =============================================
// FORM VALIDATION (for contact forms)
// =============================================
const validateForm = (form) => {
    let isValid = true;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[\d\s\-\(\)]+$/;
    
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    
    inputs.forEach(input => {
        const errorElement = input.nextElementSibling;
        
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.textContent = 'This field is required';
            }
        } else if (input.type === 'email' && !emailRegex.test(input.value)) {
            isValid = false;
            input.classList.add('error');
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.textContent = 'Please enter a valid email address';
            }
        } else if (input.type === 'tel' && !phoneRegex.test(input.value)) {
            isValid = false;
            input.classList.add('error');
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.textContent = 'Please enter a valid phone number';
            }
        } else {
            input.classList.remove('error');
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.textContent = '';
            }
        }
    });
    
    return isValid;
};

// Add form validation to all forms with class 'contact-form'
document.querySelectorAll('.contact-form').forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (validateForm(form)) {
            // Form is valid, you can submit it here
            console.log('Form is valid, ready to submit');
            // form.submit(); // Uncomment when ready to actually submit
            
            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'success-message';
            successMessage.textContent = 'Thank you! Your message has been sent successfully.';
            form.appendChild(successMessage);
            
            // Reset form
            setTimeout(() => {
                form.reset();
                successMessage.remove();
            }, 3000);
        }
    });
    
    // Remove error on input
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', () => {
            input.classList.remove('error');
            const errorElement = input.nextElementSibling;
            if (errorElement && errorElement.classList.contains('error-message')) {
                errorElement.textContent = '';
            }
        });
    });
});

// =============================================
// DROPDOWN MENU FOR MOBILE
// =============================================
document.querySelectorAll('.dropdown > a').forEach(dropdown => {
    dropdown.addEventListener('click', (e) => {
        if (window.innerWidth <= 992) {
            e.preventDefault();
            const parent = dropdown.parentElement;
            parent.classList.toggle('active');
        }
    });
});

// =============================================
// CLOSE MOBILE MENU WHEN CLICKING OUTSIDE
// =============================================
document.addEventListener('click', (e) => {
    if (navMenu && !navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
        navMenu.classList.remove('active');
        if (mobileToggle) {
            mobileToggle.classList.remove('active');
        }
    }
});

// =============================================
// ACTIVE PAGE HIGHLIGHT IN NAVIGATION
// =============================================
const currentPage = window.location.pathname.split('/').pop() || 'index.html';
document.querySelectorAll('.nav-list a').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
    }
});

// =============================================
// LAZY LOADING FOR IMAGES
// =============================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// =============================================
// SCROLL TO TOP BUTTON
// =============================================
const createScrollToTopButton = () => {
    const button = document.createElement('button');
    button.innerHTML = '<i class="fas fa-arrow-up"></i>';
    button.className = 'scroll-to-top';
    button.setAttribute('aria-label', 'Scroll to top');
    document.body.appendChild(button);
    
    button.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            button.classList.add('visible');
        } else {
            button.classList.remove('visible');
        }
    });
};

// Initialize scroll to top button
createScrollToTopButton();

// Add CSS for scroll to top button
const style = document.createElement('style');
style.textContent = `
    .scroll-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .scroll-to-top.visible {
        opacity: 1;
        visibility: visible;
    }
    
    .scroll-to-top:hover {
        background-color: var(--primary-dark);
        transform: translateY(-5px);
    }
`;
document.head.appendChild(style);


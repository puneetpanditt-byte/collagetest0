// JavaScript for SDGD College Website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize slider
    initSlider();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize scroll animations
    initScrollAnimations();
});

// Image Slider
function initSlider() {
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    
    if (slides.length === 0) return;
    
    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Auto-advance slides every 5 seconds
    setInterval(nextSlide, 5000);
}

// Mobile Menu Toggle
function initMobileMenu() {
    const navToggle = document.createElement('button');
    navToggle.className = 'nav-toggle';
    navToggle.innerHTML = '<i class="fas fa-bars"></i>';
    navToggle.style.cssText = `
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 15px;
    `;
    
    const nav = document.querySelector('.main-nav .container');
    if (nav) {
        nav.insertBefore(navToggle, nav.firstChild);
    }
    
    navToggle.addEventListener('click', function() {
        const navMenu = document.querySelector('.nav-menu');
        navMenu.classList.toggle('active');
    });
    
    // Add CSS for mobile menu
    const style = document.createElement('style');
    style.textContent = `
        @media (max-width: 768px) {
            .nav-toggle {
                display: block !important;
            }
            
            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #2c3e50;
                z-index: 1000;
            }
            
            .nav-menu.active {
                display: flex;
            }
            
            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                display: none;
            }
            
            .dropdown.active .dropdown-menu {
                display: block;
            }
        }
    `;
    document.head.appendChild(style);
}

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    // Observe sections
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });
}

// Smooth Scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showError(input, 'This field is required');
            isValid = false;
        } else {
            clearError(input);
        }
        
        // Email validation
        if (input.type === 'email' && input.value) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(input.value)) {
                showError(input, 'Please enter a valid email address');
                isValid = false;
            }
        }
        
        // Phone validation
        if (input.type === 'tel' && input.value) {
            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(input.value.replace(/[^0-9]/g, ''))) {
                showError(input, 'Please enter a valid 10-digit phone number');
                isValid = false;
            }
        }
    });
    
    return isValid;
}

function showError(input, message) {
    clearError(input);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
    `;
    
    input.parentNode.appendChild(errorDiv);
    input.style.borderColor = '#e74c3c';
}

function clearError(input) {
    const errorDiv = input.parentNode.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
    input.style.borderColor = '';
}

// AJAX Helper
function ajaxRequest(url, method, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch (e) {
                    callback({success: false, message: 'Invalid response format'});
                }
            } else {
                callback({success: false, message: 'Request failed'});
            }
        }
    };
    
    xhr.send(data);
}

// Print Function
function printPage() {
    window.print();
}

// Back to Top Button
function initBackToTop() {
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #3498db;
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        z-index: 1000;
        font-size: 18px;
        transition: all 0.3s;
    `;
    
    document.body.appendChild(backToTopBtn);
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Initialize back to top button
initBackToTop();

// Search Functionality
function initSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 3) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    });
}

function performSearch(query) {
    // This would typically make an AJAX call to the server
    // For now, we'll just show a loading state
    const searchResults = document.getElementById('searchResults');
    if (searchResults) {
        searchResults.innerHTML = '<div class="spinner"></div>';
        searchResults.style.display = 'block';
        
        // Simulate search delay
        setTimeout(function() {
            searchResults.innerHTML = '<p>No results found for: ' + query + '</p>';
        }, 1000);
    }
}

// Utility Functions
function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString(undefined, options);
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}

function confirmAction(message) {
    return confirm(message);
}

// Initialize search when DOM is ready
document.addEventListener('DOMContentLoaded', initSearch);

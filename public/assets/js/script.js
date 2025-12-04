// Preloader
window.addEventListener('load', function() {
    const preloader = document.getElementById('preloader');
    setTimeout(function() {
        preloader.style.opacity = '0';
        setTimeout(function() {
            preloader.style.display = 'none';
        }, 500);
    }, 500);
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.style.padding = '0.5rem 0';
        navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    } else {
        navbar.style.padding = '1rem 0';
        navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Service cards animation on scroll
const serviceCards = document.querySelectorAll('.service-card');
const productCards = document.querySelectorAll('.product-card');

const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Animate elements on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.service-card, .product-card, .stat-box');
    
    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        observer.observe(element);
    });
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    animateOnScroll();
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Form submission handler (example)
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
        submitBtn.disabled = true;
        
        // Simulate form submission (replace with actual AJAX call)
        setTimeout(() => {
            alert('Terima kasih! Pesan Anda telah dikirim. Kami akan menghubungi Anda segera.');
            form.reset();
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 1500);
    });
});

// Add active class to current page in navigation
function setActiveNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        
        // Check if link href matches current path
        if (linkPath === currentPath || 
            (currentPath.includes(linkPath) && linkPath !== '/')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Run on page load
document.addEventListener('DOMContentLoaded', setActiveNavItem);
// Additional JavaScript Features

// Back to Top Button
const backToTopBtn = document.getElementById('backToTop');

window.addEventListener('scroll', function() {
    if (window.scrollY > 300) {
        backToTopBtn.classList.add('show');
    } else {
        backToTopBtn.classList.remove('show');
    }
});

backToTopBtn.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Wishlist Functionality
document.querySelectorAll('.btn-wishlist').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const icon = this.querySelector('i');
        
        if (this.classList.contains('active')) {
            // Remove from wishlist
            this.classList.remove('active');
            icon.classList.remove('fas');
            icon.classList.add('far');
            showToast('Produk dihapus dari wishlist', 'info');
        } else {
            // Add to wishlist
            this.classList.add('active');
            icon.classList.remove('far');
            icon.classList.add('fas');
            showToast('Produk ditambahkan ke wishlist', 'success');
        }
        
        // Save to localStorage
        updateWishlist(productId);
    });
});

function updateWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem('cendratama_wishlist')) || [];
    
    if (wishlist.includes(productId)) {
        wishlist = wishlist.filter(id => id !== productId);
    } else {
        wishlist.push(productId);
    }
    
    localStorage.setItem('cendratama_wishlist', JSON.stringify(wishlist));
    updateWishlistCount();
}

function updateWishlistCount() {
    const wishlist = JSON.parse(localStorage.getItem('cendratama_wishlist')) || [];
    const wishlistCount = document.getElementById('wishlistCount');
    
    if (wishlistCount) {
        wishlistCount.textContent = wishlist.length;
        wishlistCount.style.display = wishlist.length > 0 ? 'block' : 'none';
    }
}

// Toast Notification
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 3000
    });
    
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Newsletter Form
const newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = this.querySelector('input[type="email"]').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            showToast('Terima kasih! Anda telah berlangganan newsletter kami.', 'success');
            this.reset();
        }, 1500);
    });
}

// Price Calculator
function initializePriceCalculator() {
    const calculator = document.getElementById('priceCalculator');
    if (!calculator) return;
    
    const serviceSelect = calculator.querySelector('#serviceType');
    const featureSelect = calculator.querySelector('#features');
    const durationInput = calculator.querySelector('#duration');
    const resultPrice = calculator.querySelector('#resultPrice');
    
    const prices = {
        'website': {
            basic: 5000000,
            standard: 10000000,
            premium: 20000000
        },
        'cctv': {
            basic: 3000000,
            standard: 6000000,
            premium: 12000000
        },
        'it-support': {
            basic: 1000000,
            standard: 2000000,
            premium: 4000000
        }
    };
    
    function calculatePrice() {
        const service = serviceSelect.value;
        const features = featureSelect.value;
        const duration = parseInt(durationInput.value) || 1;
        
        if (service && features && prices[service]) {
            let basePrice = prices[service][features] || prices[service].basic;
            let total = basePrice * duration;
            
            // Format to Rupiah
            resultPrice.textContent = formatRupiah(total);
        }
    }
    
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }
    
    // Event listeners
    serviceSelect.addEventListener('change', calculatePrice);
    featureSelect.addEventListener('change', calculatePrice);
    durationInput.addEventListener('input', calculatePrice);
    
    // Initial calculation
    calculatePrice();
}

// Chatbot
function initializeChatbot() {
    const chatbotToggle = document.querySelector('.chatbot-toggle');
    const chatbotContainer = document.querySelector('.chatbot-container');
    const chatbotClose = document.querySelector('.chatbot-close');
    const chatbotInput = document.querySelector('.chatbot-input input');
    const chatbotSend = document.querySelector('.chatbot-input button');
    const chatbotBody = document.querySelector('.chatbot-body');
    
    if (!chatbotToggle) return;
    
    const responses = {
        'harga': 'Untuk informasi harga, silakan kunjungi halaman layanan atau produk yang Anda minati. Anda juga bisa menggunakan kalkulator harga kami.',
        'website': 'Kami menyediakan layanan pembuatan website custom dengan harga mulai dari Rp 5.000.000. Silakan hubungi kami untuk konsultasi gratis.',
        'cctv': 'Pengadaan CCTV dimulai dari Rp 3.000.000 untuk paket dasar. Termasuk instalasi dan konfigurasi.',
        'kontak': 'Anda bisa menghubungi kami di: Telepon: 081393484770, Email: info@cendratama.com, WhatsApp: 081393484770',
        'jam': 'Kami buka Senin - Jumat, 08:00 - 17:00 WIB. Support tersedia 24/7 untuk masalah darurat.',
        'default': 'Maaf, saya tidak mengerti pertanyaan Anda. Silakan tanyakan tentang: harga, website, cctv, kontak, atau jam operasional.'
    };
    
    chatbotToggle.addEventListener('click', function() {
        chatbotContainer.classList.toggle('show');
    });
    
    chatbotClose.addEventListener('click', function() {
        chatbotContainer.classList.remove('show');
    });
    
    chatbotSend.addEventListener('click', sendMessage);
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
    
    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        chatbotInput.value = '';
        
        // Simulate typing delay
        setTimeout(() => {
            const response = getBotResponse(message);
            addMessage(response, 'bot');
        }, 1000);
    }
    
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}`;
        messageDiv.textContent = text;
        chatbotBody.appendChild(messageDiv);
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }
    
    function getBotResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        for (const keyword in responses) {
            if (lowerMessage.includes(keyword)) {
                return responses[keyword];
            }
        }
        
        return responses['default'];
    }
    
    // Initial bot message
    setTimeout(() => {
        if (!chatbotContainer.classList.contains('show')) {
            addMessage('Halo! Saya Chatbot CENDRATAMA. Ada yang bisa saya bantu?', 'bot');
        }
    }, 2000);
}

// Form Validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

// Product Filter
function initializeProductFilter() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productItems = document.querySelectorAll('.product-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            productItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Service Tabs
function initializeServiceTabs() {
    const tabButtons = document.querySelectorAll('.service-tab');
    const tabContents = document.querySelectorAll('.service-tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Update active tab button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show target tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === targetTab) {
                    content.classList.add('active');
                }
            });
        });
    });
}

// Interactive Pricing Plans
function initializePricingPlans() {
    const planToggles = document.querySelectorAll('.plan-toggle');
    const monthlyPrices = document.querySelectorAll('.price-monthly');
    const yearlyPrices = document.querySelectorAll('.price-yearly');
    const billingTexts = document.querySelectorAll('.billing-text');
    
    planToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const isYearly = this.checked;
            
            monthlyPrices.forEach(price => {
                price.style.display = isYearly ? 'none' : 'inline';
            });
            
            yearlyPrices.forEach(price => {
                price.style.display = isYearly ? 'inline' : 'none';
            });
            
            billingTexts.forEach(text => {
                text.textContent = isYearly ? 'per tahun' : 'per bulan';
            });
        });
    });
}

// Statistics Counter
function initializeCounters() {
    const counters = document.querySelectorAll('.counter');
    const speed = 200;
    
    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target;
            }
        };
        
        // Start counter when element is in viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCount();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });
}

// Image Lazy Loading
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Initialize all features when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize existing features
    animateOnScroll();
    setActiveNavItem();
    updateWishlistCount();
    
    // Initialize new features
    initializePriceCalculator();
    initializeChatbot();
    initializeFormValidation();
    initializeProductFilter();
    initializeServiceTabs();
    initializePricingPlans();
    initializeCounters();
    initializeLazyLoading();
    
    // Add CSS for new components
    addDynamicStyles();
});

// Add dynamic CSS styles
function addDynamicStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .toast-container {
            z-index: 9999;
        }
        
        .counter {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--ungu);
        }
        
        img[data-src] {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        img.loaded {
            opacity: 1;
        }
        
        .service-tab-content {
            display: none;
        }
        
        .service-tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .plan-toggle {
            position: relative;
            width: 60px;
            height: 30px;
        }
        
        .plan-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .plan-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .plan-toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        .plan-toggle input:checked + .plan-toggle-slider {
            background-color: var(--hijau);
        }
        
        .plan-toggle input:checked + .plan-toggle-slider:before {
            transform: translateX(30px);
        }
    `;
    document.head.appendChild(style);
}
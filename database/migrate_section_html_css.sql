-- Migration to add HTML, CSS, and JS fields to page_sections table
-- Run this migration to enable CodeMirror editing for sections

-- Add new columns for HTML, CSS, and JavaScript
ALTER TABLE `page_sections` 
ADD COLUMN `section_html` longtext AFTER `content`,
ADD COLUMN `section_css` longtext AFTER `section_html`,
ADD COLUMN `section_js` longtext AFTER `section_css`,
ADD COLUMN `layout_template` varchar(100) DEFAULT 'custom' AFTER `section_js`;

-- Create section_layout_templates table for predefined templates
CREATE TABLE IF NOT EXISTS `section_layout_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `category` varchar(50) DEFAULT 'general',
    `html_template` longtext,
    `css_template` longtext,
    `js_template` longtext,
    `variables` json, -- Template variables for customization
    `thumbnail` varchar(255),
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_category` (`category`),
    KEY `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample section templates
INSERT INTO `section_layout_templates` (`name`, `slug`, `description`, `category`, `html_template`, `css_template`, `js_template`, `variables`) VALUES
('Hero Section', 'hero-section', 'Full-width hero section with background image and call-to-action', 'hero', 
'<section class="hero-section">
    <div class="hero-background"></div>
    <div class="hero-content">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="hero-title">{{ title }}</h1>
                    <p class="hero-subtitle">{{ subtitle }}</p>
                    <div class="hero-actions">
                        <a href="{{ button_link }}" class="btn btn-primary btn-lg">{{ button_text }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>',
'.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, {{ primary_color }} 0%, {{ secondary_color }} 100%);
    color: white;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url("{{ background_image }}");
    background-size: cover;
    background-position: center;
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-actions .btn {
    padding: 12px 30px;
    font-size: 1.1rem;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.hero-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    .hero-subtitle {
        font-size: 1.1rem;
    }
}',
'// Hero section animations
document.addEventListener("DOMContentLoaded", function() {
    const heroSection = document.querySelector(".hero-section");
    const heroTitle = document.querySelector(".hero-title");
    const heroSubtitle = document.querySelector(".hero-subtitle");
    const heroActions = document.querySelector(".hero-actions");
    
    if (heroSection) {
        // Fade in animation
        heroTitle.style.opacity = "0";
        heroTitle.style.transform = "translateY(30px)";
        heroSubtitle.style.opacity = "0";
        heroSubtitle.style.transform = "translateY(30px)";
        heroActions.style.opacity = "0";
        heroActions.style.transform = "translateY(30px)";
        
        setTimeout(() => {
            heroTitle.style.transition = "all 0.8s ease";
            heroTitle.style.opacity = "1";
            heroTitle.style.transform = "translateY(0)";
        }, 200);
        
        setTimeout(() => {
            heroSubtitle.style.transition = "all 0.8s ease";
            heroSubtitle.style.opacity = "1";
            heroSubtitle.style.transform = "translateY(0)";
        }, 400);
        
        setTimeout(() => {
            heroActions.style.transition = "all 0.8s ease";
            heroActions.style.opacity = "1";
            heroActions.style.transform = "translateY(0)";
        }, 600);
    }
});',
'{"title": "Hero Title", "subtitle": "Hero Subtitle", "button_text": "Get Started", "button_link": "#", "primary_color": "#007bff", "secondary_color": "#6610f2", "background_image": ""}'),

('Services Grid', 'services-grid', 'Responsive grid layout for showcasing services', 'services',
'<section class="services-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <h2 class="section-title">{{ title }}</h2>
                <p class="section-subtitle">{{ subtitle }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-{{ service1_icon }}"></i>
                    </div>
                    <h4>{{ service1_title }}</h4>
                    <p>{{ service1_description }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-{{ service2_icon }}"></i>
                    </div>
                    <h4>{{ service2_title }}</h4>
                    <p>{{ service2_description }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-{{ service3_icon }}"></i>
                    </div>
                    <h4>{{ service3_title }}</h4>
                    <p>{{ service3_description }}</p>
                </div>
            </div>
        </div>
    </div>
</section>',
'.services-section {
    background-color: {{ background_color }};
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: {{ title_color }};
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: {{ subtitle_color }};
    max-width: 600px;
    margin: 0 auto;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.service-icon {
    width: 80px;
    height: 80px;
    background: {{ accent_color }};
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.service-icon i {
    font-size: 2rem;
    color: white;
}

.service-card h4 {
    color: {{ title_color }};
    margin-bottom: 1rem;
    font-weight: 600;
}

.service-card p {
    color: {{ text_color }};
    line-height: 1.6;
}',
'// Services animation on scroll
document.addEventListener("DOMContentLoaded", function() {
    const serviceCards = document.querySelectorAll(".service-card");
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, observerOptions);
    
    serviceCards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});',
'{"title": "Our Services", "subtitle": "We provide comprehensive solutions for your business needs", "service1_icon": "laptop-code", "service1_title": "Web Development", "service1_description": "Custom websites and web applications", "service2_icon": "mobile-alt", "service2_title": "Mobile Apps", "service2_description": "iOS and Android mobile applications", "service3_icon": "chart-line", "service3_title": "Digital Marketing", "service3_description": "SEO, social media, and online advertising", "background_color": "#f8f9fa", "title_color": "#333", "subtitle_color": "#666", "text_color": "#555", "accent_color": "#007bff"}'),

('Contact Form', 'contact-form', 'Professional contact form with validation', 'contact',
'<section class="contact-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form-wrapper">
                    <h2 class="text-center mb-4">{{ title }}</h2>
                    <p class="text-center text-muted mb-5">{{ subtitle }}</p>
                    
                    <form class="contact-form" id="contactForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">{{ button_text }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>',
'.contact-section {
    background-color: {{ background_color }};
}

.contact-form-wrapper {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.contact-form .form-label {
    font-weight: 600;
    color: {{ label_color }};
    margin-bottom: 0.5rem;
}

.contact-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.contact-form .form-control:focus {
    border-color: {{ accent_color }};
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.contact-form .btn {
    padding: 12px 40px;
    font-size: 1.1rem;
    border-radius: 50px;
    background-color: {{ accent_color }};
    border-color: {{ accent_color }};
    transition: all 0.3s ease;
}

.contact-form .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.form-message {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: none;
}

.form-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.form-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}',
'// Contact form handling
document.addEventListener("DOMContentLoaded", function() {
    const contactForm = document.getElementById("contactForm");
    
    if (contactForm) {
        contactForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData);
            
            // Basic validation
            if (!data.name || !data.email || !data.message) {
                showMessage("Please fill in all required fields.", "error");
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(data.email)) {
                showMessage("Please enter a valid email address.", "error");
                return;
            }
            
            // Simulate form submission
            const submitBtn = contactForm.querySelector("button[type=submit]");
            const originalText = submitBtn.textContent;
            submitBtn.textContent = "Sending...";
            submitBtn.disabled = true;
            
            setTimeout(() => {
                showMessage("Thank you! Your message has been sent successfully.", "success");
                contactForm.reset();
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }
    
    function showMessage(text, type) {
        // Remove existing messages
        const existingMessage = document.querySelector(".form-message");
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message
        const message = document.createElement("div");
        message.className = `form-message ${type}`;
        message.textContent = text;
        
        // Insert message before form
        contactForm.parentNode.insertBefore(message, contactForm);
        message.style.display = "block";
        
        // Auto-hide success messages
        if (type === "success") {
            setTimeout(() => {
                message.style.display = "none";
            }, 5000);
        }
    }
});',
'{"title": "Get In Touch", "subtitle": "We would love to hear from you. Send us a message and we will respond as soon as possible.", "button_text": "Send Message", "background_color": "#f8f9fa", "label_color": "#333", "accent_color": "#007bff"}');

-- Update existing sections to have default layout_template
UPDATE `page_sections` SET `layout_template` = 'custom' WHERE `layout_template` IS NULL;

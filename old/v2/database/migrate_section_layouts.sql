-- Migration for Section-Based Layout System
-- This adds layout capabilities to individual page sections

-- Add layout fields to pages table
ALTER TABLE pages 
ADD COLUMN layout_id INT NULL AFTER template,
ADD COLUMN use_sections BOOLEAN DEFAULT 0 AFTER layout_id,
ADD FOREIGN KEY (layout_id) REFERENCES layouts(id) ON DELETE SET NULL;

-- Add layout fields to page_sections table
ALTER TABLE page_sections 
ADD COLUMN section_html TEXT AFTER content,
ADD COLUMN section_css TEXT AFTER section_html,
ADD COLUMN section_js TEXT AFTER section_css,
ADD COLUMN layout_template VARCHAR(50) DEFAULT 'default' AFTER section_js;

-- Create section_layout_templates table for predefined section layouts
CREATE TABLE IF NOT EXISTS section_layout_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    category VARCHAR(50) DEFAULT 'general',
    html_template TEXT NOT NULL,
    css_template TEXT,
    js_template TEXT,
    thumbnail VARCHAR(255),
    variables JSON, -- Available template variables
    is_system BOOLEAN DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default section layout templates
INSERT INTO section_layout_templates (name, slug, description, category, html_template, css_template, js_template, variables, is_system) VALUES

-- Hero Section Template
('Hero Section', 'hero-section', 'Full-width hero section with background image and call-to-action', 'hero', 
'<section class="hero-section" style="background-image: url(\'{{ background_image|/uploads/default-hero.jpg }}\');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container">
            <div class="hero-text">
                <h1 class="hero-title">{{ title|Welcome to Our Website }}</h1>
                <p class="hero-subtitle">{{ subtitle|Discover amazing content and services }}</p>
                @if(button_text)
                    <a href="{{ button_link|# }}" class="btn btn-hero">{{ button_text|Get Started }}</a>
                @endif
            </div>
        </div>
    </div>
</section>',
'.hero-section {
    position: relative;
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    color: white;
}
.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}
.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
}
.hero-title {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
}
.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}
.btn-hero {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: transform 0.3s ease;
}
.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
}',
'// Hero section animations
document.addEventListener("DOMContentLoaded", function() {
    const heroTitle = document.querySelector(".hero-title");
    const heroSubtitle = document.querySelector(".hero-subtitle");
    const heroBtn = document.querySelector(".btn-hero");
    
    if (heroTitle) {
        heroTitle.style.opacity = "0";
        heroTitle.style.transform = "translateY(30px)";
        setTimeout(() => {
            heroTitle.style.transition = "all 0.8s ease";
            heroTitle.style.opacity = "1";
            heroTitle.style.transform = "translateY(0)";
        }, 200);
    }
    
    if (heroSubtitle) {
        heroSubtitle.style.opacity = "0";
        heroSubtitle.style.transform = "translateY(30px)";
        setTimeout(() => {
            heroSubtitle.style.transition = "all 0.8s ease";
            heroSubtitle.style.opacity = "1";
            heroSubtitle.style.transform = "translateY(0)";
        }, 400);
    }
    
    if (heroBtn) {
        heroBtn.style.opacity = "0";
        heroBtn.style.transform = "translateY(30px)";
        setTimeout(() => {
            heroBtn.style.transition = "all 0.8s ease";
            heroBtn.style.opacity = "1";
            heroBtn.style.transform = "translateY(0)";
        }, 600);
    }
});',
'{"title": "Hero Title", "subtitle": "Hero Subtitle", "background_image": "Background Image URL", "button_text": "Button Text", "button_link": "Button Link"}', 1),

-- Services Section Template
('Services Grid', 'services-grid', 'Responsive services grid with icons and descriptions', 'services',
'<section class="services-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">{{ title|Our Services }}</h2>
            <p class="section-subtitle">{{ subtitle|What we offer to help you succeed }}</p>
        </div>
        <div class="services-grid">
            @if(services)
                @foreach(services as service)
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-{{ service.icon|cog }}"></i>
                        </div>
                        <h3 class="service-title">{{ service.title|Service Title }}</h3>
                        <p class="service-description">{{ service.description|Service description goes here }}</p>
                    </div>
                @endforeach
            @else
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-cog"></i></div>
                    <h3 class="service-title">Web Development</h3>
                    <p class="service-description">Professional web development services</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-paint-brush"></i></div>
                    <h3 class="service-title">Design</h3>
                    <p class="service-description">Creative design solutions</p>
                </div>
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-rocket"></i></div>
                    <h3 class="service-title">Marketing</h3>
                    <p class="service-description">Digital marketing strategies</p>
                </div>
            @endif
        </div>
    </div>
</section>',
'.services-section {
    padding: 80px 0;
    background: #f8f9fa;
}
.section-header {
    margin-bottom: 60px;
}
.section-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 1rem;
}
.section-subtitle {
    font-size: 1.1rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 40px;
}
.service-card {
    background: white;
    padding: 40px 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.service-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 2rem;
}
.service-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}
.service-description {
    color: #666;
    line-height: 1.6;
}',
'// Services section animations
document.addEventListener("DOMContentLoaded", function() {
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
    
    document.querySelectorAll(".service-card").forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});',
'{"title": "Section Title", "subtitle": "Section Subtitle", "services": "Array of service objects with icon, title, description"}', 1),

-- Gallery Section Template
('Image Gallery', 'image-gallery', 'Responsive image gallery with lightbox', 'gallery',
'<section class="gallery-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">{{ title|Our Gallery }}</h2>
            <p class="section-subtitle">{{ subtitle|Take a look at our amazing work }}</p>
        </div>
        <div class="gallery-grid">
            @if(images)
                @foreach(images as image)
                    <div class="gallery-item" data-src="{{ image.url }}">
                        <img src="{{ image.url }}" alt="{{ image.alt|Gallery Image }}" loading="lazy">
                        <div class="gallery-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="gallery-item" data-src="/uploads/gallery/sample1.jpg">
                    <img src="/uploads/gallery/sample1.jpg" alt="Gallery Image" loading="lazy">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i></div>
                </div>
                <div class="gallery-item" data-src="/uploads/gallery/sample2.jpg">
                    <img src="/uploads/gallery/sample2.jpg" alt="Gallery Image" loading="lazy">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i></div>
                </div>
                <div class="gallery-item" data-src="/uploads/gallery/sample3.jpg">
                    <img src="/uploads/gallery/sample3.jpg" alt="Gallery Image" loading="lazy">
                    <div class="gallery-overlay"><i class="fas fa-search-plus"></i></div>
                </div>
            @endif
        </div>
    </div>
</section>',
'.gallery-section {
    padding: 80px 0;
}
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 40px;
}
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    cursor: pointer;
    aspect-ratio: 4/3;
}
.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: white;
    font-size: 2rem;
}
.gallery-item:hover img {
    transform: scale(1.1);
}
.gallery-item:hover .gallery-overlay {
    opacity: 1;
}',
'// Gallery lightbox functionality
document.addEventListener("DOMContentLoaded", function() {
    const galleryItems = document.querySelectorAll(".gallery-item");
    
    galleryItems.forEach(item => {
        item.addEventListener("click", function() {
            const src = this.dataset.src;
            showLightbox(src);
        });
    });
    
    function showLightbox(src) {
        const lightbox = document.createElement("div");
        lightbox.className = "gallery-lightbox";
        lightbox.innerHTML = `
            <div class="lightbox-overlay"></div>
            <div class="lightbox-content">
                <img src="${src}" alt="Gallery Image">
                <button class="lightbox-close">&times;</button>
            </div>
        `;
        
        const style = document.createElement("style");
        style.textContent = `
            .gallery-lightbox {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .lightbox-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.9);
            }
            .lightbox-content {
                position: relative;
                max-width: 90%;
                max-height: 90%;
            }
            .lightbox-content img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }
            .lightbox-close {
                position: absolute;
                top: -40px;
                right: 0;
                background: none;
                border: none;
                color: white;
                font-size: 2rem;
                cursor: pointer;
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(lightbox);
        
        lightbox.addEventListener("click", function(e) {
            if (e.target === lightbox || e.target.className === "lightbox-overlay" || e.target.className === "lightbox-close") {
                document.body.removeChild(lightbox);
                document.head.removeChild(style);
            }
        });
    }
});',
'{"title": "Gallery Title", "subtitle": "Gallery Subtitle", "images": "Array of image objects with url and alt text"}', 1),

-- Contact Section Template
('Contact Form', 'contact-form', 'Contact form with company information', 'contact',
'<section class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-info">
                    <h2 class="section-title">{{ title|Get In Touch }}</h2>
                    <p class="section-subtitle">{{ subtitle|We would love to hear from you }}</p>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Address</h4>
                            <p>{{ address|123 Main Street, City, Country }}</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Phone</h4>
                            <p>{{ phone|+1 234 567 8900 }}</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>{{ email|info@example.com }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <form class="contact-form" action="{{ form_action|/contact }}" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Subject" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-contact">{{ button_text|Send Message }}</button>
                </form>
            </div>
        </div>
    </div>
</section>',
'.contact-section {
    padding: 80px 0;
    background: #f8f9fa;
}
.contact-info {
    padding-right: 30px;
}
.contact-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 30px;
}
.contact-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 20px;
    flex-shrink: 0;
}
.contact-details h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}
.contact-details p {
    color: #666;
    margin: 0;
}
.contact-form {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.form-group {
    margin-bottom: 20px;
}
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
}
.btn-contact {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.3s ease;
}
.btn-contact:hover {
    transform: translateY(-2px);
}',
'// Contact form validation and submission
document.addEventListener("DOMContentLoaded", function() {
    const contactForm = document.querySelector(".contact-form");
    
    if (contactForm) {
        contactForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Basic validation
            const name = this.querySelector("input[name=name]").value.trim();
            const email = this.querySelector("input[name=email]").value.trim();
            const subject = this.querySelector("input[name=subject]").value.trim();
            const message = this.querySelector("textarea[name=message]").value.trim();
            
            if (!name || !email || !subject || !message) {
                alert("Please fill in all fields");
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address");
                return;
            }
            
            // Submit form (you can customize this)
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = this.querySelector(".btn-contact");
            const originalText = submitBtn.textContent;
            submitBtn.textContent = "Sending...";
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual endpoint)
            setTimeout(() => {
                alert("Message sent successfully!");
                this.reset();
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }
});',
'{"title": "Contact Title", "subtitle": "Contact Subtitle", "address": "Company Address", "phone": "Phone Number", "email": "Email Address", "form_action": "Form Action URL", "button_text": "Button Text"}', 1);

-- Add indexes for better performance
CREATE INDEX idx_section_layout_templates_category ON section_layout_templates(category, is_active);
CREATE INDEX idx_section_layout_templates_slug ON section_layout_templates(slug);
CREATE INDEX idx_page_sections_layout ON page_sections(layout_template, is_active);

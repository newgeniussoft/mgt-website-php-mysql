-- Migration for Fully Editable Templates System
-- This migration enhances the CMS to make all page templates editable via CodeMirror

-- Create page_templates table for storing editable page templates
CREATE TABLE IF NOT EXISTS `page_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `template_type` enum('page', 'layout', 'section') DEFAULT 'page',
    `html_template` longtext NOT NULL,
    `css_styles` longtext,
    `js_scripts` longtext,
    `variables` json, -- Template variables and their default values
    `thumbnail` varchar(255),
    `is_active` tinyint(1) DEFAULT 1,
    `is_system` tinyint(1) DEFAULT 0,
    `created_by` int(11),
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_slug` (`slug`),
    KEY `idx_template_type` (`template_type`),
    KEY `idx_active` (`is_active`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add template_id column to pages table for custom templates
ALTER TABLE `pages` 
ADD COLUMN `template_id` int(11) DEFAULT NULL AFTER `layout_id`,
ADD COLUMN `custom_html` longtext AFTER `template_id`,
ADD COLUMN `custom_css` longtext AFTER `custom_html`,
ADD COLUMN `custom_js` longtext AFTER `custom_css`,
ADD COLUMN `template_variables` json AFTER `custom_js`,
ADD KEY `idx_template_id` (`template_id`),
ADD FOREIGN KEY (`template_id`) REFERENCES `page_templates`(`id`) ON DELETE SET NULL;

-- Insert default editable page templates
INSERT INTO `page_templates` (`name`, `slug`, `description`, `template_type`, `html_template`, `css_styles`, `js_scripts`, `variables`, `is_system`) VALUES

-- Default Page Template
('Default Page', 'default-page', 'Standard page layout with header and content', 'page',
'<!DOCTYPE html>
<html lang="{{ language|en }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} - {{ site_name|Your Website }}</title>
    <meta name="description" content="{{ meta_description }}">
    <meta name="keywords" content="{{ meta_keywords }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>{{ custom_css }}</style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">{{ site_name|Your Website }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(menu_pages)
                        {{ menu_pages }}
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if(use_sections)
            {{ sections_html }}
        @else
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <article class="page-content">
                            <header class="page-header mb-4">
                                <h1 class="page-title">{{ title }}</h1>
                                @if(excerpt)
                                    <p class="page-excerpt lead text-muted">{{ excerpt }}</p>
                                @endif
                            </header>
                            
                            <div class="page-body">
                                {{ content }}
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ current_year }} {{ site_name|Your Website }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Powered by Custom CMS</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>{{ custom_js }}</script>
</body>
</html>',

'.page-content {
    background: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.page-title {
    color: {{ primary_color|#198754 }};
    margin-bottom: 1rem;
    font-weight: 700;
}

.page-excerpt {
    border-left: 4px solid {{ primary_color|#198754 }};
    padding-left: 1rem;
    font-style: italic;
}

.page-body {
    line-height: 1.7;
    font-size: {{ content_font_size|16px }};
}

.page-body h2, .page-body h3, .page-body h4 {
    color: {{ heading_color|#333 }};
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.page-body img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem 0;
}

.footer {
    background-color: {{ footer_bg|#343a40 }} !important;
}

@media (max-width: 768px) {
    .page-content {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
}',

'// Page interactions
document.addEventListener("DOMContentLoaded", function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });
    
    // Add fade-in animation to page content
    const pageContent = document.querySelector(".page-content");
    if (pageContent) {
        pageContent.style.opacity = "0";
        pageContent.style.transform = "translateY(20px)";
        pageContent.style.transition = "all 0.6s ease";
        
        setTimeout(() => {
            pageContent.style.opacity = "1";
            pageContent.style.transform = "translateY(0)";
        }, 100);
    }
});',

'{"site_name": "Your Website", "primary_color": "#198754", "heading_color": "#333", "content_font_size": "16px", "footer_bg": "#343a40", "current_year": "2024"}',
1),

-- Homepage Template
('Homepage', 'homepage', 'Homepage template with hero section and features', 'page',
'<!DOCTYPE html>
<html lang="{{ language|en }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} - {{ site_name|Your Website }}</title>
    <meta name="description" content="{{ meta_description }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>{{ custom_css }}</style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index: 1000;">
        <div class="container">
            <a class="navbar-brand" href="/">{{ site_name|Your Website }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(menu_pages)
                        {{ menu_pages }}
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        @if(featured_image)
            <div class="hero-background" style="background-image: url(\'{{ featured_image }}\');"></div>
        @endif
        <div class="hero-content">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="hero-title">{{ title }}</h1>
                        @if(excerpt)
                            <p class="hero-subtitle">{{ excerpt }}</p>
                        @endif
                        <div class="hero-actions">
                            <a href="{{ cta_link|#about }}" class="btn btn-primary btn-lg me-3">{{ cta_text|Learn More }}</a>
                            <a href="{{ secondary_cta_link|#contact }}" class="btn btn-outline-light btn-lg">{{ secondary_cta_text|Contact Us }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    @if(use_sections)
        {{ sections_html }}
    @else
        <section class="content-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="content-wrapper">
                            {{ content }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ current_year }} {{ site_name|Your Website }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Powered by Custom CMS</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>{{ custom_js }}</script>
</body>
</html>',

'.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, {{ primary_color|#198754 }} 0%, {{ secondary_color|#20c997 }} 100%);
    color: white;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: {{ hero_bg_opacity|0.3 }};
}

.hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
}

.hero-title {
    font-size: {{ hero_title_size|3.5rem }};
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: {{ hero_subtitle_size|1.25rem }};
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

.content-section {
    background-color: {{ content_bg|#f8f9fa }};
}

.content-wrapper {
    background: white;
    padding: 3rem;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    margin-top: -3rem;
    position: relative;
    z-index: 5;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    .hero-subtitle {
        font-size: 1.1rem;
    }
    .hero-actions .btn {
        display: block;
        margin: 0.5rem 0;
    }
    .content-wrapper {
        padding: 2rem;
        margin-top: -2rem;
    }
}',

'// Homepage animations
document.addEventListener("DOMContentLoaded", function() {
    const heroTitle = document.querySelector(".hero-title");
    const heroSubtitle = document.querySelector(".hero-subtitle");
    const heroActions = document.querySelector(".hero-actions");
    
    if (heroTitle) {
        // Fade in animation
        [heroTitle, heroSubtitle, heroActions].forEach((element, index) => {
            if (element) {
                element.style.opacity = "0";
                element.style.transform = "translateY(30px)";
                
                setTimeout(() => {
                    element.style.transition = "all 0.8s ease";
                    element.style.opacity = "1";
                    element.style.transform = "translateY(0)";
                }, 200 + (index * 200));
            }
        });
    }
    
    // Parallax effect for hero background
    const heroBackground = document.querySelector(".hero-background");
    if (heroBackground) {
        window.addEventListener("scroll", () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroBackground.style.transform = `translateY(${rate}px)`;
        });
    }
});',

'{"site_name": "Your Website", "primary_color": "#198754", "secondary_color": "#20c997", "hero_title_size": "3.5rem", "hero_subtitle_size": "1.25rem", "hero_bg_opacity": "0.3", "content_bg": "#f8f9fa", "cta_text": "Learn More", "cta_link": "#about", "secondary_cta_text": "Contact Us", "secondary_cta_link": "#contact", "current_year": "2024"}',
1),

-- Contact Page Template
('Contact Page', 'contact-page', 'Contact page with form and information', 'page',
'<!DOCTYPE html>
<html lang="{{ language|en }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} - {{ site_name|Your Website }}</title>
    <meta name="description" content="{{ meta_description }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>{{ custom_css }}</style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">{{ site_name|Your Website }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(menu_pages)
                        {{ menu_pages }}
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="page-title">{{ title }}</h1>
                    @if(excerpt)
                        <p class="page-subtitle">{{ excerpt }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    @if(use_sections)
        {{ sections_html }}
    @else
        <section class="contact-section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="contact-form-wrapper">
                            <h2 class="mb-4">{{ form_title|Send us a Message }}</h2>
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
                                <button type="submit" class="btn btn-primary btn-lg">{{ submit_text|Send Message }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="contact-info">
                            <h3>{{ info_title|Contact Information }}</h3>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h5>Address</h5>
                                    <p>{{ address|123 Main Street<br>City, State 12345 }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <h5>Phone</h5>
                                    <p>{{ phone|+1 (555) 123-4567 }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <h5>Email</h5>
                                    <p>{{ email|contact@yourwebsite.com }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="additional-content">
                            {{ content }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ current_year }} {{ site_name|Your Website }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Powered by Custom CMS</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>{{ custom_js }}</script>
</body>
</html>',

'.page-header {
    background: linear-gradient(135deg, {{ primary_color|#198754 }} 0%, {{ secondary_color|#20c997 }} 100%);
    color: white;
    padding: 4rem 0 2rem;
    text-align: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.contact-section {
    background-color: {{ section_bg|#f8f9fa }};
}

.contact-form-wrapper {
    background: white;
    padding: 2.5rem;
    border-radius: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.contact-form .form-label {
    font-weight: 600;
    color: {{ label_color|#333 }};
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
    border-color: {{ primary_color|#198754 }};
    box-shadow: 0 0 0 0.2rem rgba(25,135,84,0.25);
}

.contact-info {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.contact-item i {
    font-size: 1.2rem;
    color: {{ primary_color|#198754 }};
    margin-right: 1rem;
    margin-top: 0.2rem;
    width: 20px;
}

.contact-item h5 {
    margin-bottom: 0.5rem;
    color: {{ heading_color|#333 }};
}

.contact-item p {
    margin: 0;
    color: {{ text_color|#666 }};
}

.additional-content {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .page-header {
        padding: 3rem 0 1.5rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .contact-form-wrapper,
    .contact-info,
    .additional-content {
        padding: 1.5rem;
    }
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
        message.className = `alert alert-${type === "success" ? "success" : "danger"} form-message`;
        message.textContent = text;
        
        // Insert message before form
        contactForm.parentNode.insertBefore(message, contactForm);
        
        // Auto-hide success messages
        if (type === "success") {
            setTimeout(() => {
                message.remove();
            }, 5000);
        }
    }
});',

'{"site_name": "Your Website", "primary_color": "#198754", "secondary_color": "#20c997", "section_bg": "#f8f9fa", "label_color": "#333", "heading_color": "#333", "text_color": "#666", "form_title": "Send us a Message", "submit_text": "Send Message", "info_title": "Contact Information", "address": "123 Main Street<br>City, State 12345", "phone": "+1 (555) 123-4567", "email": "contact@yourwebsite.com", "current_year": "2024"}',
1);

-- Create template_editor_settings table for CodeMirror preferences
CREATE TABLE IF NOT EXISTS `template_editor_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `theme` varchar(50) DEFAULT 'default',
    `font_size` int(11) DEFAULT 14,
    `line_numbers` tinyint(1) DEFAULT 1,
    `word_wrap` tinyint(1) DEFAULT 1,
    `auto_close_tags` tinyint(1) DEFAULT 1,
    `auto_close_brackets` tinyint(1) DEFAULT 1,
    `highlight_active_line` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for better performance
CREATE INDEX idx_pages_template_sections ON pages(template_id, use_sections);
CREATE INDEX idx_templates_type_active ON page_templates(template_type, is_active);

-- Update existing pages to use default template
UPDATE pages SET template_id = (SELECT id FROM page_templates WHERE slug = 'default-page' LIMIT 1) WHERE template_id IS NULL;

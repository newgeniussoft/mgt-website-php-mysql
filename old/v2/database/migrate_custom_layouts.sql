-- Migration for Custom Layouts and Page Sections System
-- Run this script to add custom layout functionality to your CMS

-- Create layouts table for custom layout templates
CREATE TABLE IF NOT EXISTS `layouts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text,
    `html_template` longtext NOT NULL,
    `css_styles` longtext,
    `js_scripts` longtext,
    `thumbnail` varchar(255),
    `is_active` tinyint(1) DEFAULT 1,
    `is_system` tinyint(1) DEFAULT 0,
    `created_by` int(11),
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_slug` (`slug`),
    KEY `idx_active` (`is_active`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create page_sections table for modular content blocks
CREATE TABLE IF NOT EXISTS `page_sections` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `page_id` int(11) NOT NULL,
    `section_type` varchar(50) NOT NULL, -- 'text', 'image', 'gallery', 'video', 'custom', 'html'
    `title` varchar(255),
    `content` longtext,
    `settings` json, -- Store section-specific settings (colors, alignment, etc.)
    `sort_order` int(11) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_page_id` (`page_id`),
    KEY `idx_sort_order` (`sort_order`),
    KEY `idx_section_type` (`section_type`),
    FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add layout_id column to pages table
ALTER TABLE `pages` 
ADD COLUMN `layout_id` int(11) DEFAULT NULL AFTER `template`,
ADD COLUMN `use_sections` tinyint(1) DEFAULT 0 AFTER `layout_id`,
ADD KEY `idx_layout_id` (`layout_id`),
ADD FOREIGN KEY (`layout_id`) REFERENCES `layouts`(`id`) ON DELETE SET NULL;

-- Insert default system layouts
INSERT INTO `layouts` (`name`, `slug`, `description`, `html_template`, `css_styles`, `is_system`, `created_by`) VALUES
('Default Layout', 'default', 'Standard page layout with header, content, and footer', 
'<div class="container my-5">
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
                
                @if(sections)
                    <div class="page-sections">
                        {{ sections }}
                    </div>
                @endif
            </article>
        </div>
    </div>
</div>', 
'.page-content { background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.page-title { color: #198754; margin-bottom: 1rem; }
.page-excerpt { border-left: 4px solid #198754; padding-left: 1rem; }', 
1, 1),

('Hero Layout', 'hero', 'Layout with large hero section and content below', 
'<div class="hero-section position-relative d-flex align-items-center justify-content-center text-white mb-5" style="height: 60vh; background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
    @if(featured_image)
        <img src="{{ featured_image }}" alt="{{ title }}" class="position-absolute w-100 h-100" style="object-fit: cover; opacity: 0.3;">
    @endif
    <div class="container text-center position-relative" style="z-index: 10;">
        <h1 class="display-3 font-weight-bold mb-3">{{ title }}</h1>
        @if(excerpt)
            <p class="lead">{{ excerpt }}</p>
        @endif
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="content-area">
                {{ content }}
            </div>
            
            @if(sections)
                <div class="page-sections mt-5">
                    {{ sections }}
                </div>
            @endif
        </div>
    </div>
</div>', 
'.hero-section { position: relative; overflow: hidden; }
.content-area { background: white; padding: 3rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin-top: -3rem; position: relative; z-index: 5; }', 
1, 1),

('Two Column Layout', 'two-column', 'Layout with sidebar and main content area', 
'<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <article class="main-content">
                <header class="content-header mb-4">
                    <h1 class="content-title">{{ title }}</h1>
                    @if(excerpt)
                        <p class="content-excerpt">{{ excerpt }}</p>
                    @endif
                </header>
                
                <div class="content-body">
                    {{ content }}
                </div>
                
                @if(sections)
                    <div class="content-sections mt-4">
                        {{ sections }}
                    </div>
                @endif
            </article>
        </div>
        
        <div class="col-lg-4">
            <aside class="sidebar">
                <div class="sidebar-widget">
                    <h3>Quick Info</h3>
                    <p>Additional information or navigation can go here.</p>
                </div>
            </aside>
        </div>
    </div>
</div>', 
'.main-content { background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.sidebar { background: #f8f9fa; padding: 1.5rem; border-radius: 0.5rem; }
.sidebar-widget { margin-bottom: 2rem; }
.content-title { color: #198754; }', 
1, 1);

-- Create section_templates table for reusable section templates
CREATE TABLE IF NOT EXISTS `section_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `section_type` varchar(50) NOT NULL,
    `template_html` longtext NOT NULL,
    `template_css` text,
    `default_settings` json,
    `thumbnail` varchar(255),
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_section_type` (`section_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default section templates
INSERT INTO `section_templates` (`name`, `section_type`, `template_html`, `template_css`, `default_settings`) VALUES
('Text Block', 'text', 
'<div class="text-section py-4">
    <div class="container">
        @if(title)
            <h2 class="section-title">{{ title }}</h2>
        @endif
        <div class="section-content">
            {{ content }}
        </div>
    </div>
</div>', 
'.text-section { background: {{ background_color|#ffffff }}; }
.section-title { color: {{ title_color|#198754 }}; margin-bottom: 1.5rem; }
.section-content { font-size: {{ font_size|16px }}; line-height: 1.7; }', 
'{"background_color": "#ffffff", "title_color": "#198754", "font_size": "16px"}'),

('Image Gallery', 'gallery', 
'<div class="gallery-section py-5">
    <div class="container">
        @if(title)
            <h2 class="section-title text-center mb-4">{{ title }}</h2>
        @endif
        <div class="row gallery-grid">
            {{ images }}
        </div>
    </div>
</div>', 
'.gallery-section { background: {{ background_color|#f8f9fa }}; }
.gallery-grid .col-md-4 { margin-bottom: 1rem; }
.gallery-item { border-radius: 0.5rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }', 
'{"background_color": "#f8f9fa", "columns": "3", "spacing": "1rem"}'),

('Call to Action', 'cta', 
'<div class="cta-section py-5 text-center text-white" style="background: linear-gradient(135deg, {{ primary_color|#198754 }} 0%, {{ secondary_color|#20c997 }} 100%);">
    <div class="container">
        @if(title)
            <h2 class="cta-title display-4 mb-3">{{ title }}</h2>
        @endif
        @if(content)
            <p class="cta-text lead mb-4">{{ content }}</p>
        @endif
        @if(button_text)
            <a href="{{ button_link|# }}" class="btn btn-light btn-lg">{{ button_text }}</a>
        @endif
    </div>
</div>', 
'.cta-section { position: relative; }
.cta-title { font-weight: bold; }
.btn-light:hover { transform: translateY(-2px); transition: all 0.3s ease; }', 
'{"primary_color": "#198754", "secondary_color": "#20c997", "button_text": "Learn More", "button_link": "#"}');

-- Add indexes for better performance
CREATE INDEX idx_pages_layout_sections ON pages(layout_id, use_sections);
CREATE INDEX idx_sections_page_order ON page_sections(page_id, sort_order);

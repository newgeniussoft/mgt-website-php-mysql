-- CMS Page Management System
-- Structure: Page -> Template -> Section -> Content

-- Templates Table (stores page templates with Monaco editor support)
CREATE TABLE IF NOT EXISTS `templates` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `html_content` LONGTEXT,
    `css_content` LONGTEXT,
    `js_content` LONGTEXT,
    `thumbnail` VARCHAR(255),
    `is_default` TINYINT(1) DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_by` INT(11),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages Table (main pages)
CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `template_id` INT(11),
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `meta_title` VARCHAR(255),
    `meta_description` TEXT,
    `meta_keywords` VARCHAR(255),
    `featured_image` VARCHAR(255),
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `is_homepage` TINYINT(1) DEFAULT 0,
    `show_in_menu` TINYINT(1) DEFAULT 1,
    `menu_order` INT(11) DEFAULT 0,
    `parent_id` INT(11) DEFAULT NULL,
    `author_id` INT(11),
    `published_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_status` (`status`),
    INDEX `idx_template` (`template_id`),
    INDEX `idx_parent` (`parent_id`),
    FOREIGN KEY (`template_id`) REFERENCES `templates`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`parent_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sections Table (page sections)
CREATE TABLE IF NOT EXISTS `sections` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `page_id` INT(11) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `type` VARCHAR(50) DEFAULT 'content',
    `html_template` LONGTEXT,
    `css_styles` TEXT,
    `js_scripts` TEXT,
    `settings` JSON,
    `order_index` INT(11) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_page` (`page_id`),
    INDEX `idx_order` (`order_index`),
    INDEX `idx_active` (`is_active`),
    FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Content Table (section content with Summernote editor)
CREATE TABLE IF NOT EXISTS `contents` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `section_id` INT(11) NOT NULL,
    `title` VARCHAR(255),
    `content` LONGTEXT,
    `content_type` VARCHAR(50) DEFAULT 'html',
    `language` VARCHAR(5) DEFAULT 'en',
    `order_index` INT(11) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_section` (`section_id`),
    INDEX `idx_language` (`language`),
    INDEX `idx_order` (`order_index`),
    FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default template
INSERT INTO `templates` (`name`, `slug`, `description`, `html_content`, `css_content`, `js_content`, `is_default`, `status`) VALUES
('Default Template', 'default', 'Basic page template with header, content area, and footer', 
'<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ page_title }}</title>
    <meta name="description" content="{{ meta_description }}">
    <meta name="keywords" content="{{ meta_keywords }}">
    {{ custom_css }}
</head>
<body>
    <header class="site-header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <h1>{{ site_name }}</h1>
                </div>
                <ul class="nav-menu">
                    {{ menu_items }}
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            <h1 class="page-title">{{ page_title }}</h1>
            <div class="page-content">
                {{ page_sections }}
            </div>
        </div>
    </main>
    
    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2024 {{ site_name }}. All rights reserved.</p>
        </div>
    </footer>
    
    {{ custom_js }}
</body>
</html>',
'* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    line-height: 1.6;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.site-header {
    background: #2c3e50;
    color: white;
    padding: 1rem 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.nav-menu a {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
}

.nav-menu a:hover {
    color: #3498db;
}

.main-content {
    min-height: 60vh;
    padding: 3rem 0;
}

.page-title {
    margin-bottom: 2rem;
    color: #2c3e50;
}

.site-footer {
    background: #34495e;
    color: white;
    padding: 2rem 0;
    text-align: center;
    margin-top: 3rem;
}',
'// Custom JavaScript
console.log("Page loaded successfully");',
1, 'active');

-- Insert sample page
INSERT INTO `pages` (`template_id`, `title`, `slug`, `meta_title`, `meta_description`, `status`, `is_homepage`, `show_in_menu`, `author_id`) VALUES
(1, 'Home', 'home', 'Welcome to Our Website', 'This is the homepage of our website', 'published', 1, 1, 1);

-- Pages table for CMS functionality
-- Run this SQL script to create the pages table in your database

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext,
  `excerpt` text,
  `meta_title` varchar(255),
  `meta_description` text,
  `meta_keywords` varchar(500),
  `featured_image` varchar(255),
  `template` varchar(100) DEFAULT 'default',
  `status` enum('draft','published','private','archived') NOT NULL DEFAULT 'draft',
  `author_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_order` int(11) DEFAULT 0,
  `is_homepage` tinyint(1) DEFAULT 0,
  `show_in_menu` tinyint(1) DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `author_id` (`author_id`),
  KEY `parent_id` (`parent_id`),
  KEY `menu_order` (`menu_order`),
  KEY `is_homepage` (`is_homepage`),
  KEY `show_in_menu` (`show_in_menu`),
  KEY `published_at` (`published_at`),
  FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create page categories table for better organization
CREATE TABLE IF NOT EXISTS `page_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  FOREIGN KEY (`parent_id`) REFERENCES `page_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Junction table for page-category relationships (many-to-many)
CREATE TABLE IF NOT EXISTS `page_category_relations` (
  `page_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`, `category_id`),
  KEY `page_id` (`page_id`),
  KEY `category_id` (`category_id`),
  FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `page_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default page categories
INSERT INTO `page_categories` (`name`, `slug`, `description`) VALUES
('General', 'general', 'General pages and content'),
('About', 'about', 'About us and company information'),
('Services', 'services', 'Service pages and offerings'),
('Blog', 'blog', 'Blog posts and articles'),
('Legal', 'legal', 'Legal pages like privacy policy, terms of service');

-- Insert sample homepage
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `meta_title`, `meta_description`, `template`, `status`, `author_id`, `is_homepage`, `show_in_menu`, `published_at`) VALUES
('Welcome to Our Website', 'home', 
'<h1>Welcome to Our Amazing Website</h1>
<p>This is your homepage content. You can edit this page from the admin panel to customize your website''s main page.</p>
<p>Features of our CMS:</p>
<ul>
<li>Easy page management</li>
<li>SEO-friendly URLs</li>
<li>Rich text editor</li>
<li>Template system</li>
<li>Menu management</li>
</ul>', 
'Welcome to our website - your gateway to amazing content and services.',
'Welcome to Our Website | Home',
'Welcome to our amazing website. Discover our services, read our blog, and learn more about what we do.',
'homepage', 'published', 1, 1, 1, NOW());

-- Insert sample about page
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `meta_title`, `meta_description`, `template`, `status`, `author_id`, `show_in_menu`, `published_at`) VALUES
('About Us', 'about-us', 
'<h1>About Our Company</h1>
<p>We are a dynamic company dedicated to providing excellent services to our clients.</p>
<p>Our mission is to deliver high-quality solutions that exceed expectations.</p>
<h2>Our Team</h2>
<p>Our team consists of experienced professionals who are passionate about what they do.</p>', 
'Learn more about our company, mission, and the team behind our success.',
'About Us | Our Company Story',
'Learn about our company history, mission, values, and the dedicated team that makes it all possible.',
'default', 'published', 1, 1, NOW());

-- Insert sample contact page
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `meta_title`, `meta_description`, `template`, `status`, `author_id`, `show_in_menu`, `published_at`) VALUES
('Contact Us', 'contact', 
'<h1>Get in Touch</h1>
<p>We''d love to hear from you. Send us a message and we''ll respond as soon as possible.</p>
<h2>Contact Information</h2>
<p><strong>Email:</strong> info@example.com</p>
<p><strong>Phone:</strong> +1 (555) 123-4567</p>
<p><strong>Address:</strong> 123 Main Street, City, State 12345</p>', 
'Contact us for inquiries, support, or to learn more about our services.',
'Contact Us | Get in Touch',
'Contact us today for any questions, support, or business inquiries. We are here to help you.',
'contact', 'published', 1, 1, NOW());

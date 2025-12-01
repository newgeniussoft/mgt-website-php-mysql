-- Create settings table for general application settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  `type` ENUM('text', 'textarea', 'number', 'boolean', 'email', 'url', 'json', 'image') DEFAULT 'text',
  `group` VARCHAR(100) DEFAULT 'general',
  `label` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key` (`key`),
  KEY `idx_group` (`group`),
  KEY `idx_order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `settings` (`key`, `value`, `type`, `group`, `label`, `description`, `order`) VALUES
-- General Settings
('site_name', 'Madagascar Green Tours', 'text', 'general', 'Site Name', 'The name of your website', 1),
('site_tagline', 'Discover the Beauty of Madagascar', 'text', 'general', 'Site Tagline', 'A short description of your site', 2),
('site_description', 'Experience the natural wonders and unique wildlife of Madagascar with our eco-friendly tours', 'textarea', 'general', 'Site Description', 'Used for SEO meta description', 3),
('site_keywords', 'madagascar, tours, travel, wildlife, nature, eco-tourism', 'textarea', 'general', 'Site Keywords', 'SEO keywords separated by commas', 4),
('site_logo', '/images/logos/logo.webp', 'image', 'general', 'Site Logo', 'Main logo of the website', 5),
('site_favicon', '/images/logos/favicon.ico', 'image', 'general', 'Site Favicon', 'Browser tab icon', 6),

-- Contact Information
('contact_email', 'info@madagascargreentours.com', 'email', 'contact', 'Contact Email', 'Main contact email address', 1),
('contact_phone', '+261 20 22 123 45', 'text', 'contact', 'Contact Phone', 'Main contact phone number', 2),
('contact_address', 'Antananarivo, Madagascar', 'textarea', 'contact', 'Contact Address', 'Physical address', 3),
('contact_hours', 'Mon-Fri: 8:00 AM - 6:00 PM', 'text', 'contact', 'Business Hours', 'Operating hours', 4),

-- Social Media
('social_facebook', 'https://facebook.com/madagascargreentours', 'url', 'social', 'Facebook URL', 'Facebook page link', 1),
('social_twitter', 'https://twitter.com/mgt_tours', 'url', 'social', 'Twitter URL', 'Twitter profile link', 2),
('social_instagram', 'https://instagram.com/madagascargreentours', 'url', 'social', 'Instagram URL', 'Instagram profile link', 3),
('social_youtube', 'https://youtube.com/@madagascargreentours', 'url', 'social', 'YouTube URL', 'YouTube channel link', 4),
('social_linkedin', 'https://linkedin.com/company/madagascar-green-tours', 'url', 'social', 'LinkedIn URL', 'LinkedIn company page', 5),

-- Email Settings
('smtp_host', 'smtp.gmail.com', 'text', 'email', 'SMTP Host', 'Mail server hostname', 1),
('smtp_port', '587', 'number', 'email', 'SMTP Port', 'Mail server port', 2),
('smtp_username', '', 'text', 'email', 'SMTP Username', 'Email account username', 3),
('smtp_password', '', 'text', 'email', 'SMTP Password', 'Email account password (encrypted)', 4),
('smtp_encryption', 'tls', 'text', 'email', 'SMTP Encryption', 'TLS or SSL', 5),
('mail_from_address', 'noreply@madagascargreentours.com', 'email', 'email', 'From Email', 'Default sender email', 6),
('mail_from_name', 'Madagascar Green Tours', 'text', 'email', 'From Name', 'Default sender name', 7),

-- SEO Settings
('seo_meta_title', 'Madagascar Green Tours - Eco-Friendly Tours & Travel', 'text', 'seo', 'Default Meta Title', 'Default page title for SEO', 1),
('seo_meta_description', 'Explore Madagascar with our eco-friendly tours. Discover unique wildlife, pristine beaches, and rich culture.', 'textarea', 'seo', 'Default Meta Description', 'Default meta description', 2),
('seo_og_image', '/images/og-image.jpg', 'image', 'seo', 'Open Graph Image', 'Default social sharing image', 3),
('google_analytics_id', '', 'text', 'seo', 'Google Analytics ID', 'GA tracking ID (e.g., G-XXXXXXXXXX)', 4),
('google_site_verification', '', 'text', 'seo', 'Google Site Verification', 'Google Search Console verification code', 5),

-- Appearance Settings
('theme_primary_color', '#2ecc71', 'text', 'appearance', 'Primary Color', 'Main theme color (hex)', 1),
('theme_secondary_color', '#27ae60', 'text', 'appearance', 'Secondary Color', 'Secondary theme color (hex)', 2),
('items_per_page', '12', 'number', 'appearance', 'Items Per Page', 'Number of items to show per page', 3),
('date_format', 'Y-m-d', 'text', 'appearance', 'Date Format', 'PHP date format', 4),
('time_format', 'H:i:s', 'text', 'appearance', 'Time Format', 'PHP time format', 5),

-- System Settings
('maintenance_mode', '0', 'boolean', 'system', 'Maintenance Mode', 'Enable maintenance mode', 1),
('maintenance_message', 'We are currently performing maintenance. Please check back soon.', 'textarea', 'system', 'Maintenance Message', 'Message shown during maintenance', 2),
('cache_enabled', '1', 'boolean', 'system', 'Enable Cache', 'Enable application caching', 3),
('debug_mode', '0', 'boolean', 'system', 'Debug Mode', 'Show detailed error messages', 4),
('timezone', 'Indian/Antananarivo', 'text', 'system', 'Timezone', 'Application timezone', 5);

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
  `language` varchar(5) DEFAULT 'en',
  `translation_group` varchar(50) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_order` int(11) DEFAULT 0,
  `is_homepage` tinyint(1) DEFAULT 0,
  `show_in_menu` tinyint(1) DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_language` (`slug`, `language`),
  KEY `status` (`status`),
  KEY `language` (`language`),
  KEY `translation_group` (`translation_group`),
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

-- Insert sample pages (English)
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `meta_title`, `meta_description`, `template`, `status`, `language`, `translation_group`, `author_id`, `is_homepage`, `show_in_menu`, `published_at`) VALUES
('Welcome to Our Website', 'home', '<h2>Welcome to Our Amazing Website</h2><p>This is the homepage of your new website. You can edit this content from the admin panel.</p><p>Features of this CMS:</p><ul><li>Easy content management</li><li>SEO optimization</li><li>Responsive design</li><li>Multiple templates</li></ul>', 'Welcome to our amazing website with powerful CMS features', 'Welcome to Our Website - Home', 'Discover our amazing website with powerful CMS features, SEO optimization, and responsive design', 'homepage', 'published', 'en', 'home-group', 1, 1, 1, NOW()),
('About Us', 'about', '<h2>About Our Company</h2><p>We are a dynamic company focused on delivering exceptional web solutions.</p><p>Our mission is to create beautiful, functional websites that help businesses grow and succeed in the digital world.</p>', 'Learn more about our company and mission', 'About Us - Our Story', 'Learn about our company, mission, and the team behind our success', 'about', 'published', 'en', 'about-group', 1, 0, 1, NOW()),
('Contact Us', 'contact', '<h2>Get in Touch</h2><p>We would love to hear from you! Contact us for any inquiries or support.</p><p>Our team is ready to help you with your project needs.</p>', 'Contact us for inquiries and support', 'Contact Us - Get in Touch', 'Contact our team for inquiries, support, and project discussions', 'contact', 'published', 'en', 'contact-group', 1, 0, 1, NOW());

-- Insert sample pages (Spanish)
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `meta_title`, `meta_description`, `template`, `status`, `language`, `translation_group`, `author_id`, `is_homepage`, `show_in_menu`, `published_at`) VALUES
('Bienvenido a Nuestro Sitio Web', 'inicio', '<h2>Bienvenido a Nuestro Increíble Sitio Web</h2><p>Esta es la página de inicio de su nuevo sitio web. Puede editar este contenido desde el panel de administración.</p><p>Características de este CMS:</p><ul><li>Gestión de contenido fácil</li><li>Optimización SEO</li><li>Diseño responsivo</li><li>Múltiples plantillas</li></ul>', 'Bienvenido a nuestro increíble sitio web con potentes características de CMS', 'Bienvenido a Nuestro Sitio Web - Inicio', 'Descubre nuestro increíble sitio web con potentes características de CMS, optimización SEO y diseño responsivo', 'homepage', 'published', 'es', 'home-group', 1, 1, 1, NOW()),
('Acerca de Nosotros', 'acerca-de', '<h2>Acerca de Nuestra Empresa</h2><p>Somos una empresa dinámica enfocada en brindar soluciones web excepcionales.</p><p>Nuestra misión es crear sitios web hermosos y funcionales que ayuden a las empresas a crecer y tener éxito en el mundo digital.</p>', 'Conoce más sobre nuestra empresa y misión', 'Acerca de Nosotros - Nuestra Historia', 'Conoce sobre nuestra empresa, misión y el equipo detrás de nuestro éxito', 'about', 'published', 'es', 'about-group', 1, 0, 1, NOW()),
('Contáctanos', 'contacto', '<h2>Ponte en Contacto</h2><p>¡Nos encantaría saber de ti! Contáctanos para cualquier consulta o soporte.</p><p>Nuestro equipo está listo para ayudarte con las necesidades de tu proyecto.</p>', 'Contáctanos para consultas y soporte', 'Contáctanos - Ponte en Contacto', 'Contacta a nuestro equipo para consultas, soporte y discusiones de proyectos', 'contact', 'published', 'es', 'contact-group', 1, 0, 1, NOW());

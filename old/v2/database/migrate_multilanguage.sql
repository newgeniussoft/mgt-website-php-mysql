-- Migration script to add multi-language support to existing CMS installations
-- Run this script if you already have the CMS installed and want to add language support

-- Add language and translation_group columns to existing pages table
ALTER TABLE `pages` 
ADD COLUMN `language` varchar(5) DEFAULT 'en' AFTER `status`,
ADD COLUMN `translation_group` varchar(50) DEFAULT NULL AFTER `language`;

-- Drop the old unique constraint on slug
ALTER TABLE `pages` DROP INDEX `slug`;

-- Add new unique constraint for slug + language combination
ALTER TABLE `pages` ADD UNIQUE KEY `slug_language` (`slug`, `language`);

-- Add indexes for better performance
ALTER TABLE `pages` ADD KEY `language` (`language`);
ALTER TABLE `pages` ADD KEY `translation_group` (`translation_group`);

-- Update existing pages to have English as default language and generate translation groups
UPDATE `pages` SET 
    `language` = 'en',
    `translation_group` = CONCAT(LOWER(REPLACE(REPLACE(title, ' ', '-'), '.', '')), '-', SUBSTRING(MD5(RAND()), 1, 8))
WHERE `translation_group` IS NULL;

-- Insert Spanish translations for existing pages (optional - you can customize these)
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `meta_title`, `meta_description`, `template`, `status`, `language`, `translation_group`, `author_id`, `parent_id`, `menu_order`, `is_homepage`, `show_in_menu`, `published_at`)
SELECT 
    CASE 
        WHEN title = 'Welcome to Our Website' THEN 'Bienvenido a Nuestro Sitio Web'
        WHEN title = 'About Us' THEN 'Acerca de Nosotros'
        WHEN title = 'Contact Us' THEN 'Contáctanos'
        ELSE CONCAT(title, ' (ES)')
    END as title,
    CASE 
        WHEN slug = 'home' THEN 'inicio'
        WHEN slug = 'about' THEN 'acerca-de'
        WHEN slug = 'contact' THEN 'contacto'
        ELSE CONCAT(slug, '-es')
    END as slug,
    CASE 
        WHEN title = 'Welcome to Our Website' THEN '<h2>Bienvenido a Nuestro Increíble Sitio Web</h2><p>Esta es la página de inicio de su nuevo sitio web. Puede editar este contenido desde el panel de administración.</p><p>Características de este CMS:</p><ul><li>Gestión de contenido fácil</li><li>Optimización SEO</li><li>Diseño responsivo</li><li>Múltiples plantillas</li></ul>'
        WHEN title = 'About Us' THEN '<h2>Acerca de Nuestra Empresa</h2><p>Somos una empresa dinámica enfocada en brindar soluciones web excepcionales.</p><p>Nuestra misión es crear sitios web hermosos y funcionales que ayuden a las empresas a crecer y tener éxito en el mundo digital.</p>'
        WHEN title = 'Contact Us' THEN '<h2>Ponte en Contacto</h2><p>¡Nos encantaría saber de ti! Contáctanos para cualquier consulta o soporte.</p><p>Nuestro equipo está listo para ayudarte con las necesidades de tu proyecto.</p>'
        ELSE content
    END as content,
    CASE 
        WHEN title = 'Welcome to Our Website' THEN 'Bienvenido a nuestro increíble sitio web con potentes características de CMS'
        WHEN title = 'About Us' THEN 'Conoce más sobre nuestra empresa y misión'
        WHEN title = 'Contact Us' THEN 'Contáctanos para consultas y soporte'
        ELSE excerpt
    END as excerpt,
    CASE 
        WHEN title = 'Welcome to Our Website' THEN 'Bienvenido a Nuestro Sitio Web - Inicio'
        WHEN title = 'About Us' THEN 'Acerca de Nosotros - Nuestra Historia'
        WHEN title = 'Contact Us' THEN 'Contáctanos - Ponte en Contacto'
        ELSE meta_title
    END as meta_title,
    CASE 
        WHEN title = 'Welcome to Our Website' THEN 'Descubre nuestro increíble sitio web con potentes características de CMS, optimización SEO y diseño responsivo'
        WHEN title = 'About Us' THEN 'Conoce sobre nuestra empresa, misión y el equipo detrás de nuestro éxito'
        WHEN title = 'Contact Us' THEN 'Contacta a nuestro equipo para consultas, soporte y discusiones de proyectos'
        ELSE meta_description
    END as meta_description,
    template,
    status,
    'es' as language,
    translation_group,
    author_id,
    parent_id,
    menu_order,
    is_homepage,
    show_in_menu,
    published_at
FROM `pages` 
WHERE `language` = 'en' AND title IN ('Welcome to Our Website', 'About Us', 'Contact Us');

-- Show completion message
SELECT 'Multi-language migration completed successfully!' as message;

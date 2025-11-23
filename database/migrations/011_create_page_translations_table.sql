-- Page Translations Table
-- Stores per-locale values for page metadata

CREATE TABLE IF NOT EXISTS `page_translations` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `page_id` INT(11) NOT NULL,
    `locale` VARCHAR(5) NOT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `meta_title` VARCHAR(255) DEFAULT NULL,
    `meta_description` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_page_locale` (`page_id`, `locale`),
    CONSTRAINT `fk_page_translations_page`
        FOREIGN KEY (`page_id`) REFERENCES `pages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example seed (optional): Spanish translation for sample Home page (id may vary)
-- INSERT INTO `page_translations` (`page_id`, `locale`, `title`, `meta_title`, `meta_description`)
-- VALUES (1, 'es', 'Inicio', 'Bienvenido a Nuestro Sitio', 'Esta es la página de inicio de nuestro sitio web');

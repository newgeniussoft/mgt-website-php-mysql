CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `title_es` varchar(250) NOT NULL,
  `short_texte` varchar(500) NOT NULL,
  `short_texte_es` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `description_es` text NOT NULL,
  `image` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

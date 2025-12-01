-- Reviews table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `rating` INT(1) NOT NULL,
  `name_user` VARCHAR(255) NOT NULL,
  `email_user` VARCHAR(120) NOT NULL,
  `message` TEXT NOT NULL,
  `pending` INT(11) NOT NULL DEFAULT 1,
  `daty` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pending` (`pending`),
  KEY `idx_daty` (`daty`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

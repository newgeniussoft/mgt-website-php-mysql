-- Create media table for file management
CREATE TABLE IF NOT EXISTS `media` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `original_filename` VARCHAR(255) NOT NULL,
  `path` VARCHAR(500) NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `mime_type` VARCHAR(100) NULL,
  `extension` VARCHAR(20) NULL,
  `size` BIGINT(20) UNSIGNED DEFAULT 0,
  `width` INT(11) UNSIGNED NULL,
  `height` INT(11) UNSIGNED NULL,
  `title` VARCHAR(255) NULL,
  `alt_text` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `folder_id` INT(11) UNSIGNED NULL,
  `user_id` INT(11) UNSIGNED NULL,
  `type` ENUM('image', 'video', 'audio', 'document', 'other') DEFAULT 'other',
  `is_public` TINYINT(1) DEFAULT 1,
  `downloads` INT(11) UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_folder_id` (`folder_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_type` (`type`),
  KEY `idx_mime_type` (`mime_type`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create media folders table
CREATE TABLE IF NOT EXISTS `media_folders` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `parent_id` INT(11) UNSIGNED NULL,
  `path` VARCHAR(500) NULL,
  `description` TEXT NULL,
  `order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default folders
INSERT INTO `media_folders` (`name`, `slug`, `parent_id`, `description`, `order`) VALUES
('Images', 'images', NULL, 'Image files (JPG, PNG, GIF, etc.)', 1),
('Documents', 'documents', NULL, 'Document files (PDF, DOC, XLS, etc.)', 2),
('Videos', 'videos', NULL, 'Video files (MP4, AVI, MOV, etc.)', 3),
('Audio', 'audio', NULL, 'Audio files (MP3, WAV, OGG, etc.)', 4),
('Others', 'others', NULL, 'Other file types', 5);

-- Insert sample media (optional)
INSERT INTO `media` (`filename`, `original_filename`, `path`, `url`, `mime_type`, `extension`, `size`, `type`, `title`, `alt_text`, `folder_id`) VALUES
('sample-image.jpg', 'sample-image.jpg', '/uploads/media/sample-image.jpg', '/uploads/media/sample-image.jpg', 'image/jpeg', 'jpg', 102400, 'image', 'Sample Image', 'A sample image', 1);

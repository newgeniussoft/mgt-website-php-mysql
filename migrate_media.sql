-- Media Management System Migration
-- This script creates the media table for file management

-- Create media table
CREATE TABLE IF NOT EXISTS `media` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filename` varchar(255) NOT NULL,
    `original_name` varchar(255) NOT NULL,
    `file_path` varchar(500) NOT NULL,
    `file_size` bigint(20) NOT NULL,
    `mime_type` varchar(100) NOT NULL,
    `file_type` enum('image', 'video', 'audio', 'document', 'other') NOT NULL DEFAULT 'other',
    `alt_text` varchar(255) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `width` int(11) DEFAULT NULL,
    `height` int(11) DEFAULT NULL,
    `thumbnail_path` varchar(500) DEFAULT NULL,
    `uploaded_by` int(11) NOT NULL,
    `is_public` tinyint(1) NOT NULL DEFAULT 1,
    `download_count` int(11) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_file_type` (`file_type`),
    KEY `idx_mime_type` (`mime_type`),
    KEY `idx_uploaded_by` (`uploaded_by`),
    KEY `idx_is_public` (`is_public`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_media_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create media_folders table for organization
CREATE TABLE IF NOT EXISTS `media_folders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `parent_id` int(11) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `created_by` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_slug_parent` (`slug`, `parent_id`),
    KEY `idx_parent_id` (`parent_id`),
    KEY `idx_created_by` (`created_by`),
    CONSTRAINT `fk_media_folders_parent` FOREIGN KEY (`parent_id`) REFERENCES `media_folders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_media_folders_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add folder_id to media table
ALTER TABLE `media` ADD COLUMN `folder_id` int(11) DEFAULT NULL AFTER `uploaded_by`;
ALTER TABLE `media` ADD KEY `idx_folder_id` (`folder_id`);
ALTER TABLE `media` ADD CONSTRAINT `fk_media_folder` FOREIGN KEY (`folder_id`) REFERENCES `media_folders` (`id`) ON DELETE SET NULL;

-- Create default folders
INSERT INTO `media_folders` (`name`, `slug`, `description`, `created_by`) VALUES
('Images', 'images', 'Default folder for images', 1),
('Documents', 'documents', 'Default folder for documents', 1),
('Videos', 'videos', 'Default folder for videos', 1),
('Audio', 'audio', 'Default folder for audio files', 1);

-- Sample media entries (optional)
INSERT INTO `media` (`filename`, `original_name`, `file_path`, `file_size`, `mime_type`, `file_type`, `alt_text`, `title`, `uploaded_by`, `folder_id`) VALUES
('sample-image.jpg', 'sample-image.jpg', '/uploads/images/sample-image.jpg', 245760, 'image/jpeg', 'image', 'Sample image', 'Sample Image', 1, 1),
('sample-doc.pdf', 'sample-document.pdf', '/uploads/documents/sample-doc.pdf', 1048576, 'application/pdf', 'document', NULL, 'Sample Document', 1, 2);

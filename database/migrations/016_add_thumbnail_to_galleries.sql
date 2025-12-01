-- Add `thumbnail` column to galleries table if it doesn't exist
SET @exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'galleries' AND COLUMN_NAME = 'thumbnail');
SET @sql := IF(@exists = 0, 'ALTER TABLE `galleries` ADD COLUMN `thumbnail` VARCHAR(255) NULL AFTER `image`', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

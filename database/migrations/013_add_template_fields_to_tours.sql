-- Migration: Add per-tour template selection and variables
-- Adds template_slug and template_variables columns to tours table

ALTER TABLE `tours`
  ADD COLUMN `template_slug` varchar(255) DEFAULT NULL AFTER `meta_keywords`,
  ADD COLUMN `template_variables` longtext DEFAULT NULL COMMENT 'JSON of template-specific variables' AFTER `template_slug`;

-- Optional index for faster lookup by template
CREATE INDEX `idx_tours_template_slug` ON `tours`(`template_slug`);

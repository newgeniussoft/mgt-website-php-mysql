-- Add slug to posts table for SEO-friendly URLs
-- Safe to run multiple times (IF NOT EXISTS guards are not available for columns in MySQL <8, so we defensively try ADD and ignore if already exists)

ALTER TABLE `posts`
  ADD COLUMN `slug` VARCHAR(255) NULL AFTER `title`;

-- Create index on slug for faster lookups (non-unique to avoid failing on existing duplicates)
ALTER TABLE `posts`
  ADD INDEX `idx_posts_slug` (`slug`);

-- Backfill slug values from title where missing
UPDATE `posts`
SET `slug` = LOWER(
    TRIM(BOTH '-' FROM
        REPLACE(
            REPLACE(
                REPLACE(
                    REPLACE(
                        REPLACE(`title`, ' ', '-'),
                    '_','-'),
                '/','-'),
            '\\','-'),
        '--','-')
    )
)
WHERE (`slug` IS NULL OR `slug` = '') AND `title` IS NOT NULL AND `title` != '';

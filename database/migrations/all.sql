-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : sql101.infinityfree.com
-- Généré le :  sam. 22 nov. 2025 à 01:52
-- Version du serveur :  11.4.7-MariaDB
-- Version de PHP :  7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `if0_38932427_db_mgt`
--

-- --------------------------------------------------------

--
-- Structure de la table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `content_type` varchar(50) DEFAULT 'html',
  `language` varchar(5) DEFAULT 'en',
  `order_index` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `slug`, `description`, `image`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(4, 'sqdsq', '', ' dsdqsds', 'galleries/20250627_1953_Red Car Logo_remix_01jys4scadf7zajkm7h4m631md.png', 'active', 0, '2025-11-15 12:32:11', '2025-11-15 12:32:11');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `path` varchar(500) NOT NULL,
  `url` varchar(500) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `size` bigint(20) UNSIGNED DEFAULT 0,
  `width` int(11) UNSIGNED DEFAULT NULL,
  `height` int(11) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `folder_id` int(11) UNSIGNED DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `type` enum('image','video','audio','document','other') DEFAULT 'other',
  `is_public` tinyint(1) DEFAULT 1,
  `downloads` int(11) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `filename`, `original_filename`, `path`, `url`, `mime_type`, `extension`, `size`, `width`, `height`, `title`, `alt_text`, `description`, `folder_id`, `user_id`, `type`, `is_public`, `downloads`, `created_at`, `updated_at`) VALUES
(4, '1762625797_690f8905d03bb.png', '3d-logo-new.png', '/uploads/media/1762625797_690f8905d03bb.png', '/uploads/media/1762625797_690f8905d03bb.png', 'image/png', 'png', 1368402, 941, 967, 'Logo of Madagascar green tours', 'Logo of Madagascar green tours', '', 1, 1, 'image', 1, 0, '2025-11-08 17:16:38', '2025-11-08 17:19:18'),
(5, 'Data', 'ada', 'documents', 'docs', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, 0, 0, 'other', 1, 0, NULL, NULL),
(6, 'zany', 'ndray', 'ary', 'jereo', NULL, NULL, 0, 0, 0, NULL, NULL, NULL, 0, 0, 'other', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `media_folders`
--

CREATE TABLE `media_folders` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media_folders`
--

INSERT INTO `media_folders` (`id`, `name`, `slug`, `parent_id`, `path`, `description`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Images', 'images', NULL, NULL, 'Image files (JPG, PNG, GIF, etc.)', 1, '2025-11-08 17:59:22', '2025-11-08 17:59:22'),
(2, 'Documents', 'documents', NULL, NULL, 'Document files (PDF, DOC, XLS, etc.)', 2, '2025-11-08 17:59:22', '2025-11-08 17:59:22'),
(3, 'Videos', 'videos', NULL, NULL, 'Video files (MP4, AVI, MOV, etc.)', 3, '2025-11-08 17:59:22', '2025-11-08 17:59:22'),
(4, 'Audio', 'audio', NULL, NULL, 'Audio files (MP3, WAV, OGG, etc.)', 4, '2025-11-08 17:59:22', '2025-11-08 17:59:22'),
(5, 'Others', 'others', NULL, NULL, 'Other file types', 5, '2025-11-08 17:59:22', '2025-11-08 17:59:22'),
(6, 'tours', 'tours', 1, NULL, 'Here is the image of tours', 0, '2025-11-08 17:17:17', '2025-11-08 17:17:17');

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `is_homepage` tinyint(1) DEFAULT 0,
  `show_in_menu` tinyint(1) DEFAULT 1,
  `menu_order` int(11) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pages`
--

INSERT INTO `pages` (`id`, `template_id`, `title`, `slug`, `meta_title`, `meta_description`, `meta_keywords`, `featured_image`, `status`, `is_homepage`, `show_in_menu`, `menu_order`, `parent_id`, `author_id`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 7, 'Home', 'home', 'Welcome to Our Website', 'This is the homepage of our website', 'best travel, agence de voyage, explore travel, madagascar tourism, madagascar tours, madagascar trip, tour in madagascar, travel tours, private tour', '/uploads/pages/madagascar-canoe-in-the-sea.jpeg', 'published', 1, 1, 0, NULL, 1, '2025-11-10 09:59:47', '2025-11-09 14:49:52', '2025-11-19 06:24:52'),
(6, 2, 'Tours', 'tour', 'Our tours', 'This is the list of amazing tours', 'tours madagascar, madagascar local tour company', NULL, 'published', 0, 1, 1, NULL, 1, '2025-11-18 11:51:00', '2025-11-18 11:51:00', '2025-11-18 11:51:00');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `excerpt`, `image`, `user_id`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to Our Blog', NULL, 'This is the first post on our amazing blog. We are excited to share our thoughts and ideas with you.', NULL, NULL, 1, 'published', NULL, '2025-11-08 13:17:53', '2025-11-08 13:17:53'),
(2, 'Getting Started with PHP', NULL, 'PHP is a powerful server-side scripting language that powers millions of websites worldwide. In this post, we will explore the basics of PHP development.', NULL, NULL, 2, 'published', NULL, '2025-11-08 13:17:53', '2025-11-08 13:17:53'),
(3, 'Database Design Best Practices', NULL, 'Good database design is crucial for application performance. This post covers normalization, indexing, and other important concepts.', NULL, NULL, 3, 'draft', NULL, '2025-11-08 13:17:53', '2025-11-08 13:17:53'),
(4, 'Introduction to MVC Architecture', NULL, 'Model-View-Controller (MVC) is a design pattern that separates concerns in web applications. Learn how it can improve your code organization.', NULL, NULL, 1, 'published', NULL, '2025-11-08 13:17:53', '2025-11-08 13:17:53'),
(5, 'Top 10 Travel Destinations for 2024', 'top-10-travel-destinations-2024', '<p>Discover the most amazing places to visit this year...</p>', 'Explore the best travel destinations that you must visit in 2024.', NULL, 1, 'published', '2025-11-11 10:17:07', '2025-11-11 10:17:07', '2025-11-11 10:17:07'),
(6, 'How to Plan Your Perfect Vacation', 'how-to-plan-perfect-vacation', '<p>Planning a vacation can be overwhelming. Here are our top tips...</p>', 'Learn the secrets to planning an unforgettable vacation.', NULL, 1, 'published', '2025-11-11 10:17:07', '2025-11-11 10:17:07', '2025-11-11 10:17:07'),
(7, 'Budget Travel Tips and Tricks', 'budget-travel-tips', '<p>Traveling doesn\'t have to break the bank. Here\'s how to save...</p>', 'Save money while traveling with these practical tips.', NULL, 1, 'published', '2025-11-11 10:17:07', '2025-11-11 10:17:07', '2025-11-11 10:17:07'),
(8, 'Adventure Travel Guide', 'adventure-travel-guide', '<p>For thrill-seekers and adventure enthusiasts...</p>', 'Your ultimate guide to adventure travel around the world.', NULL, 1, 'published', '2025-11-11 10:17:07', '2025-11-11 10:17:07', '2025-11-11 10:17:07'),
(9, 'Cultural Experiences Around the World', 'cultural-experiences-world', '<p>Immerse yourself in different cultures...</p>', 'Discover unique cultural experiences from various countries.', NULL, 1, 'published', '2025-11-11 10:17:07', '2025-11-11 10:17:07', '2025-11-11 10:17:07');

-- --------------------------------------------------------

--
-- Structure de la table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(50) DEFAULT 'content',
  `data_source` varchar(50) DEFAULT NULL COMMENT 'Data source type: manual, database, api',
  `data_source_table` varchar(100) DEFAULT NULL COMMENT 'Database table name for data',
  `data_source_query` text DEFAULT NULL COMMENT 'Custom SQL query or API endpoint',
  `data_source_limit` int(11) DEFAULT 10 COMMENT 'Number of items to fetch',
  `data_source_order` varchar(100) DEFAULT 'id DESC' COMMENT 'Order by clause',
  `data_source_filters` text DEFAULT NULL COMMENT 'JSON filters for data',
  `html_template` longtext DEFAULT NULL,
  `css_styles` text DEFAULT NULL,
  `js_scripts` text DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Déchargement des données de la table `sections`
--

INSERT INTO `sections` (`id`, `page_id`, `name`, `slug`, `type`, `data_source`, `data_source_table`, `data_source_query`, `data_source_limit`, `data_source_order`, `data_source_filters`, `html_template`, `css_styles`, `js_scripts`, `settings`, `order_index`, `is_active`, `created_at`, `updated_at`) VALUES
(9, 1, 'Slide', 'slide', 'hero', NULL, NULL, NULL, 10, 'id DESC', NULL, '<section id=\"home\" class=\"content-section\">\r\n    <div class=\"container-fluid p-0\">\r\n        <div class=\"row no-gutters\">\r\n            <div class=\"col-12\">\r\n                <div id=\"iview-overlay\">\r\n                    <div class=\"bottom_inside_divider\"></div>\r\n                </div>\r\n                <h3 class=\"text-center text-white  font-inter-bold title-slogan\">\r\n                    Discover Madagascar with us                </h3>\r\n                <div id=\"heroCarousel\" class=\"carousel slide\" data-ride=\"carousel\"\r\n                    aria-label=\"Main Highlights Carousel\">\r\n                    <div class=\"carousel-inner\">\r\n                                                <div class=\"carousel-item active\">\r\n                            <img src=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp\"\r\n                                srcset=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 480w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 800w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 1200w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 1920w\"\r\n                                sizes=\"(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px\"\r\n                                class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\" width=\"1920\"\r\n                                height=\"700\" type=\"image/webp\" decoding=\"async\" fetchpriority=\"high\">\r\n                        </div>\r\n                                                <div class=\"carousel-item \">\r\n                            <img src=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c522c563e1.35120843.webp\"\r\n                                srcset=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c522c563e1.35120843.webp 480w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c522c563e1.35120843.webp 800w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c522c563e1.35120843.webp 1200w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c522c563e1.35120843.webp 1920w\"\r\n                                sizes=\"(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px\"\r\n                                class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\" width=\"1920\"\r\n                                height=\"700\" type=\"image/webp\" decoding=\"async\" fetchpriority=\"high\">\r\n                        </div>\r\n                                                <div class=\"carousel-item \">\r\n                            <img src=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c52bd9dea5.60011147.webp\"\r\n                                srcset=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c52bd9dea5.60011147.webp 480w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c52bd9dea5.60011147.webp 800w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c52bd9dea5.60011147.webp 1200w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c52bd9dea5.60011147.webp 1920w\"\r\n                                sizes=\"(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px\"\r\n                                class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\" width=\"1920\"\r\n                                height=\"700\" type=\"image/webp\" decoding=\"async\" fetchpriority=\"high\">\r\n                        </div>\r\n                                                <div class=\"carousel-item \">\r\n                            <img src=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp\"\r\n                                srcset=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 480w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 800w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 1200w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c538f0e223.62457398.webp 1920w\"\r\n                                sizes=\"(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px\"\r\n                                class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\" width=\"1920\"\r\n                                height=\"700\" type=\"image/webp\" decoding=\"async\" fetchpriority=\"high\">\r\n                        </div>\r\n                                                <div class=\"carousel-item \">\r\n                            <img src=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c5453ee919.81854811.webp\"\r\n                                srcset=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c5453ee919.81854811.webp 480w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c5453ee919.81854811.webp 800w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c5453ee919.81854811.webp 1200w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c5453ee919.81854811.webp 1920w\"\r\n                                sizes=\"(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px\"\r\n                                class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\" width=\"1920\"\r\n                                height=\"700\" type=\"image/webp\" decoding=\"async\" fetchpriority=\"high\">\r\n                        </div>\r\n                                                <div class=\"carousel-item \">\r\n                            <img src=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c50e683136.03640728.webp\"\r\n                                srcset=\"https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c50e683136.03640728.webp 480w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c50e683136.03640728.webp 800w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c50e683136.03640728.webp 1200w, https://madagascar-green-tours.com/assets/img/uploads/slides/img_6860c50e683136.03640728.webp 1920w\"\r\n                                sizes=\"(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 1920px\"\r\n                                class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\" width=\"1920\"\r\n                                height=\"700\" type=\"image/webp\" decoding=\"async\" fetchpriority=\"high\">\r\n                        </div>\r\n                                            </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</section>', '.section-wrapper {\r\n    padding: 3rem 0;\r\n}\r\n\r\n.container {\r\n    max-width: 1200px;\r\n    margin: 0 auto;\r\n    padding: 0 1rem;\r\n}', '// Section JavaScript\r\nconsole.log(\'Section loaded\');', '{}', 1, 1, '2025-11-15 13:56:25', '2025-11-15 13:56:25'),
(10, 6, 'List tour', 'list-tour', 'content', NULL, NULL, NULL, 10, 'id DESC', NULL, '<div class=\"section-wrapper\">\r\n    <div class=\"container\">\r\n        {{ content }}\r\n        <div class=\"row\">\r\n        <items name=\"tour\" template=\"tour-template\" limit=\"13\" />\r\n        </div>\r\n    </div>\r\n</div>', '.section-wrapper {\r\n    padding: 3rem 0;\r\n}\r\n\r\n.container {\r\n    max-width: 1200px;\r\n    margin: 0 auto;\r\n    padding: 0 1rem;\r\n}', '// Section JavaScript\r\nconsole.log(\'Section loaded\');', '{}', 1, 1, '2025-11-18 12:09:13', '2025-11-18 12:09:51');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` enum('text','textarea','number','boolean','email','url','json','image') DEFAULT 'text',
  `group` varchar(100) DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `order`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Madagascar Green Tours', 'text', 'general', 'Site Name', 'The name of your website', 1, '2025-11-08 17:41:27', '2025-11-08 16:53:05'),
(2, 'site_tagline', 'Discover the Beauty of Madagascar', 'text', 'general', 'Site Tagline', 'A short description of your site', 2, '2025-11-08 17:41:27', '2025-11-08 16:53:05'),
(3, 'site_description', 'Experience the natural wonders and unique wildlife of Madagascar with our eco-friendly tours', 'textarea', 'general', 'Site Description', 'Used for SEO meta description', 3, '2025-11-08 17:41:27', '2025-11-08 16:53:05'),
(4, 'site_keywords', 'madagascar, tours, travel, wildlife, nature, eco-tourism', 'textarea', 'general', 'Site Keywords', 'SEO keywords separated by commas', 4, '2025-11-08 17:41:27', '2025-11-08 16:53:06'),
(5, 'site_logo', '/images/logos/logo.webp', 'image', 'general', 'Site Logo', 'Main logo of the website', 5, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(6, 'site_favicon', '/uploads/settings/site_favicon_1762624385.png', 'image', 'general', 'Site Favicon', 'Browser tab icon', 6, '2025-11-08 17:41:27', '2025-11-08 16:53:06'),
(7, 'contact_email', 'info@madagascargreentours.com', 'email', 'contact', 'Contact Email', 'Main contact email address', 1, '2025-11-08 17:41:27', '2025-11-08 16:53:38'),
(8, 'contact_phone', '+261 34 71 071 00', 'text', 'contact', 'Contact Phone', 'Main contact phone number', 2, '2025-11-08 17:41:27', '2025-11-08 16:53:39'),
(9, 'contact_address', 'Antananarivo, Madagascar', 'textarea', 'contact', 'Contact Address', 'Physical address', 3, '2025-11-08 17:41:27', '2025-11-08 16:53:39'),
(10, 'contact_hours', 'Mon-Fri: 8:00 AM - 6:00 PM', 'text', 'contact', 'Business Hours', 'Operating hours', 4, '2025-11-08 17:41:27', '2025-11-08 16:53:39'),
(11, 'social_facebook', 'https://facebook.com/madagascargreentours', 'url', 'social', 'Facebook URL', 'Facebook page link', 1, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(12, 'social_twitter', 'https://twitter.com/mgt_tours', 'url', 'social', 'Twitter URL', 'Twitter profile link', 2, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(13, 'social_instagram', 'https://instagram.com/madagascargreentours', 'url', 'social', 'Instagram URL', 'Instagram profile link', 3, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(14, 'social_youtube', 'https://youtube.com/@madagascargreentours', 'url', 'social', 'YouTube URL', 'YouTube channel link', 4, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(15, 'social_linkedin', 'https://linkedin.com/company/madagascar-green-tours', 'url', 'social', 'LinkedIn URL', 'LinkedIn company page', 5, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(16, 'smtp_host', 'smtp.gmail.com', 'text', 'email', 'SMTP Host', 'Mail server hostname', 1, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(17, 'smtp_port', '587', 'number', 'email', 'SMTP Port', 'Mail server port', 2, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(18, 'smtp_username', '', 'text', 'email', 'SMTP Username', 'Email account username', 3, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(19, 'smtp_password', '', 'text', 'email', 'SMTP Password', 'Email account password (encrypted)', 4, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(20, 'smtp_encryption', 'tls', 'text', 'email', 'SMTP Encryption', 'TLS or SSL', 5, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(21, 'mail_from_address', 'noreply@madagascargreentours.com', 'email', 'email', 'From Email', 'Default sender email', 6, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(22, 'mail_from_name', 'Madagascar Green Tours', 'text', 'email', 'From Name', 'Default sender name', 7, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(23, 'seo_meta_title', 'Madagascar Green Tours - Eco-Friendly Tours & Travel', 'text', 'seo', 'Default Meta Title', 'Default page title for SEO', 1, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(24, 'seo_meta_description', 'Explore Madagascar with our eco-friendly tours. Discover unique wildlife, pristine beaches, and rich culture.', 'textarea', 'seo', 'Default Meta Description', 'Default meta description', 2, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(25, 'seo_og_image', '/images/og-image.jpg', 'image', 'seo', 'Open Graph Image', 'Default social sharing image', 3, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(26, 'google_analytics_id', '', 'text', 'seo', 'Google Analytics ID', 'GA tracking ID (e.g., G-XXXXXXXXXX)', 4, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(27, 'google_site_verification', '', 'text', 'seo', 'Google Site Verification', 'Google Search Console verification code', 5, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(28, 'theme_primary_color', '#2ecc71', 'text', 'appearance', 'Primary Color', 'Main theme color (hex)', 1, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(29, 'theme_secondary_color', '#27ae60', 'text', 'appearance', 'Secondary Color', 'Secondary theme color (hex)', 2, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(30, 'items_per_page', '12', 'number', 'appearance', 'Items Per Page', 'Number of items to show per page', 3, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(31, 'date_format', 'Y-m-d', 'text', 'appearance', 'Date Format', 'PHP date format', 4, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(32, 'time_format', 'H:i:s', 'text', 'appearance', 'Time Format', 'PHP time format', 5, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(33, 'maintenance_mode', '0', 'boolean', 'system', 'Maintenance Mode', 'Enable maintenance mode', 1, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(34, 'maintenance_message', 'We are currently performing maintenance. Please check back soon.', 'textarea', 'system', 'Maintenance Message', 'Message shown during maintenance', 2, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(35, 'cache_enabled', '1', 'boolean', 'system', 'Enable Cache', 'Enable application caching', 3, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(36, 'debug_mode', '0', 'boolean', 'system', 'Debug Mode', 'Show detailed error messages', 4, '2025-11-08 17:41:27', '2025-11-08 17:41:27'),
(37, 'timezone', 'Indian/Antananarivo', 'text', 'system', 'Timezone', 'Application timezone', 5, '2025-11-08 17:41:27', '2025-11-08 17:41:27');

-- --------------------------------------------------------

--
-- Structure de la table `slides`
--

CREATE TABLE `slides` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('draft','active','inactive') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `slides`
--

INSERT INTO `slides` (`id`, `title`, `subtitle`, `description`, `image`, `link_url`, `button_text`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Home slide', 'hhello', '', 'slides/2020-10-22.webp', '', '', 0, 'active', '2025-11-15 11:16:32', '2025-11-15 11:16:48');

-- --------------------------------------------------------

--
-- Structure de la table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `html_content` longtext DEFAULT NULL,
  `css_content` longtext DEFAULT NULL,
  `js_content` longtext DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `templates`
--

INSERT INTO `templates` (`id`, `name`, `slug`, `description`, `html_content`, `css_content`, `js_content`, `thumbnail`, `is_default`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'MGT Template', 'mgt-template', 'This Madagascar Green Tours template', '<!DOCTYPE html>\r\n<html lang=\"fr\">\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>{{ page_title }}</title>\r\n    <!-- Latest compiled and minified CSS -->\r\n    <link rel=\"stylesheet\" href=\"/css/bootstrap.min.css\">\r\n    <link rel=\"stylesheet\" href=\"/css/styles.css\">\r\n    <meta name=\"description\" content=\"{{ meta_description }}\">\r\n    <meta name=\"keywords\" content=\"{{ meta_keywords }}\">\r\n    <meta name=\"author\" content=\"Madagascar Green Tours\">\r\n    <meta name=\"robots\" content=\"index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1\">\r\n    <!-- Performance optimization meta tags -->\r\n    <meta http-equiv=\"Cache-Control\" content=\"max-age=86400\">\r\n    <meta name=\"theme-color\" content=\"#4CAF50\">\r\n     <link rel=\"canonical\" href=\"{{ current_path }}\">\r\n     <!-- Font Awesome CSS -->\r\n    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css\">\r\n\r\n    <!-- Favicon and App Icons -->\r\n    <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"{{ app_url }}/img/logos/favicon.png\">\r\n    <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"{{ app_url }}/img/logos/favicon-32x32.png\">\r\n    <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"{{ app_url }}/img/logos/favicon-16x16.png\">\r\n    <link rel=\"manifest\" href=\"{{ app_url }}/site.webmanifest\">\r\n    <!-- Open Graph / Facebook Meta Tags -->\r\n    <meta property=\"og:type\" content=\"website\">\r\n    <meta property=\"og:url\" content=\"{{ current_path }}\">\r\n    <meta property=\"og:title\" content=\"{{ page_title }}\">\r\n    <meta property=\"og:description\" content=\"{{ meta_description }}\">\r\n    <meta property=\"og:image\" content=\"{{ app_url }}{{ featured_image }}\">\r\n    <meta property=\"og:image:type\" content=\"image/webp\">\r\n    <meta property=\"og:image:width\" content=\"1200\">\r\n    <meta property=\"og:image:height\" content=\"630\">\r\n    <meta property=\"og:locale\" content=\"en_US\"><!-- Don\'t check here if you have internet -->\r\n    <meta property=\"og:site_name\" content=\"{{ site_name }}\">\r\n\r\n    <!-- Twitter Card Meta Tags -->\r\n    <meta name=\"twitter:card\" content=\"summary_large_image\">\r\n    <meta name=\"twitter:site\" content=\"@MGTours\">\r\n    <meta name=\"twitter:title\" content=\"{{ meta_title }}\">\r\n    <meta name=\"twitter:description\" content=\"{{ meta_description }}\">\r\n    <meta name=\"twitter:image\" content=\"{{ app_url }}{{ featured_image }}\">\r\n    <!-- Alternate Language Links -->\r\n    <link rel=\"alternate\" hreflang=\"en\" href=\"{{ current_path }}\">\r\n    <link rel=\"alternate\" hreflang=\"es\" href=\"{{ current_path_es }}\">\r\n    <link rel=\"alternate\" hreflang=\"x-default\" href=\"{{ current_path }}\">\r\n    \r\n    \r\n</head>\r\n<body>\r\n    <a href=\"#main-content\" class=\"skip-link sr-only sr-only-focusable\">Skip to main content</a>\r\n    <main id=\"main-content\">\r\n        <nav class=\"navbar navbar-expand-lg navbar-light \" id=\"mainNav\">\r\n        <div class=\"container\">\r\n            <a class=\"navbar-brand\" href=\"https://madagascar-green-tours.com/\">\r\n                <picture>\r\n                    <img src=\"/img/logos/logo-400.png\" alt=\"{{ site_name }} Travel Logo\" class=\"navbar-logo\" width=\"180\"\r\n                        height=\"63\" fetchpriority=\"high\">\r\n                </picture>\r\n            </a>\r\n            <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\"\r\n                aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\r\n                <span class=\"navbar-toggler-icon\"></span>\r\n            </button>\r\n            <div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">\r\n                {{ menu_items }}\r\n            </div>\r\n        </div>\r\n        </nav>\r\n        {{ page_sections }}\r\n    \r\n    </main>\r\n    <footer>\r\n        <p>&copy; 2024 {{ site_name }}</p>\r\n    </footer>\r\n    <script src=\"/js/jquery-3.2.1.slim.min.js\"\r\n            integrity=\"sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN\" crossorigin=\"anonymous\"\r\n            defer></script>\r\n        <script src=\"/js/popper.min.js\"\r\n            integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"\r\n            defer></script>\r\n        <script src=\"/js/bootstrap.min.js\"\r\n            integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"\r\n            defer></script>\r\n    {{ custom_js }}\r\n\r\n    <!-- Optimized JavaScript with defer attribute -->\r\n        <script defer>\r\n             function closeElement() {\r\n                \r\n                var element = $(\'.floating-chat\');\r\n    element.find(\'.chat\').removeClass(\'enter\').hide();\r\n    setTimeout(function() {\r\n        element.find(\'.chat\').removeClass(\'enter\').show();\r\n        $(\'.floating-chat\').removeClass(\'expand\');\r\n    }, 50);\r\n}\r\n            document.addEventListener(\'DOMContentLoaded\', function () {\r\n                // Toggle fadeIn class for navbar collapse\r\n                document.querySelector(\'.navbar-toggler\').addEventListener(\'click\', function () {\r\n                    document.querySelector(\'.navbar-collapse\').classList.toggle(\'fadeIn\');\r\n                });\r\n               \r\n\r\n                // Navbar scroll animation\r\n                window.addEventListener(\'scroll\', function () {\r\n                    var scroll = window.scrollY;\r\n                    var navbar = document.getElementById(\'mainNav\');\r\n                    var navbarSupportedContent = document.querySelector(\"#navbarSupportedContent ul\");\r\n\r\n                    // Add/remove classes based on scroll position\r\n                    if (scroll > 80) { // Increased threshold for the transition\r\n                        navbar.classList.add(\'navbar-scrolled\');\r\n                        document.body.classList.add(\'scrolled\');\r\n                        navbarSupportedContent.classList.remove(\'header-styled\');\r\n                    } else {\r\n                        navbar.classList.remove(\'navbar-scrolled\');\r\n                        navbarSupportedContent.classList.add(\'header-styled\');\r\n                        document.body.classList.remove(\'scrolled\');\r\n                    }\r\n                });\r\n\r\n                // Initialize navbar state on page load\r\n                if (window.scrollY > 100) {\r\n                    document.getElementById(\'mainNav\').classList.add(\'navbar-scrolled\');\r\n                    document.body.classList.add(\'scrolled\');\r\n                }\r\n\r\n                // Smooth scrolling for anchor links (excluding dropdown toggles on mobile)\r\n                document.querySelectorAll(\'a.nav-link\').forEach(function (link) {\r\n                    link.addEventListener(\'click\', function (event) {\r\n                        // Skip if this is a dropdown toggle on mobile\r\n                        var isMobile = window.innerWidth < 992;\r\n                        if (this.classList.contains(\'dropdown-toggle\') && isMobile) {\r\n                            return;\r\n                        }\r\n                        if (this.hash !== \"\") {\r\n                            event.preventDefault();\r\n                            var hash = this.hash;\r\n\r\n                            // Smooth scroll to the target\r\n                            document.querySelector(hash).scrollIntoView({\r\n                                behavior: \'smooth\'\r\n                            });\r\n                        }\r\n                    });\r\n                });\r\n\r\n                // Initialize dropdown functionality for mobile devices\r\n                var isMobile = false;\r\n                if (window.matchMedia(\"(max-width: 991.98px)\").matches) {\r\n                    isMobile = true;\r\n                }\r\n\r\n                // Handle window resize events\r\n                $(window).resize(function () {\r\n                    if (window.matchMedia(\"(max-width: 991.98px)\").matches) {\r\n                        isMobile = true;\r\n                    } else {\r\n                        isMobile = false;\r\n                        // Close any open dropdowns when switching to desktop\r\n                        $(\'.dropdown-menu\').removeClass(\'show\');\r\n                    }\r\n                });\r\n\r\n                // For mobile: toggle dropdown on click\r\n                $(\'.dropdown-toggle\').on(\'click\', function (e) {\r\n                    if (isMobile) {\r\n                        e.preventDefault();\r\n                        e.stopPropagation();\r\n                        $(this).next(\'.dropdown-menu\').toggleClass(\'show\');\r\n                    }\r\n                });\r\n\r\n                // Close dropdown when clicking outside\r\n                $(document).on(\'click\', function (e) {\r\n                    if (isMobile && !$(e.target).closest(\'.dropdown\').length) {\r\n                        $(\'.dropdown-menu\').removeClass(\'show\');\r\n                    }\r\n                });\r\n\r\n                var element = $(\'.floating-chat\');\r\nvar myStorage = localStorage;\r\n\r\nif (!myStorage.getItem(\'chatID\')) {\r\n    myStorage.setItem(\'chatID\', createUUID());\r\n}\r\n\r\nsetTimeout(function() {\r\n    element.addClass(\'enter\');\r\n}, 1000);\r\n\r\nelement.click(openElement);\r\n\r\nfunction openElement() {\r\n    var messages = element.find(\'.messages\');\r\n    var textInput = element.find(\'.text-box\');\r\n    element.find(\'>i\').hide();\r\n    element.addClass(\'expand\');\r\n    element.find(\'.chat\').addClass(\'enter\');\r\n    var strLength = textInput.val().length * 2;\r\n    textInput.keydown(onMetaAndEnter).prop(\"disabled\", false).focus();\r\n    element.off(\'click\', openElement);\r\n    element.find(\'.user-bar div\').click(closeElement);\r\n    element.find(\'#sendMessage\').click(sendNewMessage);\r\n    messages.scrollTop(messages.prop(\"scrollHeight\"));\r\n}\r\n\r\nfunction closeElement() {\r\n    element.find(\'.chat\').removeClass(\'enter\').hide();\r\n    setTimeout(function() {\r\n        element.find(\'.chat\').removeClass(\'enter\').show();\r\n        $(\'.floating-chat\').removeClass(\'expand\');\r\n    }, 50);\r\n    element.click(openElement);\r\n}\r\n\r\nfunction createUUID() {\r\n    // http://www.ietf.org/rfc/rfc4122.txt\r\n    var s = [];\r\n    var hexDigits = \"0123456789abcdef\";\r\n    for (var i = 0; i < 36; i++) {\r\n        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);\r\n    }\r\n    s[14] = \"4\"; // bits 12-15 of the time_hi_and_version field to 0010\r\n    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01\r\n    s[8] = s[13] = s[18] = s[23] = \"-\";\r\n\r\n    var uuid = s.join(\"\");\r\n    return uuid;\r\n}\r\n\r\nfunction sendNewMessage() {\r\n    var userInput = $(\'.text-box\');\r\n    var newMessage = userInput.html().replace(/\\<div\\>|\\<br.*?\\>/ig, \'\\n\').replace(/\\<\\/div\\>/g, \'\').trim().replace(/\\n/g, \'<br>\');\r\n\r\n    if (!newMessage) return;\r\n\r\n    var messagesContainer = $(\'.messages\');\r\n\r\n    messagesContainer.append([\r\n        \'<li class=\"self\">\',\r\n        newMessage,\r\n        \'</li>\'\r\n    ].join(\'\'));\r\n\r\n    // clean out old message\r\n    userInput.html(\'\');\r\n    // focus on input\r\n    userInput.focus();\r\n\r\n    messagesContainer.finish().animate({\r\n        scrollTop: messagesContainer.prop(\"scrollHeight\")\r\n    }, 250);\r\n}\r\n\r\nfunction onMetaAndEnter(event) {\r\n    if ((event.metaKey || event.ctrlKey) && event.keyCode == 13) {\r\n        sendNewMessage();\r\n    }\r\n}\r\n\r\n\r\n            });\r\n\r\n        </script>\r\n        \r\n<script>\r\n/*\r\n    //<![CDATA[\r\nvar show_msg = \'1\';\r\n\r\nif (show_msg !== \'0\') {\r\n  var options = {view_src: \"View Source is disabled!\", inspect_elem: \"Inspect Element is disabled!\", right_click: \"Right click is disabled!\", copy_cut_paste_content: \"Cut/Copy/Paste is disabled!\", image_drop: \"Image Drag-n-Drop is disabled!\" }\r\n} else {\r\n  var options = \'\';\r\n}\r\n\r\n    function nocontextmenu(e) { return false; }\r\n    document.oncontextmenu = nocontextmenu;\r\n    document.ondragstart = function() { return false;}\r\n\r\ndocument.onmousedown = function (event) {\r\n  event = (event || window.event);\r\n  if (event.keyCode === 123) {\r\n    if (show_msg !== \'0\') {show_toast(\'inspect_elem\');}\r\n    return false;\r\n  }\r\n}\r\ndocument.onkeydown = function (event) {\r\n  event = (event || window.event);\r\n  //alert(event.keyCode);   return false;\r\n  if (event.keyCode === 123 ||\r\n      event.ctrlKey && event.shiftKey && event.keyCode === 73 ||\r\n      event.ctrlKey && event.shiftKey && event.keyCode === 75) {\r\n    if (show_msg !== \'0\') {show_toast(\'inspect_elem\');}\r\n    return false;\r\n  }\r\n  if (event.ctrlKey && event.keyCode === 85) {\r\n    if (show_msg !== \'0\') {show_toast(\'view_src\');}\r\n    return false;\r\n  }\r\n}\r\nfunction addMultiEventListener(element, eventNames, listener) {\r\n  var events = eventNames.split(\' \');\r\n  for (var i = 0, iLen = events.length; i < iLen; i++) {\r\n    element.addEventListener(events[i], function (e) {\r\n      e.preventDefault();\r\n      if (show_msg !== \'0\') {\r\n        show_toast(listener);\r\n      }\r\n    });\r\n  }\r\n}\r\naddMultiEventListener(document, \'contextmenu\', \'right_click\');\r\naddMultiEventListener(document, \'cut copy paste print\', \'copy_cut_paste_content\');\r\naddMultiEventListener(document, \'drag drop\', \'image_drop\');\r\nfunction show_toast(text) {\r\n  var x = document.getElementById(\"amm_drcfw_toast_msg\");\r\n  x.innerHTML = eval(\'options.\' + text);\r\n  x.className = \"show\";\r\n  setTimeout(function () {\r\n    x.className = x.className.replace(\"show\", \"\")\r\n  }, 3000);\r\n}\r\n//]]>\r\n*/\r\n</script>\r\n<style type=\"text/css\">\r\n/*\r\nbody * :not(input):not(textarea){\r\n    user-select:none !important; \r\n    -webkit-touch-callout: none !important;  \r\n    -webkit-user-select: none !important; \r\n    -moz-user-select:none !important; \r\n    -khtml-user-select:none !important; \r\n    -ms-user-select: none !important;\r\n    }\r\n    #amm_drcfw_toast_msg{\r\n        visibility:hidden;\r\n        min-width:250px;\r\n        font-family: Arial, \"Helvetica Neue\", Helvetica, sans-serif;\r\n        margin-left:-125px;\r\n        background-color:#333;\r\n        color:#fff;\r\n        text-align:center;\r\n        border-radius:25px;\r\n        padding:4px;\r\n        position:fixed;\r\n        z-index:999;\r\n        left:50%;\r\n        bottom:30px;\r\n        font-size:17px\r\n    }\r\n    #amm_drcfw_toast_msg.show{\r\n        visibility:visible;\r\n        -webkit-animation:fadein .5s,fadeout .5s 2.5s;\r\n        animation:fadein .5s,fadeout .5s 2.5s\r\n    }\r\n    @-webkit-keyframes fadein{\r\n        from{\r\n            bottom:0;\r\n            opacity:0\r\n        }\r\n        to{\r\n            bottom:30px;\r\n            opacity:1\r\n        }\r\n    }\r\n    @keyframes fadein{\r\n        from{\r\n            bottom:0;\r\n            opacity:0\r\n        }\r\n        to{\r\n            bottom:30px;\r\n            opacity:1\r\n        }\r\n    }\r\n    @-webkit-keyframes fadeout{\r\n        from{\r\n            bottom:30px;\r\n            opacity:1\r\n        }\r\n        to{\r\n            bottom:0;\r\n            opacity:0\r\n        }\r\n    }\r\n    @keyframes fadeout{\r\n        from{\r\n            bottom:30px;\r\n            opacity:1\r\n        }\r\n        to{\r\n            bottom:0;\r\n            opacity:0\r\n        }\r\n    }*/\r\n</style>\r\n\r\n\r\n\r\n\r\n<div id=\"amm_drcfw_toast_msg\"></div>\r\n    \r\n</body>\r\n</html>', '', '', '/uploads/templates/1762831103_mgt-template-design.png', 1, 'active', 1, '2025-11-09 14:11:03', '2025-11-19 05:45:02'),
(7, 'Template pro', 'mgt-template-pro', 'This Madagascar Green Tours template', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n    <head>\r\n        <meta charset=\"UTF-8\">\r\n        <title>{{ meta_title }}</title>\r\n\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <meta name=\"description\" content=\"{{ meta_description }}\">\r\n        <meta name=\"keywords\" content=\"{{ meta_keywords }}\">\r\n        <meta name=\"author\" content=\"{{ site_name  }}\">\r\n        <meta name=\"theme-color\" content=\"#4CAF50\">\r\n    \r\n        <!-- Open Graph / Facebook -->\r\n        <meta property=\"og:type\" content=\"website\">\r\n        <meta property=\"og:title\" content=\"{{ meta_title }}\">\r\n        <meta property=\"og:description\" content=\"{{ meta_description }}\">\r\n        <meta property=\"og:image\" content=\"{{ app_url }}{{ featured_image }}\">\r\n        <!-- Performance optimization meta tags -->\r\n        <meta http-equiv=\"Cache-Control\" content=\"max-age=86400\">\r\n        <meta name=\"theme-color\" content=\"#4CAF50\">\r\n        <link rel=\"canonical\" href=\"{{ current_path }}\">\r\n     \r\n        <!-- Latest compiled and minified CSS -->\r\n        <link rel=\"stylesheet\" href=\"/css/bootstrap.min.css\">\r\n        <link rel=\"stylesheet\" href=\"/css/styles.css\">\r\n        <!-- Font Awesome CSS -->\r\n        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css\">\r\n        <!-- Favicon and App Icons -->\r\n        <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"{{ app_url }}/img/logos/favicon.png\">\r\n        <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"{{ app_url }}/img/logos/favicon-32x32.png\">\r\n        <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"{{ app_url }}/img/logos/favicon-16x16.png\">\r\n        <link rel=\"manifest\" href=\"{{ app_url }}/site.webmanifest\">\r\n    \r\n        <!-- Twitter -->\r\n        <meta name=\"twitter:card\" content=\"summary_large_image\">\r\n        <meta name=\"twitter:title\" content=\"{{ meta_title }}\">\r\n        <meta name=\"twitter:description\" content=\"{{ meta_description }}\">\r\n    \r\n        <!-- Preconnect to external domains -->\r\n        <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\r\n        <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\r\n    \r\n        <!-- Alternate Language Links -->\r\n        <link rel=\"alternate\" hreflang=\"en\" href=\"{{ current_path }}\">\r\n        <link rel=\"alternate\" hreflang=\"es\" href=\"{{ current_path_es }}\">\r\n        <link rel=\"alternate\" hreflang=\"x-default\" href=\"{{ current_path }}\">\r\n    </head>\r\n    <body>\r\n        <a href=\"#main-content\" class=\"skip-link sr-only sr-only-focusable\">Skip to main content</a>\r\n        <header>\r\n            <nav class=\"navbar navbar-expand-lg navbar-light \" id=\"mainNav\">\r\n                <div class=\"container\">\r\n                    <a class=\"navbar-brand\" href=\"https://madagascar-green-tours.com/\">\r\n                        <picture>\r\n                            <img src=\"/img/logos/logo-400.png\" alt=\"{{ site_name }} Travel Logo\" class=\"navbar-logo\" width=\"180\"\r\n                            height=\"63\" fetchpriority=\"high\">\r\n                        </picture>\r\n                    </a>\r\n                    <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\"\r\n                        aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\r\n                        <span class=\"navbar-toggler-icon\"></span>\r\n                    </button>\r\n                    <div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">\r\n                        {{ menu_items }}\r\n                    </div>\r\n                </div>\r\n            </nav>\r\n        </header>\r\n        <main id=\"main-content\">\r\n            {{ page_sections }}\r\n        </main>\r\n        <footer>\r\n            <p>&copy; 2024 {{ site_name }}</p>\r\n        </footer>\r\n        <script src=\"/js/jquery-3.2.1.slim.min.js\" ></script>\r\n        <script src=\"/js/bootstrap.min.js\"></script>\r\n        {{ custom_js }}\r\n    </body>\r\n</html>', '', '', '/uploads/templates/1763537075_mgt-template-design.png', 0, 'active', 1, '2025-11-19 06:04:29', '2025-11-19 17:12:09');

-- --------------------------------------------------------

--
-- Structure de la table `template_items`
--

CREATE TABLE `template_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `model_name` varchar(100) NOT NULL COMMENT 'Model name (e.g., media, post, page, tour)',
  `html_template` longtext NOT NULL COMMENT 'HTML template with {{ $item.variable }} syntax',
  `css_styles` text DEFAULT NULL COMMENT 'Custom CSS styles for this template',
  `js_code` text DEFAULT NULL COMMENT 'Custom JavaScript code for this template',
  `variables` text DEFAULT NULL COMMENT 'JSON array of available variables [{key, label, type, default}]',
  `default_keys` varchar(500) DEFAULT NULL COMMENT 'Comma-separated list of default keys to display',
  `thumbnail` varchar(255) DEFAULT NULL COMMENT 'Preview thumbnail image path',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Is this the default template for the model',
  `status` enum('active','draft','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `template_items`
--

INSERT INTO `template_items` (`id`, `name`, `slug`, `description`, `model_name`, `html_template`, `css_styles`, `js_code`, `variables`, `default_keys`, `thumbnail`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Media Grid Template', 'media-grid-template', 'Grid layout for displaying media files with thumbnails', 'media', '<div class=\"media-item col-md-4\">\r\n  <div class=\"media-thumbnail\">\r\n    <img src=\"{{ $item.url }}\" alt=\"{{ $item.original_filename }}\" style=\"width:100%\" />\r\n  </div>\r\n  <div class=\"media-info\">\r\n    <h4>{{ $item.original_filename }}</h4>\r\n    <a href=\"{{ $item.url }}\" class=\"btn-download\" download>Download</a>\r\n  </div>\r\n</div>', '.media-item {\r\n  background: #fff;\r\n  border: 1px solid #e5e7eb;\r\n  border-radius: 8px;\r\n  padding: 1rem;\r\n  transition: all 0.3s;\r\n}\r\n.media-item:hover {\r\n  box-shadow: 0 4px 6px rgba(0,0,0,0.1);\r\n  transform: translateY(-2px);\r\n}\r\n.media-thumbnail {\r\n  width: 100%;\r\n  height: 200px;\r\n  overflow: hidden;\r\n  border-radius: 4px;\r\n  margin-bottom: 0.75rem;\r\n}\r\n.media-thumbnail img {\r\n  width: 100%;\r\n  height: 100%;\r\n  object-fit: cover;\r\n}\r\n.media-info h4 {\r\n  font-size: 1rem;\r\n  font-weight: 600;\r\n  margin-bottom: 0.5rem;\r\n  color: #1f2937;\r\n}\r\n.media-type, .media-size {\r\n  font-size: 0.875rem;\r\n  color: #6b7280;\r\n  margin-bottom: 0.25rem;\r\n}\r\n.btn-download {\r\n  display: inline-block;\r\n  margin-top: 0.5rem;\r\n  padding: 0.5rem 1rem;\r\n  background: #3b82f6;\r\n  color: white;\r\n  text-decoration: none;\r\n  border-radius: 4px;\r\n  font-size: 0.875rem;\r\n}\r\n.btn-download:hover {\r\n  background: #2563eb;\r\n}', '', '[{\"key\":\"url\",\"label\":\"URL\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"original_filename\",\"label\":\"Filename\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"file_type\",\"label\":\"File Type\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"file_size\",\"label\":\"File Size\",\"type\":\"text\",\"default\":\"\"}]', 'url,original_filename', NULL, 1, 'active', '2025-11-12 13:17:05', '2025-11-12 15:36:30'),
(2, 'Blog Post Card', 'blog-post-card', 'Modern card layout for blog posts with image and excerpt', 'post', '<article class=\"post-card col-md-4\">\r\n  <div class=\"post-image\">\r\n    <img src=\"{{ $item.featured_image }}\" alt=\"{{ $item.title }}\" style=\"width: 120px\" />\r\n  </div>\r\n  <div class=\"post-content\">\r\n    <div class=\"post-meta\">\r\n      <span class=\"post-date\">{{ $item.created_at }}</span>\r\n      <span class=\"post-author\">By {{ $item.author }}</span>\r\n    </div>\r\n    <h3 class=\"post-title\">{{ $item.title }}</h3>\r\n    <p class=\"post-excerpt\">{{ $item.excerpt }}</p>\r\n    <a href=\"/post/{{ $item.slug }}\" class=\"read-more\">Read More →</a>\r\n  </div>\r\n</article>', '.post-card {\r\n  background: white;\r\n  border-radius: 12px;\r\n  overflow: hidden;\r\n  box-shadow: 0 2px 8px rgba(0,0,0,0.08);\r\n  transition: all 0.3s;\r\n  height: 100%;\r\n  display: flex;\r\n  flex-direction: column;\r\n}\r\n.post-card:hover {\r\n  box-shadow: 0 8px 16px rgba(0,0,0,0.12);\r\n  transform: translateY(-4px);\r\n}\r\n.post-image {\r\n  width: 100%;\r\n  height: 240px;\r\n  overflow: hidden;\r\n}\r\n.post-image img {\r\n  width: 100%;\r\n  height: 100%;\r\n  object-fit: cover;\r\n  transition: transform 0.3s;\r\n}\r\n.post-card:hover .post-image img {\r\n  transform: scale(1.05);\r\n}\r\n.post-content {\r\n  padding: 1.5rem;\r\n  flex: 1;\r\n  display: flex;\r\n  flex-direction: column;\r\n}\r\n.post-meta {\r\n  display: flex;\r\n  gap: 1rem;\r\n  font-size: 0.875rem;\r\n  color: #6b7280;\r\n  margin-bottom: 0.75rem;\r\n}\r\n.post-title {\r\n  font-size: 1.25rem;\r\n  font-weight: 700;\r\n  color: #111827;\r\n  margin-bottom: 0.75rem;\r\n  line-height: 1.4;\r\n}\r\n.post-excerpt {\r\n  color: #4b5563;\r\n  line-height: 1.6;\r\n  margin-bottom: 1rem;\r\n  flex: 1;\r\n}\r\n.read-more {\r\n  color: #3b82f6;\r\n  font-weight: 600;\r\n  text-decoration: none;\r\n  display: inline-flex;\r\n  align-items: center;\r\n  transition: color 0.2s;\r\n}\r\n.read-more:hover {\r\n  color: #2563eb;\r\n}', '', '[{\"key\":\"featured_image\",\"label\":\"Featured Image\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"title\",\"label\":\"Title\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"excerpt\",\"label\":\"Excerpt\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"slug\",\"label\":\"Slug\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"created_at\",\"label\":\"Date\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"author\",\"label\":\"Author\",\"type\":\"text\",\"default\":\"\"}]', 'featured_image,title,excerpt,slug,created_at', NULL, 1, 'active', '2025-11-12 13:17:05', '2025-11-12 15:34:44'),
(4, 'Page List Item', 'page-list-item', 'Simple list item for displaying pages', 'page', '<div class=\"page-item\">\n  <div class=\"page-icon\">📄</div>\n  <div class=\"page-info\">\n    <h4 class=\"page-title\">{{ $item.title }}</h4>\n    <p class=\"page-description\">{{ $item.meta_description }}</p>\n    <a href=\"/{{ $item.slug }}\" class=\"page-link\">View Page →</a>\n  </div>\n</div>', '.page-item {\n  display: flex;\n  gap: 1rem;\n  padding: 1.5rem;\n  background: white;\n  border: 1px solid #e5e7eb;\n  border-radius: 8px;\n  transition: all 0.3s;\n}\n.page-item:hover {\n  border-color: #3b82f6;\n  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.1);\n}\n.page-icon {\n  font-size: 2rem;\n}\n.page-info {\n  flex: 1;\n}\n.page-title {\n  font-size: 1.125rem;\n  font-weight: 600;\n  color: #111827;\n  margin-bottom: 0.5rem;\n}\n.page-description {\n  color: #6b7280;\n  font-size: 0.95rem;\n  margin-bottom: 0.75rem;\n  line-height: 1.5;\n}\n.page-link {\n  color: #3b82f6;\n  text-decoration: none;\n  font-weight: 500;\n  font-size: 0.95rem;\n}\n.page-link:hover {\n  color: #2563eb;\n}', NULL, '[{\"key\":\"title\",\"label\":\"Title\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"meta_description\",\"label\":\"Description\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"slug\",\"label\":\"Slug\",\"type\":\"text\",\"default\":\"\"}]', 'title,meta_description,slug', NULL, 1, 'active', '2025-11-12 13:17:05', '2025-11-12 13:17:05'),
(5, 'Tour template', 'tour-template', 'Template', 'tour', '<div class=\"media-item col-md-4\">\r\n  <div class=\"media-thumbnail\">\r\n    <img src=\"/uploads/{{ $item.image }}\" alt=\"{{ $item.name }}\" style=\"width:100%\" />\r\n  </div>\r\n  <div class=\"media-info\">\r\n    <h4>{{ $item.name }}</h4>\r\n    <a href=\"{{ $item.slug }}\" class=\"btn-download\" down>Show</a>\r\n  </div>\r\n</div>', '', '', '[{\"key\":\"image\",\"label\":\"Image\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"name\",\"label\":\"Name\",\"type\":\"text\",\"default\":\"\"},{\"key\":\"slug\",\"label\":\"Slug\",\"type\":\"text\",\"default\":\"\"}]', 'name,image,slug', NULL, 0, 'active', '2025-11-13 09:33:07', '2025-11-13 12:17:47'),
(6, 'Slide template', 'slide-template', 'For slide in home', 'media', '<div class=\"carousel-item active\">\r\n                            <img src=\"/uploads/{{ $item.image }}\"class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\">\r\n                        </div><div class=\"carousel-item\">\r\n                            <img src=\"/uploads/{{ $item.image }}\"class=\"carousel-img w-100\" alt=\"Madagascar rainforest landscape\">\r\n                        </div>', '', '', NULL, 'image, link_url, button_text', NULL, 0, 'active', '2025-11-15 11:36:28', '2025-11-15 11:46:32');

-- --------------------------------------------------------

--
-- Structure de la table `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `language` varchar(5) NOT NULL DEFAULT 'en',
  `translation_group` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `itinerary` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `highlights` longtext DEFAULT NULL COMMENT 'JSON array of highlights',
  `price` decimal(10,2) DEFAULT NULL,
  `price_includes` longtext DEFAULT NULL COMMENT 'JSON array of inclusions',
  `price_excludes` longtext DEFAULT NULL COMMENT 'JSON array of exclusions',
  `duration_days` int(11) DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `difficulty_level` enum('easy','moderate','challenging','extreme') DEFAULT 'moderate',
  `category` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','draft') DEFAULT 'active',
  `featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tours`
--

INSERT INTO `tours` (`id`, `name`, `slug`, `language`, `translation_group`, `title`, `subtitle`, `short_description`, `description`, `itinerary`, `image`, `cover_image`, `highlights`, `price`, `price_includes`, `price_excludes`, `duration_days`, `max_participants`, `difficulty_level`, `category`, `location`, `status`, `featured`, `sort_order`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES
(1, 'Machu Picchu Adventure', 'machu-picchu-adventure', 'en', 'tour_001', 'Machu Picchu Adventure', 'Discover the Lost City of the Incas', 'Experience the wonder of Machu Picchu on this unforgettable 4-day adventure through the Sacred Valley.', 'Embark on an incredible journey to one of the New Seven Wonders of the World. This carefully crafted tour takes you through the breathtaking Sacred Valley, ancient Inca ruins, and culminates with a sunrise visit to the magnificent Machu Picchu citadel.\r\n\r\nYou\'ll explore traditional markets, meet local communities, and learn about the rich history and culture of the Inca civilization. Our expert guides will share fascinating stories and ensure you have the most authentic experience possible.', 'Day 1: Arrival in Cusco and city tour\r\nDay 2: Sacred Valley exploration\r\nDay 3: Ollantaytambo to Aguas Calientes\r\nDay 4: Machu Picchu sunrise and return', 'tours/6915d989d263f.jpg', 'tours/6915d93f4a39f.jpg', '[\"UNESCO World Heritage Site\",\"Professional bilingual guide\",\"Small group experience\",\"Sunrise at Machu Picchu\",\"Sacred Valley exploration\",\"Traditional markets visit\"]', '899.00', '[\"Professional guide\",\"All entrance fees\",\"Train tickets\",\"Hotel accommodation\",\"Daily breakfast\",\"Transportation\"]', '[\"International flights\",\"Travel insurance\",\"Lunch and dinner\",\"Personal expenses\",\"Tips for guide\"]', 4, 12, 'moderate', 'Cultural', 'Cusco, Peru', 'active', 1, 0, '', '', '', '2025-11-13 07:36:55', '2025-11-13 13:13:45'),
(2, 'Amazon Rainforest Expedition', 'amazon-rainforest-expedition', 'en', 'tour_002', 'Amazon Rainforest Expedition', 'Explore the Heart of the Amazon', 'Immerse yourself in the world\'s largest rainforest on this 5-day wildlife and nature expedition.', 'Venture deep into the Amazon rainforest for an unforgettable wildlife experience. This expedition takes you to remote areas where you\'ll encounter exotic animals, learn about medicinal plants, and experience the incredible biodiversity of this natural wonder.\n\nStay in eco-friendly lodges, take guided nature walks, enjoy canoe trips along winding rivers, and experience the magic of the rainforest at night. Perfect for nature lovers and adventure seekers.', 'Day 1: Arrival and jungle orientation\nDay 2: Wildlife spotting and canoe trip\nDay 3: Medicinal plants tour and night walk\nDay 4: Bird watching and indigenous community visit\nDay 5: Final exploration and departure', 'tours/amazon-main.jpg', 'tours/amazon-cover.jpg', '[\"Expert naturalist guide\", \"Wildlife spotting\", \"Eco-friendly accommodation\", \"Canoe expeditions\", \"Night jungle walks\", \"Indigenous community visit\"]', '1299.00', '[\"Naturalist guide\", \"Eco-lodge accommodation\", \"All meals\", \"Canoe trips\", \"Entrance fees\", \"Transportation from Iquitos\"]', '[\"Flights to Iquitos\", \"Travel insurance\", \"Alcoholic beverages\", \"Personal items\", \"Tips\"]', 5, 8, 'moderate', 'Adventure', 'Iquitos, Peru', 'active', 1, 0, NULL, NULL, NULL, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(3, 'Aventura Machu Picchu', 'aventura-machu-picchu', 'es', 'tour_001', 'Aventura Machu Picchu', 'Descubre la Ciudad Perdida de los Incas', 'Experimenta la maravilla de Machu Picchu en esta inolvidable aventura de 4 días por el Valle Sagrado.', 'Embárcate en un viaje increíble a una de las Nuevas Siete Maravillas del Mundo. Este tour cuidadosamente diseñado te lleva por el impresionante Valle Sagrado, ruinas incas ancestrales, y culmina con una visita al amanecer a la magnífica ciudadela de Machu Picchu.\n\nExplorarás mercados tradicionales, conocerás comunidades locales y aprenderás sobre la rica historia y cultura de la civilización inca. Nuestros guías expertos compartirán historias fascinantes y asegurarán que tengas la experiencia más auténtica posible.', 'Día 1: Llegada a Cusco y tour de la ciudad\nDía 2: Exploración del Valle Sagrado\nDía 3: Ollantaytambo a Aguas Calientes\nDía 4: Amanecer en Machu Picchu y regreso', 'tours/machu-picchu-main.jpg', 'tours/machu-picchu-cover.jpg', '[\"Sitio Patrimonio de la Humanidad UNESCO\", \"Guía profesional bilingüe\", \"Experiencia en grupo pequeño\", \"Amanecer en Machu Picchu\", \"Exploración del Valle Sagrado\", \"Visita a mercados tradicionales\"]', '899.00', '[\"Guía profesional\", \"Todas las entradas\", \"Boletos de tren\", \"Alojamiento en hotel\", \"Desayuno diario\", \"Transporte\"]', '[\"Vuelos internacionales\", \"Seguro de viaje\", \"Almuerzo y cena\", \"Gastos personales\", \"Propinas para el guía\"]', 4, 12, 'moderate', 'Cultural', 'Cusco, Perú', 'active', 1, 0, NULL, NULL, NULL, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(4, 'Expedición Selva Amazónica', 'expedicion-selva-amazonica', 'es', 'tour_002', 'Expedición Selva Amazónica', 'Explora el Corazón del Amazonas', 'Sumérgete en la selva tropical más grande del mundo en esta expedición de vida silvestre y naturaleza de 5 días.', 'Aventúrate profundamente en la selva amazónica para una experiencia inolvidable de vida silvestre. Esta expedición te lleva a áreas remotas donde encontrarás animales exóticos, aprenderás sobre plantas medicinales y experimentarás la increíble biodiversidad de esta maravilla natural.\n\nAlójate en lodges ecológicos, realiza caminatas guiadas por la naturaleza, disfruta de viajes en canoa por ríos serpenteantes y experimenta la magia de la selva por la noche. Perfecto para amantes de la naturaleza y buscadores de aventuras.', 'Día 1: Llegada y orientación en la selva\nDía 2: Avistamiento de vida silvestre y viaje en canoa\nDía 3: Tour de plantas medicinales y caminata nocturna\nDía 4: Observación de aves y visita a comunidad indígena\nDía 5: Exploración final y partida', 'tours/amazon-main.jpg', 'tours/amazon-cover.jpg', '[\"Guía naturalista experto\", \"Avistamiento de vida silvestre\", \"Alojamiento ecológico\", \"Expediciones en canoa\", \"Caminatas nocturnas en la selva\", \"Visita a comunidad indígena\"]', '1299.00', '[\"Guía naturalista\", \"Alojamiento en eco-lodge\", \"Todas las comidas\", \"Viajes en canoa\", \"Tarifas de entrada\", \"Transporte desde Iquitos\"]', '[\"Vuelos a Iquitos\", \"Seguro de viaje\", \"Bebidas alcohólicas\", \"Artículos personales\", \"Propinas\"]', 5, 8, 'moderate', 'Aventura', 'Iquitos, Perú', 'active', 1, 0, NULL, NULL, NULL, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(43, 'adventure-tour', 'adventure-tour', 'en', 'tour_69186a0d53346', 'Adventure tour', 'hello ', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'easy', '', '', 'draft', 0, 0, '', '', '', '2025-11-15 10:54:53', '2025-11-15 10:54:53');

-- --------------------------------------------------------

--
-- Structure de la table `tour_details`
--

CREATE TABLE `tour_details` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `activities` longtext DEFAULT NULL COMMENT 'JSON array of activities',
  `meals` varchar(255) DEFAULT NULL COMMENT 'B=Breakfast, L=Lunch, D=Dinner',
  `accommodation` varchar(255) DEFAULT NULL,
  `transport` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tour_details`
--

INSERT INTO `tour_details` (`id`, `tour_id`, `day`, `title`, `description`, `activities`, `meals`, `accommodation`, `transport`, `notes`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Arrival in Cusco', 'Welcome to the ancient capital of the Inca Empire. Upon arrival, you\'ll be transferred to your hotel for acclimatization. In the afternoon, enjoy a guided city tour visiting the Cathedral, Qorikancha Temple, and nearby ruins of Sacsayhuamán.', '[\"Airport transfer\", \"City tour\", \"Cathedral visit\", \"Qorikancha Temple\", \"Sacsayhuamán ruins\"]', 'B', 'Hotel in Cusco', 'Private transport', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(2, 1, 2, 'Sacred Valley Exploration', 'Journey through the beautiful Sacred Valley, visiting the colorful market of Pisac and the impressive fortress of Ollantaytambo. Learn about traditional weaving techniques and enjoy lunch with a local family.', '[\"Pisac market\", \"Traditional weaving demo\", \"Local family lunch\", \"Ollantaytambo fortress\", \"Sacred Valley drive\"]', 'B,L', 'Hotel in Sacred Valley', 'Private transport', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(3, 1, 3, 'Train to Aguas Calientes', 'Take the scenic train journey through the cloud forest to Aguas Calientes, the gateway to Machu Picchu. Enjoy the changing landscapes and prepare for tomorrow\'s early morning adventure.', '[\"Scenic train journey\", \"Cloud forest views\", \"Aguas Calientes arrival\", \"Equipment check\", \"Early rest\"]', 'B,D', 'Hotel in Aguas Calientes', 'Train', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(4, 1, 4, 'Machu Picchu Sunrise', 'Early morning bus ride to Machu Picchu for the spectacular sunrise. Enjoy a comprehensive guided tour of the citadel, learning about Inca architecture, astronomy, and daily life. Return to Cusco in the evening.', '[\"Sunrise at Machu Picchu\", \"Guided citadel tour\", \"Inca architecture study\", \"Photography time\", \"Return journey\"]', 'B', 'Return to Cusco', 'Bus and train', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(5, 2, 1, 'Jungle Orientation', 'Arrival in Iquitos and transfer to the eco-lodge. Introduction to the Amazon ecosystem, safety briefing, and first nature walk to get acquainted with the rainforest environment.', '[\"Airport transfer\", \"Lodge orientation\", \"Safety briefing\", \"First nature walk\", \"Equipment distribution\"]', 'L,D', 'Eco-lodge', 'Boat transfer', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(6, 2, 2, 'Wildlife Adventure', 'Full day of wildlife spotting including early morning bird watching, canoe trip along tributaries, and afternoon search for pink dolphins, sloths, and monkeys.', '[\"Dawn bird watching\", \"Canoe expedition\", \"Pink dolphin spotting\", \"Sloth observation\", \"Monkey encounters\"]', 'B,L,D', 'Eco-lodge', 'Canoe', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(7, 2, 3, 'Medicinal Plants & Night Walk', 'Learn about the medicinal properties of rainforest plants with a local shaman. Evening night walk to discover nocturnal wildlife and experience the jungle\'s nighttime symphony.', '[\"Medicinal plant tour\", \"Shaman consultation\", \"Plant preparation demo\", \"Night walk\", \"Nocturnal wildlife\"]', 'B,L,D', 'Eco-lodge', 'Walking', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(8, 2, 4, 'Indigenous Community', 'Visit a local indigenous community to learn about their traditional way of life, participate in daily activities, and enjoy traditional music and dance performances.', '[\"Community visit\", \"Traditional crafts\", \"Cultural exchange\", \"Music and dance\", \"Traditional lunch\"]', 'B,L,D', 'Eco-lodge', 'Boat and walking', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55'),
(9, 2, 5, 'Final Exploration', 'Last morning exploration focusing on any wildlife you haven\'t yet encountered. Pack up and transfer back to Iquitos for your onward journey.', '[\"Final wildlife search\", \"Photography session\", \"Lodge checkout\", \"Transfer to airport\", \"Departure\"]', 'B,L', 'Day use', 'Boat transfer', NULL, 0, '2025-11-13 07:36:55', '2025-11-13 07:36:55');

-- --------------------------------------------------------

--
-- Structure de la table `tour_photos`
--

CREATE TABLE `tour_photos` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `type` enum('gallery','itinerary','accommodation','activity') DEFAULT 'gallery',
  `day` int(11) DEFAULT NULL COMMENT 'Associated day for itinerary photos',
  `sort_order` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tour_photos`
--

INSERT INTO `tour_photos` (`id`, `tour_id`, `image`, `title`, `description`, `alt_text`, `type`, `day`, `sort_order`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 'tours/machu-picchu-sunrise.jpg', 'Machu Picchu at Sunrise', 'The iconic view of Machu Picchu citadel at sunrise', NULL, 'gallery', NULL, 1, 1, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(2, 1, 'tours/cusco-cathedral.jpg', 'Cusco Cathedral', 'The beautiful colonial cathedral in Cusco main square', NULL, 'itinerary', 1, 2, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(3, 1, 'tours/sacred-valley.jpg', 'Sacred Valley Landscape', 'Panoramic view of the Sacred Valley', NULL, 'itinerary', 2, 3, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(4, 1, 'tours/ollantaytambo.jpg', 'Ollantaytambo Fortress', 'Ancient Inca fortress in Ollantaytambo', NULL, 'itinerary', 2, 4, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(5, 1, 'tours/train-journey.jpg', 'Train to Machu Picchu', 'Scenic train journey through cloud forest', NULL, 'itinerary', 3, 5, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(6, 2, 'tours/amazon-wildlife.jpg', 'Amazon Wildlife', 'Colorful birds and exotic animals of the Amazon', NULL, 'gallery', NULL, 1, 1, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(7, 2, 'tours/eco-lodge.jpg', 'Eco Lodge', 'Sustainable accommodation in the heart of the rainforest', NULL, 'accommodation', NULL, 2, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(8, 2, 'tours/canoe-trip.jpg', 'Canoe Expedition', 'Exploring Amazon tributaries by traditional canoe', NULL, 'activity', 2, 3, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(9, 2, 'tours/medicinal-plants.jpg', 'Medicinal Plants', 'Learning about traditional plant medicine', NULL, 'activity', 3, 4, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56'),
(10, 2, 'tours/indigenous-community.jpg', 'Indigenous Community', 'Cultural exchange with local community', NULL, 'activity', 4, 5, 0, '2025-11-13 07:36:56', '2025-11-13 07:36:56');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', '$2y$10$.VEl1MMbVoA4hT1A1yRfAumRxu/01wBKrqQgCM2QNPWXqDHgbbsq6', '2025-11-08 13:17:52', '2025-11-08 13:17:52'),
(2, 'Georginot Armelin', 'john@example.com', '$2y$10$bOTizkLvvP66iOIk9jkIR.vdaG9Way..QGOlLXh/B5q5ajkUX65DG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Jane Smith', 'jane@example.com', '$2y$10$KE5lVeCfNYn3kvg9X/ZmVemv2VTqBLy1hktv6Qkt2Nv7a7LNZMNTm', '2025-11-08 13:17:52', '2025-11-08 13:17:52'),
(4, 'Test User', 'test@example.com', '$2y$10$0AaHKYXuboJ8yNj2rMac2Oy3JG2hNCuob5LoXeHnLoosTOZQ85Y7O', '2025-11-08 13:17:52', '2025-11-08 13:17:52');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_section` (`section_id`),
  ADD KEY `idx_language` (`language`),
  ADD KEY `idx_order` (`order_index`);

--
-- Index pour la table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_folder_id` (`folder_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_mime_type` (`mime_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Index pour la table `media_folders`
--
ALTER TABLE `media_folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_slug` (`slug`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_order` (`order`);

--
-- Index pour la table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_template` (`template_id`),
  ADD KEY `idx_parent` (`parent_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_key` (`key`),
  ADD KEY `idx_group` (`group`),
  ADD KEY `idx_order` (`order`);

--
-- Index pour la table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Index pour la table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `template_items`
--
ALTER TABLE `template_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `model_name` (`model_name`),
  ADD KEY `status` (`status`),
  ADD KEY `is_default` (`is_default`),
  ADD KEY `idx_template_model_default` (`model_name`,`is_default`,`status`);

--
-- Index pour la table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_language` (`slug`,`language`),
  ADD KEY `language` (`language`),
  ADD KEY `translation_group` (`translation_group`),
  ADD KEY `status` (`status`),
  ADD KEY `featured` (`featured`),
  ADD KEY `category` (`category`),
  ADD KEY `idx_tours_slug_lang` (`slug`,`language`),
  ADD KEY `idx_tours_translation_group` (`translation_group`);

--
-- Index pour la table `tour_details`
--
ALTER TABLE `tour_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `day` (`day`),
  ADD KEY `idx_tour_details_tour_day` (`tour_id`,`day`);

--
-- Index pour la table `tour_photos`
--
ALTER TABLE `tour_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `type` (`type`),
  ADD KEY `day` (`day`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `idx_tour_photos_tour_type` (`tour_id`,`type`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `media_folders`
--
ALTER TABLE `media_folders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `template_items`
--
ALTER TABLE `template_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `tour_details`
--
ALTER TABLE `tour_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `tour_photos`
--
ALTER TABLE `tour_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pages_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pages_ibfk_3` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

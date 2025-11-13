-- Tours System Migration
-- Creates tours, tour_details, and tour_photos tables with multi-language support

-- Main tours table with multi-language support
CREATE TABLE IF NOT EXISTS `tours` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
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
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug_language` (`slug`, `language`),
    KEY `language` (`language`),
    KEY `translation_group` (`translation_group`),
    KEY `status` (`status`),
    KEY `featured` (`featured`),
    KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour details table for daily itinerary
CREATE TABLE IF NOT EXISTS `tour_details` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
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
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `tour_id` (`tour_id`),
    KEY `day` (`day`),
    FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tour photos table for tour images
CREATE TABLE IF NOT EXISTS `tour_photos` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `tour_id` int(11) NOT NULL,
    `image` varchar(255) NOT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `alt_text` varchar(255) DEFAULT NULL,
    `type` enum('gallery','itinerary','accommodation','activity') DEFAULT 'gallery',
    `day` int(11) DEFAULT NULL COMMENT 'Associated day for itinerary photos',
    `sort_order` int(11) DEFAULT 0,
    `is_featured` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `tour_id` (`tour_id`),
    KEY `type` (`type`),
    KEY `day` (`day`),
    KEY `is_featured` (`is_featured`),
    FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample tours data (English and Spanish versions)
INSERT INTO `tours` (`name`, `slug`, `language`, `translation_group`, `title`, `subtitle`, `short_description`, `description`, `itinerary`, `image`, `cover_image`, `highlights`, `price`, `price_includes`, `price_excludes`, `duration_days`, `max_participants`, `difficulty_level`, `category`, `location`, `status`, `featured`) VALUES
-- English Tours
('Machu Picchu Adventure', 'machu-picchu-adventure', 'en', 'tour_001', 'Machu Picchu Adventure', 'Discover the Lost City of the Incas', 'Experience the wonder of Machu Picchu on this unforgettable 4-day adventure through the Sacred Valley.', 'Embark on an incredible journey to one of the New Seven Wonders of the World. This carefully crafted tour takes you through the breathtaking Sacred Valley, ancient Inca ruins, and culminates with a sunrise visit to the magnificent Machu Picchu citadel.\n\nYou''ll explore traditional markets, meet local communities, and learn about the rich history and culture of the Inca civilization. Our expert guides will share fascinating stories and ensure you have the most authentic experience possible.', 'Day 1: Arrival in Cusco and city tour\nDay 2: Sacred Valley exploration\nDay 3: Ollantaytambo to Aguas Calientes\nDay 4: Machu Picchu sunrise and return', 'tours/machu-picchu-main.jpg', 'tours/machu-picchu-cover.jpg', '["UNESCO World Heritage Site", "Professional bilingual guide", "Small group experience", "Sunrise at Machu Picchu", "Sacred Valley exploration", "Traditional markets visit"]', 899.00, '["Professional guide", "All entrance fees", "Train tickets", "Hotel accommodation", "Daily breakfast", "Transportation"]', '["International flights", "Travel insurance", "Lunch and dinner", "Personal expenses", "Tips for guide"]', 4, 12, 'moderate', 'Cultural', 'Cusco, Peru', 'active', 1),

('Amazon Rainforest Expedition', 'amazon-rainforest-expedition', 'en', 'tour_002', 'Amazon Rainforest Expedition', 'Explore the Heart of the Amazon', 'Immerse yourself in the world''s largest rainforest on this 5-day wildlife and nature expedition.', 'Venture deep into the Amazon rainforest for an unforgettable wildlife experience. This expedition takes you to remote areas where you''ll encounter exotic animals, learn about medicinal plants, and experience the incredible biodiversity of this natural wonder.\n\nStay in eco-friendly lodges, take guided nature walks, enjoy canoe trips along winding rivers, and experience the magic of the rainforest at night. Perfect for nature lovers and adventure seekers.', 'Day 1: Arrival and jungle orientation\nDay 2: Wildlife spotting and canoe trip\nDay 3: Medicinal plants tour and night walk\nDay 4: Bird watching and indigenous community visit\nDay 5: Final exploration and departure', 'tours/amazon-main.jpg', 'tours/amazon-cover.jpg', '["Expert naturalist guide", "Wildlife spotting", "Eco-friendly accommodation", "Canoe expeditions", "Night jungle walks", "Indigenous community visit"]', 1299.00, '["Naturalist guide", "Eco-lodge accommodation", "All meals", "Canoe trips", "Entrance fees", "Transportation from Iquitos"]', '["Flights to Iquitos", "Travel insurance", "Alcoholic beverages", "Personal items", "Tips"]', 5, 8, 'moderate', 'Adventure', 'Iquitos, Peru', 'active', 1),

-- Spanish Tours (translations)
('Aventura Machu Picchu', 'aventura-machu-picchu', 'es', 'tour_001', 'Aventura Machu Picchu', 'Descubre la Ciudad Perdida de los Incas', 'Experimenta la maravilla de Machu Picchu en esta inolvidable aventura de 4 días por el Valle Sagrado.', 'Embárcate en un viaje increíble a una de las Nuevas Siete Maravillas del Mundo. Este tour cuidadosamente diseñado te lleva por el impresionante Valle Sagrado, ruinas incas ancestrales, y culmina con una visita al amanecer a la magnífica ciudadela de Machu Picchu.\n\nExplorarás mercados tradicionales, conocerás comunidades locales y aprenderás sobre la rica historia y cultura de la civilización inca. Nuestros guías expertos compartirán historias fascinantes y asegurarán que tengas la experiencia más auténtica posible.', 'Día 1: Llegada a Cusco y tour de la ciudad\nDía 2: Exploración del Valle Sagrado\nDía 3: Ollantaytambo a Aguas Calientes\nDía 4: Amanecer en Machu Picchu y regreso', 'tours/machu-picchu-main.jpg', 'tours/machu-picchu-cover.jpg', '["Sitio Patrimonio de la Humanidad UNESCO", "Guía profesional bilingüe", "Experiencia en grupo pequeño", "Amanecer en Machu Picchu", "Exploración del Valle Sagrado", "Visita a mercados tradicionales"]', 899.00, '["Guía profesional", "Todas las entradas", "Boletos de tren", "Alojamiento en hotel", "Desayuno diario", "Transporte"]', '["Vuelos internacionales", "Seguro de viaje", "Almuerzo y cena", "Gastos personales", "Propinas para el guía"]', 4, 12, 'moderate', 'Cultural', 'Cusco, Perú', 'active', 1),

('Expedición Selva Amazónica', 'expedicion-selva-amazonica', 'es', 'tour_002', 'Expedición Selva Amazónica', 'Explora el Corazón del Amazonas', 'Sumérgete en la selva tropical más grande del mundo en esta expedición de vida silvestre y naturaleza de 5 días.', 'Aventúrate profundamente en la selva amazónica para una experiencia inolvidable de vida silvestre. Esta expedición te lleva a áreas remotas donde encontrarás animales exóticos, aprenderás sobre plantas medicinales y experimentarás la increíble biodiversidad de esta maravilla natural.\n\nAlójate en lodges ecológicos, realiza caminatas guiadas por la naturaleza, disfruta de viajes en canoa por ríos serpenteantes y experimenta la magia de la selva por la noche. Perfecto para amantes de la naturaleza y buscadores de aventuras.', 'Día 1: Llegada y orientación en la selva\nDía 2: Avistamiento de vida silvestre y viaje en canoa\nDía 3: Tour de plantas medicinales y caminata nocturna\nDía 4: Observación de aves y visita a comunidad indígena\nDía 5: Exploración final y partida', 'tours/amazon-main.jpg', 'tours/amazon-cover.jpg', '["Guía naturalista experto", "Avistamiento de vida silvestre", "Alojamiento ecológico", "Expediciones en canoa", "Caminatas nocturnas en la selva", "Visita a comunidad indígena"]', 1299.00, '["Guía naturalista", "Alojamiento en eco-lodge", "Todas las comidas", "Viajes en canoa", "Tarifas de entrada", "Transporte desde Iquitos"]', '["Vuelos a Iquitos", "Seguro de viaje", "Bebidas alcohólicas", "Artículos personales", "Propinas"]', 5, 8, 'moderate', 'Aventura', 'Iquitos, Perú', 'active', 1);

-- Insert sample tour details
INSERT INTO `tour_details` (`tour_id`, `day`, `title`, `description`, `activities`, `meals`, `accommodation`, `transport`) VALUES
-- Machu Picchu Adventure (English version - ID 1)
(1, 1, 'Arrival in Cusco', 'Welcome to the ancient capital of the Inca Empire. Upon arrival, you''ll be transferred to your hotel for acclimatization. In the afternoon, enjoy a guided city tour visiting the Cathedral, Qorikancha Temple, and nearby ruins of Sacsayhuamán.', '["Airport transfer", "City tour", "Cathedral visit", "Qorikancha Temple", "Sacsayhuamán ruins"]', 'B', 'Hotel in Cusco', 'Private transport'),
(1, 2, 'Sacred Valley Exploration', 'Journey through the beautiful Sacred Valley, visiting the colorful market of Pisac and the impressive fortress of Ollantaytambo. Learn about traditional weaving techniques and enjoy lunch with a local family.', '["Pisac market", "Traditional weaving demo", "Local family lunch", "Ollantaytambo fortress", "Sacred Valley drive"]', 'B,L', 'Hotel in Sacred Valley', 'Private transport'),
(1, 3, 'Train to Aguas Calientes', 'Take the scenic train journey through the cloud forest to Aguas Calientes, the gateway to Machu Picchu. Enjoy the changing landscapes and prepare for tomorrow''s early morning adventure.', '["Scenic train journey", "Cloud forest views", "Aguas Calientes arrival", "Equipment check", "Early rest"]', 'B,D', 'Hotel in Aguas Calientes', 'Train'),
(1, 4, 'Machu Picchu Sunrise', 'Early morning bus ride to Machu Picchu for the spectacular sunrise. Enjoy a comprehensive guided tour of the citadel, learning about Inca architecture, astronomy, and daily life. Return to Cusco in the evening.', '["Sunrise at Machu Picchu", "Guided citadel tour", "Inca architecture study", "Photography time", "Return journey"]', 'B', 'Return to Cusco', 'Bus and train'),

-- Amazon Expedition (English version - ID 2)
(2, 1, 'Jungle Orientation', 'Arrival in Iquitos and transfer to the eco-lodge. Introduction to the Amazon ecosystem, safety briefing, and first nature walk to get acquainted with the rainforest environment.', '["Airport transfer", "Lodge orientation", "Safety briefing", "First nature walk", "Equipment distribution"]', 'L,D', 'Eco-lodge', 'Boat transfer'),
(2, 2, 'Wildlife Adventure', 'Full day of wildlife spotting including early morning bird watching, canoe trip along tributaries, and afternoon search for pink dolphins, sloths, and monkeys.', '["Dawn bird watching", "Canoe expedition", "Pink dolphin spotting", "Sloth observation", "Monkey encounters"]', 'B,L,D', 'Eco-lodge', 'Canoe'),
(2, 3, 'Medicinal Plants & Night Walk', 'Learn about the medicinal properties of rainforest plants with a local shaman. Evening night walk to discover nocturnal wildlife and experience the jungle''s nighttime symphony.', '["Medicinal plant tour", "Shaman consultation", "Plant preparation demo", "Night walk", "Nocturnal wildlife"]', 'B,L,D', 'Eco-lodge', 'Walking'),
(2, 4, 'Indigenous Community', 'Visit a local indigenous community to learn about their traditional way of life, participate in daily activities, and enjoy traditional music and dance performances.', '["Community visit", "Traditional crafts", "Cultural exchange", "Music and dance", "Traditional lunch"]', 'B,L,D', 'Eco-lodge', 'Boat and walking'),
(2, 5, 'Final Exploration', 'Last morning exploration focusing on any wildlife you haven''t yet encountered. Pack up and transfer back to Iquitos for your onward journey.', '["Final wildlife search", "Photography session", "Lodge checkout", "Transfer to airport", "Departure"]', 'B,L', 'Day use', 'Boat transfer');

-- Insert sample tour photos
INSERT INTO `tour_photos` (`tour_id`, `image`, `title`, `description`, `type`, `day`, `sort_order`, `is_featured`) VALUES
-- Machu Picchu photos
(1, 'tours/machu-picchu-sunrise.jpg', 'Machu Picchu at Sunrise', 'The iconic view of Machu Picchu citadel at sunrise', 'gallery', NULL, 1, 1),
(1, 'tours/cusco-cathedral.jpg', 'Cusco Cathedral', 'The beautiful colonial cathedral in Cusco main square', 'itinerary', 1, 2, 0),
(1, 'tours/sacred-valley.jpg', 'Sacred Valley Landscape', 'Panoramic view of the Sacred Valley', 'itinerary', 2, 3, 0),
(1, 'tours/ollantaytambo.jpg', 'Ollantaytambo Fortress', 'Ancient Inca fortress in Ollantaytambo', 'itinerary', 2, 4, 0),
(1, 'tours/train-journey.jpg', 'Train to Machu Picchu', 'Scenic train journey through cloud forest', 'itinerary', 3, 5, 0),

-- Amazon photos
(2, 'tours/amazon-wildlife.jpg', 'Amazon Wildlife', 'Colorful birds and exotic animals of the Amazon', 'gallery', NULL, 1, 1),
(2, 'tours/eco-lodge.jpg', 'Eco Lodge', 'Sustainable accommodation in the heart of the rainforest', 'accommodation', NULL, 2, 0),
(2, 'tours/canoe-trip.jpg', 'Canoe Expedition', 'Exploring Amazon tributaries by traditional canoe', 'activity', 2, 3, 0),
(2, 'tours/medicinal-plants.jpg', 'Medicinal Plants', 'Learning about traditional plant medicine', 'activity', 3, 4, 0),
(2, 'tours/indigenous-community.jpg', 'Indigenous Community', 'Cultural exchange with local community', 'activity', 4, 5, 0);

-- Add indexes for better performance
CREATE INDEX idx_tours_slug_lang ON tours(slug, language);
CREATE INDEX idx_tours_translation_group ON tours(translation_group);
CREATE INDEX idx_tour_details_tour_day ON tour_details(tour_id, day);
CREATE INDEX idx_tour_photos_tour_type ON tour_photos(tour_id, type);

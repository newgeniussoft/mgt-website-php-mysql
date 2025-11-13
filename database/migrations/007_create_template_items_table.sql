-- =====================================================
-- Template Items Table Migration
-- Creates table for managing item display templates
-- =====================================================

-- Create template_items table
CREATE TABLE IF NOT EXISTS `template_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `model_name` varchar(100) NOT NULL COMMENT 'Model name (e.g., media, post, page, tour)',
  `html_template` longtext NOT NULL COMMENT 'HTML template with {{ $item.variable }} syntax',
  `css_styles` text COMMENT 'Custom CSS styles for this template',
  `js_code` text COMMENT 'Custom JavaScript code for this template',
  `variables` text COMMENT 'JSON array of available variables [{key, label, type, default}]',
  `default_keys` varchar(500) COMMENT 'Comma-separated list of default keys to display',
  `thumbnail` varchar(255) COMMENT 'Preview thumbnail image path',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Is this the default template for the model',
  `status` enum('active','draft','archived') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `model_name` (`model_name`),
  KEY `status` (`status`),
  KEY `is_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default template items
INSERT INTO `template_items` (`name`, `slug`, `description`, `model_name`, `html_template`, `css_styles`, `variables`, `default_keys`, `is_default`, `status`) VALUES
('Media Grid Template', 'media-grid', 'Grid layout for displaying media files with thumbnails', 'media', 
'<div class="media-item">\n  <div class="media-thumbnail">\n    <img src="{{ $item.url }}" alt="{{ $item.original_filename }}" />\n  </div>\n  <div class="media-info">\n    <h4>{{ $item.original_filename }}</h4>\n    <p class="media-type">{{ $item.file_type }}</p>\n    <p class="media-size">{{ $item.file_size }}</p>\n    <a href="{{ $item.url }}" class="btn-download" download>Download</a>\n  </div>\n</div>',
'.media-item {\n  background: #fff;\n  border: 1px solid #e5e7eb;\n  border-radius: 8px;\n  padding: 1rem;\n  transition: all 0.3s;\n}\n.media-item:hover {\n  box-shadow: 0 4px 6px rgba(0,0,0,0.1);\n  transform: translateY(-2px);\n}\n.media-thumbnail {\n  width: 100%;\n  height: 200px;\n  overflow: hidden;\n  border-radius: 4px;\n  margin-bottom: 0.75rem;\n}\n.media-thumbnail img {\n  width: 100%;\n  height: 100%;\n  object-fit: cover;\n}\n.media-info h4 {\n  font-size: 1rem;\n  font-weight: 600;\n  margin-bottom: 0.5rem;\n  color: #1f2937;\n}\n.media-type, .media-size {\n  font-size: 0.875rem;\n  color: #6b7280;\n  margin-bottom: 0.25rem;\n}\n.btn-download {\n  display: inline-block;\n  margin-top: 0.5rem;\n  padding: 0.5rem 1rem;\n  background: #3b82f6;\n  color: white;\n  text-decoration: none;\n  border-radius: 4px;\n  font-size: 0.875rem;\n}\n.btn-download:hover {\n  background: #2563eb;\n}',
'[{"key":"url","label":"URL","type":"text","default":""},{"key":"original_filename","label":"Filename","type":"text","default":""},{"key":"file_type","label":"File Type","type":"text","default":""},{"key":"file_size","label":"File Size","type":"text","default":""}]',
'url,original_filename,file_type,file_size',
1, 'active'),

('Blog Post Card', 'blog-post-card', 'Modern card layout for blog posts with image and excerpt', 'post',
'<article class="post-card">\n  <div class="post-image">\n    <img src="{{ $item.featured_image }}" alt="{{ $item.title }}" />\n  </div>\n  <div class="post-content">\n    <div class="post-meta">\n      <span class="post-date">{{ $item.created_at }}</span>\n      <span class="post-author">By {{ $item.author }}</span>\n    </div>\n    <h3 class="post-title">{{ $item.title }}</h3>\n    <p class="post-excerpt">{{ $item.excerpt }}</p>\n    <a href="/post/{{ $item.slug }}" class="read-more">Read More →</a>\n  </div>\n</article>',
'.post-card {\n  background: white;\n  border-radius: 12px;\n  overflow: hidden;\n  box-shadow: 0 2px 8px rgba(0,0,0,0.08);\n  transition: all 0.3s;\n  height: 100%;\n  display: flex;\n  flex-direction: column;\n}\n.post-card:hover {\n  box-shadow: 0 8px 16px rgba(0,0,0,0.12);\n  transform: translateY(-4px);\n}\n.post-image {\n  width: 100%;\n  height: 240px;\n  overflow: hidden;\n}\n.post-image img {\n  width: 100%;\n  height: 100%;\n  object-fit: cover;\n  transition: transform 0.3s;\n}\n.post-card:hover .post-image img {\n  transform: scale(1.05);\n}\n.post-content {\n  padding: 1.5rem;\n  flex: 1;\n  display: flex;\n  flex-direction: column;\n}\n.post-meta {\n  display: flex;\n  gap: 1rem;\n  font-size: 0.875rem;\n  color: #6b7280;\n  margin-bottom: 0.75rem;\n}\n.post-title {\n  font-size: 1.25rem;\n  font-weight: 700;\n  color: #111827;\n  margin-bottom: 0.75rem;\n  line-height: 1.4;\n}\n.post-excerpt {\n  color: #4b5563;\n  line-height: 1.6;\n  margin-bottom: 1rem;\n  flex: 1;\n}\n.read-more {\n  color: #3b82f6;\n  font-weight: 600;\n  text-decoration: none;\n  display: inline-flex;\n  align-items: center;\n  transition: color 0.2s;\n}\n.read-more:hover {\n  color: #2563eb;\n}',
'[{"key":"featured_image","label":"Featured Image","type":"text","default":""},{"key":"title","label":"Title","type":"text","default":""},{"key":"excerpt","label":"Excerpt","type":"text","default":""},{"key":"slug","label":"Slug","type":"text","default":""},{"key":"created_at","label":"Date","type":"text","default":""},{"key":"author","label":"Author","type":"text","default":""}]',
'featured_image,title,excerpt,slug,created_at',
1, 'active'),

('Tour Package Card', 'tour-package-card', 'Attractive card for displaying tour packages', 'tour',
'<div class="tour-card">\n  <div class="tour-badge">{{ $item.duration }} Days</div>\n  <div class="tour-image">\n    <img src="{{ $item.image }}" alt="{{ $item.name }}" />\n  </div>\n  <div class="tour-details">\n    <h3 class="tour-name">{{ $item.name }}</h3>\n    <p class="tour-location">📍 {{ $item.location }}</p>\n    <p class="tour-description">{{ $item.description }}</p>\n    <div class="tour-footer">\n      <div class="tour-price">\n        <span class="price-label">From</span>\n        <span class="price-amount">${{ $item.price }}</span>\n      </div>\n      <a href="/tour/{{ $item.slug }}" class="btn-book">Book Now</a>\n    </div>\n  </div>\n</div>',
'.tour-card {\n  position: relative;\n  background: white;\n  border-radius: 16px;\n  overflow: hidden;\n  box-shadow: 0 4px 12px rgba(0,0,0,0.1);\n  transition: all 0.3s;\n}\n.tour-card:hover {\n  box-shadow: 0 12px 24px rgba(0,0,0,0.15);\n  transform: translateY(-8px);\n}\n.tour-badge {\n  position: absolute;\n  top: 1rem;\n  right: 1rem;\n  background: rgba(59, 130, 246, 0.95);\n  color: white;\n  padding: 0.5rem 1rem;\n  border-radius: 20px;\n  font-size: 0.875rem;\n  font-weight: 600;\n  z-index: 10;\n}\n.tour-image {\n  width: 100%;\n  height: 280px;\n  overflow: hidden;\n}\n.tour-image img {\n  width: 100%;\n  height: 100%;\n  object-fit: cover;\n  transition: transform 0.4s;\n}\n.tour-card:hover .tour-image img {\n  transform: scale(1.1);\n}\n.tour-details {\n  padding: 1.5rem;\n}\n.tour-name {\n  font-size: 1.5rem;\n  font-weight: 700;\n  color: #111827;\n  margin-bottom: 0.5rem;\n}\n.tour-location {\n  color: #6b7280;\n  margin-bottom: 1rem;\n  font-size: 0.95rem;\n}\n.tour-description {\n  color: #4b5563;\n  line-height: 1.6;\n  margin-bottom: 1.5rem;\n}\n.tour-footer {\n  display: flex;\n  justify-content: space-between;\n  align-items: center;\n  padding-top: 1rem;\n  border-top: 1px solid #e5e7eb;\n}\n.tour-price {\n  display: flex;\n  flex-direction: column;\n}\n.price-label {\n  font-size: 0.75rem;\n  color: #9ca3af;\n  text-transform: uppercase;\n}\n.price-amount {\n  font-size: 1.75rem;\n  font-weight: 700;\n  color: #3b82f6;\n}\n.btn-book {\n  padding: 0.75rem 1.5rem;\n  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);\n  color: white;\n  text-decoration: none;\n  border-radius: 8px;\n  font-weight: 600;\n  transition: all 0.3s;\n}\n.btn-book:hover {\n  transform: translateX(4px);\n  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);\n}',
'[{"key":"image","label":"Image","type":"text","default":""},{"key":"name","label":"Name","type":"text","default":""},{"key":"location","label":"Location","type":"text","default":""},{"key":"description","label":"Description","type":"text","default":""},{"key":"price","label":"Price","type":"text","default":""},{"key":"duration","label":"Duration","type":"text","default":""},{"key":"slug","label":"Slug","type":"text","default":""}]',
'image,name,location,description,price,duration',
1, 'active'),

('Page List Item', 'page-list-item', 'Simple list item for displaying pages', 'page',
'<div class="page-item">\n  <div class="page-icon">📄</div>\n  <div class="page-info">\n    <h4 class="page-title">{{ $item.title }}</h4>\n    <p class="page-description">{{ $item.meta_description }}</p>\n    <a href="/{{ $item.slug }}" class="page-link">View Page →</a>\n  </div>\n</div>',
'.page-item {\n  display: flex;\n  gap: 1rem;\n  padding: 1.5rem;\n  background: white;\n  border: 1px solid #e5e7eb;\n  border-radius: 8px;\n  transition: all 0.3s;\n}\n.page-item:hover {\n  border-color: #3b82f6;\n  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.1);\n}\n.page-icon {\n  font-size: 2rem;\n}\n.page-info {\n  flex: 1;\n}\n.page-title {\n  font-size: 1.125rem;\n  font-weight: 600;\n  color: #111827;\n  margin-bottom: 0.5rem;\n}\n.page-description {\n  color: #6b7280;\n  font-size: 0.95rem;\n  margin-bottom: 0.75rem;\n  line-height: 1.5;\n}\n.page-link {\n  color: #3b82f6;\n  text-decoration: none;\n  font-weight: 500;\n  font-size: 0.95rem;\n}\n.page-link:hover {\n  color: #2563eb;\n}',
'[{"key":"title","label":"Title","type":"text","default":""},{"key":"meta_description","label":"Description","type":"text","default":""},{"key":"slug","label":"Slug","type":"text","default":""}]',
'title,meta_description,slug',
1, 'active');

-- Create index for better performance
CREATE INDEX idx_template_model_default ON template_items(model_name, is_default, status);

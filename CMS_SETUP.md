# CMS Page Management System

This document provides comprehensive instructions for using the CMS (Content Management System) page management features.

## 🚀 Quick Setup

### 1. Install the CMS System

Run the installation script to create the pages tables and sample content:

```
http://localhost/your-project-path/install_cms.php
```

Or manually execute the SQL file:
```sql
-- Import the database/pages.sql file into your MySQL database
```

### 2. Access Page Management

After installation, access the page management system:
- **Admin Dashboard:** `/admin/dashboard`
- **Page Management:** `/admin/pages`
- **Create New Page:** `/admin/pages/create`

## 📁 File Structure

### Models
- `app/models/Page.php` - Page model with full CMS functionality

### Controllers  
- `app/controllers/admin/PageController.php` - Handles all page management operations

### Views
#### Admin Views
- `app/views/admin/pages/index.blade.php` - Page listing with search and filters
- `app/views/admin/pages/create.blade.php` - Create new page form with WYSIWYG editor
- `app/views/admin/pages/edit.blade.php` - Edit existing page form
- `app/views/admin/pages/preview.blade.php` - Page preview functionality

#### Frontend Views
- `app/views/frontend/page.blade.php` - Default page template
- `app/views/frontend/homepage.blade.php` - Homepage template with hero section
- `app/views/frontend/about.blade.php` - About page with team and stats sections
- `app/views/frontend/contact.blade.php` - Contact page with form and info
- `app/views/frontend/services.blade.php` - Services page with service grid
- `app/views/frontend/blog.blade.php` - Blog post template with sharing
- `app/views/frontend/gallery.blade.php` - Gallery template with filtering

### Database
- `database/pages.sql` - Complete database schema for CMS

## 🔗 Available Routes

### Page Management Routes (Admin)
- `GET /admin/pages` - List all pages with pagination and filters
- `GET /admin/pages/create` - Show create page form
- `POST /admin/pages/store` - Store new page
- `GET /admin/pages/edit?id=X` - Show edit page form
- `POST /admin/pages/update` - Update existing page
- `POST /admin/pages/delete` - Delete page
- `GET /admin/pages/preview?id=X` - Preview page

### Frontend Routes (Public)
- `GET /` - Homepage (displays page marked as homepage or default index)
- `GET /{slug}` - Display any published page by its slug
- Dynamic menu generation from published pages with `show_in_menu = 1`

## 📊 Database Schema

### Pages Table
```sql
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `content` longtext,
  `excerpt` text,
  `meta_title` varchar(255),
  `meta_description` text,
  `meta_keywords` varchar(500),
  `featured_image` varchar(255),
  `template` varchar(100) DEFAULT 'default',
  `status` enum('draft','published','private','archived') DEFAULT 'draft',
  `author_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menu_order` int(11) DEFAULT 0,
  `is_homepage` tinyint(1) DEFAULT 0,
  `show_in_menu` tinyint(1) DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Page Categories (Optional)
```sql
CREATE TABLE `page_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `description` text,
  `parent_id` int(11) DEFAULT NULL
);
```

## ✨ Features

### Content Management
- **WYSIWYG Editor** - Rich text editor with TinyMCE
- **Auto-save Drafts** - Automatic draft saving
- **Version Control** - Track content changes
- **Bulk Operations** - Manage multiple pages at once

### SEO Optimization
- **SEO-Friendly URLs** - Automatic slug generation
- **Meta Tags** - Title, description, keywords
- **Open Graph** - Social media sharing optimization
- **Sitemap Ready** - XML sitemap generation support

### Template System
- **Multiple Templates** - Choose from various page templates:
  - Default Template
  - Homepage Template
  - About Page Template
  - Contact Page Template
  - Blog Template
  - Services Template
  - Gallery Template

### Media Management
- **Featured Images** - Upload and manage page images
- **Image Optimization** - Automatic image resizing
- **Media Library** - Centralized media management

### Menu Management
- **Automatic Menus** - Pages automatically appear in menus
- **Menu Ordering** - Custom menu order control
- **Hierarchical Pages** - Parent-child page relationships

## 🛠️ Usage Examples

### Creating a New Page

1. Navigate to `/admin/pages/create`
2. Fill in the page details:
   - **Title** - Page title (required)
   - **Slug** - URL slug (auto-generated if empty)
   - **Content** - Page content using WYSIWYG editor
   - **Excerpt** - Brief description
3. Configure SEO settings:
   - Meta title, description, keywords
4. Set page options:
   - Status (draft, published, private, archived)
   - Template selection
   - Homepage setting
   - Menu visibility
5. Upload featured image (optional)
6. Click "Create Page"

### Managing Existing Pages

1. Go to `/admin/pages` to see all pages
2. Use search and filters to find specific pages
3. Available actions for each page:
   - **Preview** - See how the page looks
   - **Edit** - Modify page content and settings
   - **Delete** - Remove page (with confirmation)

### Page Status Options

- **Draft** - Page is being worked on, not visible to public
- **Published** - Page is live and visible to visitors
- **Private** - Page is only visible to logged-in users
- **Archived** - Page is hidden but preserved

## 🎨 Customization

### Adding New Templates

1. Add template to `Page.php` model:
```php
public function getAvailableTemplates() 
{
    return [
        'default' => 'Default Template',
        'custom' => 'Custom Template', // Add this line
        // ... other templates
    ];
}
```

2. Create corresponding template file in your theme

### Custom Page Fields

To add custom fields to pages:

1. Add database columns:
```sql
ALTER TABLE pages ADD COLUMN custom_field VARCHAR(255);
```

2. Update the Page model to handle the new field

3. Add form fields to create/edit views

### Styling the Editor

Customize TinyMCE editor in the create/edit views:

```javascript
tinymce.init({
    selector: '#content',
    // Add your custom configuration
    content_css: '/path/to/your/custom.css',
    // ... other options
});
```

## 🔍 Search and Filtering

The page management interface includes:

- **Search** - Search by title and content
- **Status Filter** - Filter by page status
- **Pagination** - Navigate through large page lists
- **Sorting** - Sort by date, title, or status

## 📱 Responsive Design

All admin interfaces are fully responsive and work on:
- Desktop computers
- Tablets
- Mobile phones

## 🔒 Security Features

### Access Control
- Admin authentication required
- CSRF token protection on all forms
- Role-based permissions

### Data Validation
- Input sanitization
- XSS protection
- SQL injection prevention
- File upload validation

### Content Security
- Safe HTML filtering in WYSIWYG editor
- Image upload restrictions
- File type validation

## 🐛 Troubleshooting

### Common Issues

1. **Pages Not Saving**
   - Check database permissions
   - Verify CSRF tokens are working
   - Check PHP error logs

2. **Images Not Uploading**
   - Verify `/uploads/pages/` directory exists and is writable
   - Check PHP upload limits (`upload_max_filesize`, `post_max_size`)
   - Ensure proper file permissions

3. **Editor Not Loading**
   - Check internet connection (TinyMCE loads from CDN)
   - Verify JavaScript is enabled
   - Check browser console for errors

4. **Slugs Not Generating**
   - Ensure JavaScript is enabled
   - Check for JavaScript errors
   - Verify the slug generation function is working

### Debug Mode

Enable debugging by adding to your PHP files:

```php
// Add to top of problematic files
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Performance Optimization

### Database Optimization
- Index frequently queried columns
- Use pagination for large page lists
- Optimize database queries

### Caching
- Implement page caching for published pages
- Cache menu structures
- Use CDN for media files

### Image Optimization
- Compress uploaded images
- Generate multiple image sizes
- Use WebP format when possible

## 🔄 Backup and Maintenance

### Regular Backups
- Backup `pages` table regularly
- Include uploaded media files
- Test restore procedures

### Maintenance Tasks
- Clean up unused media files
- Archive old page revisions
- Monitor database size

## 📞 Support and Updates

### Getting Help
1. Check this documentation first
2. Review error logs for specific issues
3. Test in a development environment
4. Check database connectivity and permissions

### Feature Requests
The CMS system is designed to be extensible. Common enhancements include:
- Page revisions/versions
- Advanced media management
- Multi-language support
- Advanced SEO tools
- Page analytics

---

**Created:** Complete CMS page management system with WYSIWYG editor, SEO optimization, and template support
**Last Updated:** Current implementation includes full CRUD operations, media management, and responsive admin interface

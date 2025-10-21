## Folder structure

```bash
tourism-cms/
│
├── public/                          # Public accessible files
│   ├── index.php                    # Front controller
│   ├── .htaccess                    # URL rewriting
│   │
│   ├── assets/                      # Public assets
│   │   ├── css/
│   │   │   ├── admin.css
│   │   │   └── frontend.css
│   │   ├── js/
│   │   │   ├── admin.js
│   │   │   └── frontend.js
│   │   └── images/
│   │
│   └── uploads/                     # Uploaded media files
│       ├── images/
│       ├── videos/
│       └── documents/
│
├── app/                             # Application code
│   │
│   ├── controllers/                 # Controllers
│   │   ├── admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── PageController.php
│   │   │   ├── MediaController.php
│   │   │   ├── LanguageController.php
│   │   │   └── AuthController.php
│   │   └── frontend/
│   │       ├── HomeController.php
│   │       └── PageController.php
│   │
│   ├── models/                      # Models
│   │   ├── Page.php
│   │   ├── PageTranslation.php
│   │   ├── PageMeta.php
│   │   ├── Language.php
│   │   ├── Media.php
│   │   └── User.php
│   │
│   ├── views/                       # Views
│   │   ├── admin/
│   │   │   ├── layouts/
│   │   │   │   ├── header.php
│   │   │   │   ├── sidebar.php
│   │   │   │   └── footer.php
│   │   │   ├── dashboard/
│   │   │   │   └── index.php
│   │   │   ├── pages/
│   │   │   │   ├── index.php       # List pages
│   │   │   │   ├── create.php      # Add new page
│   │   │   │   └── edit.php        # Edit page
│   │   │   └── auth/
│   │   │       └── login.php
│   │   └── frontend/
│   │       └── pages/
│   │           └── view.php
│   │
│   └── core/                        # Core framework files
│       ├── Database.php             # Database connection
│       ├── Model.php                # Base model
│       ├── Controller.php           # Base controller
│       ├── Router.php               # Router
│       ├── Session.php              # Session management
│       ├── Validator.php            # Form validation
│       └── Helper.php               # Helper functions
│
├── config/                          # Configuration files
│   ├── config.php                   # Main config
│   ├── database.php                 # Database config
│   └── routes.php                   # Route definitions
│
├── storage/                         # Storage (not public)
│   ├── logs/                        # Log files
│   └── cache/                       # Cache files
│
└── vendor/                          # Third-party libraries (if using Composer)
```

## Run server

```bash
php -S localhost:8080 server.php
```

## Admin Authentication System Setup

This document provides instructions for setting up and using the admin authentication system.

## 🚀 Quick Setup

### 1. Install Database Schema

Run the installation script to create the users table and default admin user:

```
http://localhost:8080/install_auth.php
```

Or manually execute the SQL file:
```sql
-- Import the database/users.sql file into your MySQL database
```

### 2. Default Admin Credentials

After installation, you can login with:
- **Email:** admin@example.com  
- **Password:** admin123

⚠️ **IMPORTANT:** Change the default password immediately after first login!

## 📁 File Structure

### Models
- `app/models/User.php` - User model with authentication methods

### Controllers  
- `app/controllers/admin/AuthController.php` - Handles login, logout, dashboard, profile

### Middleware
- `app/core/AuthMiddleware.php` - Authentication and authorization middleware

### Views
- `app/views/admin/auth/login.blade.php` - Admin login page
- `app/views/admin/dashboard.blade.php` - Admin dashboard
- `app/views/admin/auth/profile.blade.php` - User profile management

### Routes
- Enhanced `app/core/Router.php` with admin route handling

## 🔗 Available Routes

### Public Routes
- `/admin` or `/admin-panel` - Redirects to login if not authenticated
- `/admin/login` - Login page

### Protected Routes (Admin Only)
- `/admin/dashboard` - Admin dashboard
- `/admin/profile` - User profile management  
- `/admin/logout` - Logout

## 🛡️ Security Features

### Authentication
- Secure password hashing with PHP's `password_hash()`
- Session-based authentication
- CSRF token protection on forms
- Session regeneration on login

### Authorization  
- Role-based access control (admin, user, editor)
- Middleware protection for admin routes
- Active user status checking

### Additional Security
- Input validation and sanitization
- SQL injection protection via PDO prepared statements
- XSS protection with escaped output
- Session timeout handling

## 🔧 Usage Examples

### Protecting Routes with Middleware

```php
// Require authentication
AuthMiddleware::requireAuth();

// Require admin role
AuthMiddleware::requireAdmin();

// Check if user is authenticated
if (AuthMiddleware::isAuthenticated()) {
    // User is logged in
}

// Get current user
$user = AuthMiddleware::getCurrentUser();
```

### User Model Methods

```php
$user = new User();

// Find user by email
if ($user->findByEmail('admin@example.com')) {
    // User found
}

// Verify password
if ($user->verifyPassword('password123')) {
    // Password is correct
}

// Check if user is admin
if ($user->isAdmin()) {
    // User has admin role
}
```

## 🎨 Customization

### Adding New Admin Routes

1. Add route handling in `Router.php`:
```php
case 'new-route':
    $controller->newRoute();
    break;
```

2. Add method to `AuthController.php`:
```php
public function newRoute() {
    AuthMiddleware::requireAdmin();
    // Your logic here
    $this->render('admin.new-route', $data);
}
```

### Creating New User Roles

1. Update the database enum in `users` table:
```sql
ALTER TABLE users MODIFY COLUMN role ENUM('admin','user','editor','new-role');
```

2. Add role check methods to `User.php`:
```php
public function isNewRole() {
    return $this->role === 'new-role';
}
```

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check `config/database.php` settings
   - Verify `.env` file has correct database credentials

2. **Views Not Found**
   - Ensure view files have `.blade.php` extension
   - Check file paths in controller render calls

3. **Session Issues**
   - Verify PHP session configuration
   - Check file permissions on storage/cache directory

4. **CSRF Token Errors**
   - Ensure forms include the CSRF token field
   - Check that sessions are working properly

### Debug Mode

To enable debug information, you can add error reporting to your files:

```php
// Add to top of problematic files for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📝 Database Schema

The users table includes:
- `id` - Primary key
- `username` - Unique username
- `email` - Unique email address  
- `password` - Hashed password
- `role` - User role (admin, user, editor)
- `is_active` - Account status
- `created_at` - Account creation timestamp
- `updated_at` - Last update timestamp

## 🔄 Updates and Maintenance

### Changing Admin Path

Update the `PATH_ADMIN` value in `.env` file:
```
PATH_ADMIN=your-custom-admin-path
```

### Adding New Users

Use the User model to create new users:
```php
$user = new User();
$userId = $user->create([
    'username' => 'newuser',
    'email' => 'user@example.com', 
    'password' => 'secure-password',
    'role' => 'admin',
    'is_active' => 1
]);
```

## 📞 Support

If you encounter issues:
1. Check the troubleshooting section above
2. Verify all files are in the correct locations
3. Ensure database credentials are correct
4. Check PHP error logs for detailed error information

---

**Created:** Authentication system with User model, AuthMiddleware, and admin routes
**Last Updated:** Current implementation includes login, dashboard, and profile management


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
- **WYSIWYG Editor** - Rich text editor with Summernote
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

Customize Summernote editor in the create/edit views:

```javascript
$('#content').summernote({
    height: 400,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
    ],
    // Add your custom configuration
    styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
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
   - Check internet connection (Summernote loads from CDN)
   - Verify jQuery is loaded before Summernote
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

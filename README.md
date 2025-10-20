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

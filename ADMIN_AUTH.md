Admin Authentication System

## Overview
A complete admin authentication system with login, logout, middleware protection, and a beautiful admin dashboard.

## Features
- ✅ Secure login with password verification
- ✅ Session-based authentication
- ✅ Auth middleware for protected routes
- ✅ Beautiful admin dashboard with sidebar navigation
- ✅ Logout functionality
- ✅ Configurable admin URL prefix via `.env`

## Configuration

### Admin URL Prefix
Set your admin panel URL in `.env`:
```env
APP_ADMIN_PREFIX=cpanel
```

This will make your admin panel accessible at: `http://localhost:8000/cpanel`

## Routes

### Public Routes (No Authentication Required)
- `GET /{admin_prefix}/login` - Show login form
- `POST /{admin_prefix}/login` - Process login
- `GET /{admin_prefix}/logout` - Logout

### Protected Routes (Authentication Required)
- `GET /{admin_prefix}/dashboard` - Admin dashboard
- `GET /{admin_prefix}/users` - List all users (API)
- `GET /{admin_prefix}/users/{id}` - Show specific user (API)
- `POST /{admin_prefix}/users` - Create new user (API)
- `PUT /{admin_prefix}/users/{id}` - Update user (API)
- `DELETE /{admin_prefix}/users/{id}` - Delete user (API)

## Usage

### 1. Access Admin Login
Navigate to: `http://localhost:8000/cpanel/login`

### 2. Login Credentials
Use any existing user from your `users` table:
- Email: `admin@example.com`
- Password: (the password you set when creating the user)

**Note:** Make sure the password is hashed using `password_hash()` in the database.

### 3. Create Admin User (If Needed)
Run this SQL to create a test admin user:
```sql
INSERT INTO users (name, email, password, created_at, updated_at) 
VALUES (
    'Admin User', 
    'admin@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    NOW(), 
    NOW()
);
```

### 4. Access Dashboard
After successful login, you'll be redirected to: `http://localhost:8000/cpanel/dashboard`

### 5. Logout
Click the "Logout" link in the sidebar or navigate to: `http://localhost:8000/cpanel/logout`

## How It Works

### Authentication Middleware
The `AuthMiddleware` checks if `$_SESSION['admin_id']` exists:
- ✅ **If exists**: Allow access to protected routes
- ❌ **If not exists**: Redirect to login page

### Session Variables
When a user logs in successfully, these session variables are set:
```php
$_SESSION['admin_id'] = $user->id;
$_SESSION['admin_name'] = $user->name;
$_SESSION['admin_email'] = $user->email;
```

### Protecting Routes
To protect a route with authentication, use the `auth` middleware:
```php
$router->group(['prefix' => $_ENV['APP_ADMIN_PREFIX'], 'middleware' => 'auth'], function($router) {
    $router->get('/dashboard', 'App\Http\Controllers\AdminAuthController@dashboard');
    // Add more protected routes here
});
```

## Helper Functions

### `admin_prefix()`
Get the admin URL prefix from `.env`:
```php
$prefix = admin_prefix(); // Returns: "cpanel"
```

### `admin_url($path)`
Generate admin URLs with locale support:
```php
echo admin_url('dashboard'); // Returns: http://localhost:8000/cpanel/dashboard
echo admin_url('users'); // Returns: http://localhost:8000/cpanel/users
```

### `is_admin_logged_in()`
Check if admin is authenticated:
```php
if (is_admin_logged_in()) {
    echo "Welcome, Admin!";
}
```

### `admin_user()`
Get current admin user data:
```php
$admin = admin_user();
echo $admin->name; // Admin name
echo $admin->email; // Admin email
```

## Views

### Login Page
- **File**: `resources/views/admin/auth/login.blade.php`
- **Features**: Beautiful gradient design, error messages, CSRF protection

### Dashboard
- **File**: `resources/views/admin/dashboard.blade.php`
- **Features**: Statistics cards, recent activity, quick actions

### Admin Layout
- **File**: `resources/views/layouts/admin.blade.php`
- **Features**: Sidebar navigation, responsive design, Font Awesome icons

## Security Features

1. **Password Hashing**: Uses PHP's `password_hash()` and `password_verify()`
2. **CSRF Protection**: All forms include CSRF tokens
3. **Session Management**: Secure session handling
4. **Middleware Protection**: Routes are protected by authentication middleware
5. **Error Handling**: Failed login attempts show error messages

## Customization

### Change Admin Prefix
Edit `.env`:
```env
APP_ADMIN_PREFIX=admin
```

### Add More Protected Routes
Edit `routes/web.php`:
```php
$router->group(['prefix' => $_ENV['APP_ADMIN_PREFIX'], 'middleware' => 'auth'], function($router) {
    $router->get('/pages', 'App\Http\Controllers\PageController@index');
    $router->get('/media', 'App\Http\Controllers\MediaController@index');
    // Add more routes...
});
```

### Customize Dashboard
Edit `resources/views/admin/dashboard.blade.php` to add your own content.

### Add Sidebar Links
Edit `resources/views/layouts/admin.blade.php` to modify the sidebar navigation.

## Troubleshooting

### "Unauthorized" or Redirect Loop
- Make sure sessions are started in `bootstrap/app.php`
- Check that `$_SESSION['admin_id']` is set after login

### Login Form Not Showing
- Verify the route is registered in `routes/web.php`
- Check that `APP_ADMIN_PREFIX` is set in `.env`

### Password Not Working
- Ensure passwords are hashed with `password_hash()`
- Test with the default password: `password`

## Next Steps

1. Add role-based permissions (admin, editor, viewer)
2. Implement "Remember Me" functionality
3. Add password reset feature
4. Create user management interface
5. Add activity logging

## Example: Creating a New Admin User Programmatically

```php
use App\Models\User;

$user = User::create([
    'name' => 'John Admin',
    'email' => 'john@example.com',
    'password' => password_hash('secretpassword', PASSWORD_DEFAULT)
]);
```

---

**Your admin panel is now fully secured with authentication!** 🔐

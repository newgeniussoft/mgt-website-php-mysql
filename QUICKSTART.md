# Quick Start Guide

## Running the Application

### Option 1: PHP Built-in Server (Recommended for Development)

From the project root, run:
```bash
php -S localhost:8000 -t public
```

Or use the router script:
```bash
php -S localhost:8000 server.php
```

Then visit: http://localhost:8000

### Option 2: Apache (XAMPP/WAMP)

1. Place project in your web root (e.g., `C:\xampp\htdocs\mgt-v5_2`)
2. Access via: http://localhost/mgt-v5_2
3. The `.htaccess` in root will redirect to `/public` directory

## Database Setup

1. Create your database in phpMyAdmin or MySQL:
   ```sql
   CREATE DATABASE madagascar_gree_v5_4;
   ```

2. Update `.env` file with your database credentials:
   ```
   DB_DATABASE=madagascar_gree_v5_4
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Import your database schema

## Troubleshooting

### 404 Error
- Make sure you're running the server from the correct directory
- Use `-t public` flag or the `server.php` router script
- For Apache, ensure mod_rewrite is enabled

### Database Connection Error
- Check your `.env` file has correct database credentials
- Make sure the database exists
- Verify MySQL is running (XAMPP control panel)

### Class Not Found Error
- The autoloader should handle this automatically
- Make sure `bootstrap/autoloader.php` exists
- Check class namespaces match folder structure

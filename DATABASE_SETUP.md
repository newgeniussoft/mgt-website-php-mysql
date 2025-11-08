# Database Setup Guide

## Quick Setup

### 1. Create Database
```sql
CREATE DATABASE madagascar_gree_v5_4;
```

### 2. Run Migrations
```bash
cd database
php migrate.php
```

### 3. Run Seeders
```bash
cd database
php seed.php
```

## Migration Files

### `001_create_users_table.sql`
- Creates users table with authentication fields
- Includes indexes for performance
- Auto-timestamps for created_at/updated_at

### `002_create_posts_table.sql`
- Creates posts table with content management
- Foreign key relationship to users table
- Status field (draft, published, archived)

## Seeder Data

### Users Created:
- **admin@example.com** / **admin123** (Admin User)
- **john@example.com** / **password123** (John Doe)
- **jane@example.com** / **password123** (Jane Smith)
- **test@example.com** / **test123** (Test User)

### Posts Created:
- 4 sample posts with different statuses
- Linked to various users
- Mix of published and draft posts

## Testing API Endpoints

After setup, test these endpoints:

### Users API
- `GET /api/users` - Get all users
- `GET /api/users/1` - Get specific user
- `POST /api/users` - Create new user

### Posts API
- `GET /api/posts` - Get all posts
- `GET /api/posts/1` - Get specific post
- `POST /api/posts` - Create new post

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

## Troubleshooting

### Migration Errors
- Check database credentials in `.env`
- Ensure database exists
- Verify user has CREATE TABLE permissions

### Seeder Errors
- Run migrations first
- Check foreign key constraints
- Ensure users table exists before posts seeder

### Connection Issues
- Verify MySQL is running (XAMPP)
- Check `.env` database settings
- Test with phpMyAdmin first

# Madagascar Green Tours CMS

A comprehensive PHP/MySQL Content Management System with advanced features.

## 🚀 Features

- **Admin Panel** with authentication and role-based access
- **Media Library** with folder organization
- **File Manager** for direct file system access
- **Code Editor** with Monaco Editor integration
- **Database Manager** - phpMyAdmin alternative built-in
- **Settings System** with dynamic configuration
- **Multi-language Support** (English, Spanish)
- **Responsive Design** with Bootstrap

## 📦 Quick Start

1. **Database Manager**: See `DATABASE_MANAGER_QUICKSTART.md`
2. **Admin Authentication**: See `ADMIN_AUTH.md`
3. **Media Management**: See `MEDIA_MANAGEMENT.md`
4. **Settings System**: See `SETTINGS_SYSTEM.md`

## 🗄️ Database Manager

The built-in Database Manager provides phpMyAdmin-like functionality:

- ✅ View all database tables
- ✅ Browse, search, and sort data
- ✅ Add, edit, and delete rows
- ✅ Execute custom SQL queries
- ✅ Export tables as SQL files
- ✅ Smart form fields based on data types

**Access**: Admin Panel → System → Database Manager

For details, see `DATABASE_MANAGER.md`

```bash
// ==========================================
// PROJECT STRUCTURE GUIDE
// ==========================================
/*
your-project/
├── app/
│   ├── Models/
│   │   ├── Model.php (base model)
│   │   ├── User.php
│   │   └── Post.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php (base controller)
│   │   │   ├── UserController.php
│   │   │   ├── PostController.php
│   │   │   └── AuthController.php
│   │   ├── Requests/
│   │   │   ├── Request.php (base request)
│   │   │   ├── StoreUserRequest.php
│   │   │   └── UpdatePostRequest.php
│   │   └── Middleware/
│   │       ├── Middleware.php (base)
│   │       ├── AuthMiddleware.php
│   │       └── CorsMiddleware.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   └── EmailService.php
│   └── Traits/
│       ├── HasTimestamps.php
│       └── Searchable.php
├── bootstrap/
│   └── app.php (application bootstrap)
├── config/
│   ├── app.php
│   ├── database.php
│   └── mail.php
├── database/
│   ├── migrations/
│   │   └── 001_create_users_table.sql
│   └── seeds/
│       └── UserSeeder.php
├── public/
│   ├── index.php (entry point)
│   ├── css/
│   ├── js/
│   └── images/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.php
│       │   └── guest.php
│       ├── components/
│       │   ├── header.php
│       │   └── footer.php
│       ├── users/
│       │   ├── index.php
│       │   ├── show.php
│       │   └── create.php
│       └── errors/
│           ├── 404.php
│           └── 500.php
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   ├── logs/
│   │   └── app.log
│   ├── cache/
│   └── uploads/
├── helpers/
│   ├── functions.php
│   ├── array_helpers.php
│   └── string_helpers.php
├── tests/
│   ├── Unit/
│   └── Feature/
├── vendor/ (if using Composer)
├── .env (environment variables)
├── .htaccess
└── composer.json
```
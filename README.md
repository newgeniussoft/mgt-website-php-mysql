# Madagascar Green Tours CMS

A comprehensive PHP/MySQL Content Management System with advanced features.

## 🚀 Features

- **Admin panel** with authentication (prefix configurable via `APP_ADMIN_PREFIX`)
- **Pages, Templates & Sections** for database-driven pages with a default site Template
- **Template Items** to render model-backed lists/cards and detail pages
- **Tours module** (multi-language, photos, details, featured, categories, locations)
- **Blogs** CRUD with image upload and Spanish fields
- **Galleries** with thumbnails and multi-image support
- **Media Library** (DB-backed) with folders, metadata, downloads
- **File Manager** for real file system operations
- **Code Editor** (Monaco) to edit project files in-browser
- **Database Manager** (browse/edit/SQL/export/DDL tools)
- **Settings system** grouped and typed (text/number/boolean/json/image)
- **DB-backed translations** with file fallback, `<t key="..." default="..."/>` tags, and inline seeding
- **I18n-aware URLs** with Spanish canonicalization at `/es/`
- **Bootstrap** responsive UI and Font Awesome icons

## 📦 Requirements

- PHP 7.4+ (8.x recommended)
- MySQL 5.7+ or MariaDB 10.3+
- Apache with `mod_rewrite` enabled, or PHP built-in server
- No Composer required (custom PSR-4 autoloader)

## �️ Installation

- **Clone or copy** into your web root. Example (XAMPP on Windows): `c:\xampp\htdocs\mgt-v5_2`.
- Create a database (e.g. `db_mgt_prod`).
- Import schema/data: `database/migrations/all.sql` (or individual `00X_*.sql` files).
- Configure `.env` in project root:
  - `APP_URL` (e.g. `http://localhost/mgt-v5_2` or `http://localhost:8000`)
  - `APP_ADMIN_PREFIX` (default `cpanel`)
  - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

## ▶️ Run locally

- Option A — Apache (recommended)
  - Point your VirtualHost DocumentRoot to `public/`.
  - Ensure `.htaccess` is honored and `mod_rewrite` is enabled.
  - Visit: `APP_URL` (home) and `APP_URL/{APP_ADMIN_PREFIX}` (admin).

- Option B — PHP built-in server
  - From project root: `php -S localhost:8000 server.php`
  - Visit: `http://localhost:8000` and `http://localhost:8000/{APP_ADMIN_PREFIX}`

## 🔐 Admin access

- Login page: `/{APP_ADMIN_PREFIX}/login`.
- Users are stored in the `users` table. Create one via SQL or the Database Manager and store a `password_hash()` value for the password (bcrypt).

## 🌐 Routing overview

- Custom router in `public/index.php`, routes in `routes/web.php`.
- Language prefix support: `/es/...` sets the locale to Spanish. The `.htaccess` canonicalizes `/es` → `/es/` and keeps `/es/` trailing slash for the homepage.
- Frontend:
  - `/` → dynamic homepage (`Page::getHomepage()`)
  - `/{slug}` → page by slug
  - `/{slug}/{item}` → model-backed detail (e.g. `tour/{slug}`, `blog/{slug}`)
- Admin (all prefixed with `/{APP_ADMIN_PREFIX}`): dashboard, pages, templates, template-items, sections, media, filemanager, codeeditor, database, tours, blogs, reviews, translations, settings.

## 🧩 Templates, Sections, and Items

- Templates (`templates` table) render the overall page; Sections attach HTML/CSS/JS blocks to pages.
- Content placeholders:
  - `{{ content }}` (first entry), `{{ content_all }}`, `{{ content1 }}` ...
  - Named content via auto-slugified titles, e.g. `{{ intro_text }}`.
- Items tag to render datasets:
  - Example: `<items name="tour" template="tour-template" limit="6" />`
  - Supports inline lists and JSON via `data="..."`.

## 🗣️ Translations

- `resources/lang/{locale}/*.php` files with DB-backed override (`translations` table).
- Helpers: `__('key')`, `trans('key')`, `trans_choice('key', n)`.
- Inline render tags: `<t key="..." default="..." />` and block form.
- Inline seeding: `{ lang="en" key="menu.home" value="Home" }` stores into DB if missing.

## 🖼️ Media, Files, Database tools

- **Media Library**: upload, metadata, folders, download links.
- **File Manager**: create/rename/move/delete/copy files and folders.
- **Code Editor**: Monaco-based editor with file tree and save actions.
- **Database Manager**: browse tables, edit rows, run SQL, export, manage columns.

## 📁 Notable paths

- Entry point: `public/index.php`
- Rewrites: `public/.htaccess` (includes Spanish `/es/` canonicalization)
- Bootstrap & env: `bootstrap/app.php`, `.env`
- Router & routes: `public/index.php`, `routes/web.php`
- Views: `resources/views`
- Uploads: `public/uploads`
- Logs: `storage/logs/app.log`

## 🚀 Production notes

- Set web server DocumentRoot to `public/`.
- Ensure `public/.htaccess` and `mod_rewrite` are enabled.
- Set proper permissions for `public/uploads` and `storage/logs`.
- Configure `APP_URL` and SMTP/analytics/settings via the admin Settings UI.

---

This project uses a lean custom PHP stack (no Composer) with PDO, a simple PSR-4 autoloader, and Blade-like rendering helpers.
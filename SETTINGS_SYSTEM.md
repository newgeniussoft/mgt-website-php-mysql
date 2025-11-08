# Settings System Documentation

## Overview
A comprehensive settings management system for storing and managing application configuration with a beautiful admin interface.

## Features
- ✅ Database-driven settings storage
- ✅ Multiple setting types (text, textarea, number, boolean, email, url, image, json)
- ✅ Organized by groups (General, Contact, Social, Email, SEO, Appearance, System)
- ✅ Beautiful admin interface with tabbed navigation
- ✅ Image upload support
- ✅ Easy-to-use helper functions
- ✅ Type casting (boolean, number, json)
- ✅ Default values support

## Installation

### 1. Run Installation Script
Navigate to: `http://localhost:8000/install_settings.php`

This will:
- Create the `settings` table
- Insert 40+ default settings
- Create upload directories
- Verify installation

### 2. Access Settings Page
Go to: `http://localhost:8000/cpanel/settings`

## Database Schema

```sql
CREATE TABLE `settings` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(255) NOT NULL UNIQUE,
  `value` TEXT NULL,
  `type` ENUM('text', 'textarea', 'number', 'boolean', 'email', 'url', 'json', 'image'),
  `group` VARCHAR(100) DEFAULT 'general',
  `label` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `order` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Settings Groups

### 1. **General Settings**
- Site Name
- Site Tagline
- Site Description
- Site Keywords
- Site Logo
- Site Favicon

### 2. **Contact Information**
- Contact Email
- Contact Phone
- Contact Address
- Business Hours

### 3. **Social Media**
- Facebook URL
- Twitter URL
- Instagram URL
- YouTube URL
- LinkedIn URL

### 4. **Email Settings**
- SMTP Host
- SMTP Port
- SMTP Username
- SMTP Password
- SMTP Encryption
- From Email
- From Name

### 5. **SEO Settings**
- Default Meta Title
- Default Meta Description
- Open Graph Image
- Google Analytics ID
- Google Site Verification

### 6. **Appearance**
- Primary Color
- Secondary Color
- Items Per Page
- Date Format
- Time Format

### 7. **System Settings**
- Maintenance Mode
- Maintenance Message
- Cache Enabled
- Debug Mode
- Timezone

## Usage

### Get a Single Setting

```php
// Basic usage
$siteName = setting('site_name');

// With default value
$email = setting('contact_email', 'default@example.com');

// Boolean setting (auto-casted)
$maintenanceMode = setting('maintenance_mode'); // Returns true/false

// Number setting (auto-casted)
$itemsPerPage = setting('items_per_page'); // Returns integer
```

### Get All Settings

```php
// Get all settings as associative array
$allSettings = settings();

// Access specific setting
echo $allSettings['site_name'];
```

### Get Settings by Group

```php
// Get all social media settings
$socialSettings = settings('social');

// Loop through group settings
foreach ($socialSettings as $setting) {
    echo $setting->label . ': ' . $setting->value;
}
```

### Using in Blade Templates

```blade
<h1>{{ setting('site_name') }}</h1>
<p>{{ setting('site_tagline') }}</p>

@if(setting('maintenance_mode'))
    <div class="alert alert-warning">
        {{ setting('maintenance_message') }}
    </div>
@endif
```

### Using in Controllers

```php
use App\Models\Setting;

class HomeController extends Controller {
    public function index() {
        return View::make('home', [
            'siteName' => setting('site_name'),
            'contactEmail' => setting('contact_email'),
            'socialLinks' => settings('social')
        ]);
    }
}
```

## Model Methods

### Setting::get($key, $default)
Get a setting value with optional default.

```php
$value = Setting::get('site_name', 'My Site');
```

### Setting::set($key, $value)
Set or update a setting value.

```php
Setting::set('site_name', 'New Site Name');
```

### Setting::getAllAsArray()
Get all settings as key-value array.

```php
$settings = Setting::getAllAsArray();
```

### Setting::getByGroup($group)
Get all settings for a specific group.

```php
$generalSettings = Setting::getByGroup('general');
```

### Setting::getAllGroups()
Get list of all setting groups.

```php
$groups = Setting::getAllGroups();
// Returns: ['general', 'contact', 'social', 'email', 'seo', 'appearance', 'system']
```

### Setting::updateMany($settings)
Update multiple settings at once.

```php
Setting::updateMany([
    'site_name' => 'New Name',
    'contact_email' => 'new@example.com'
]);
```

### Setting::has($key)
Check if a setting exists.

```php
if (Setting::has('custom_setting')) {
    // Setting exists
}
```

### Setting::remove($key)
Delete a setting.

```php
Setting::remove('old_setting');
```

## Setting Types

### text
Single-line text input
```php
'type' => 'text'
```

### textarea
Multi-line text input
```php
'type' => 'textarea'
```

### number
Numeric input (auto-casted to int/float)
```php
'type' => 'number'
```

### boolean
Checkbox toggle (auto-casted to true/false)
```php
'type' => 'boolean'
```

### email
Email input with validation
```php
'type' => 'email'
```

### url
URL input with validation
```php
'type' => 'url'
```

### image
File upload with preview
```php
'type' => 'image'
```

### json
JSON data (auto-decoded to array)
```php
'type' => 'json'
```

## Adding Custom Settings

### Via Database

```sql
INSERT INTO settings (`key`, `value`, `type`, `group`, `label`, `description`, `order`)
VALUES ('custom_setting', 'value', 'text', 'general', 'Custom Setting', 'Description here', 10);
```

### Via Code

```php
Setting::create([
    'key' => 'custom_setting',
    'value' => 'default value',
    'type' => 'text',
    'group' => 'general',
    'label' => 'Custom Setting',
    'description' => 'This is a custom setting',
    'order' => 10
]);
```

## Admin Interface Features

### Group Navigation
- Sidebar with all setting groups
- Active group highlighting
- Icon indicators for each group

### Form Controls
- Text inputs
- Textareas
- Number inputs
- Email inputs
- URL inputs
- Boolean toggles
- Image uploads with preview

### Actions
- **Save Settings** - Update all settings in current group
- **Reset to Defaults** - Reset group settings to default values

### Feedback
- Success messages on save
- Error messages on failure
- Form validation

## Security

### File Uploads
- Uploaded images stored in `/public/uploads/settings/`
- Unique filenames with timestamps
- File type validation

### Input Validation
- Email validation for email type
- URL validation for url type
- Number validation for number type

### CSRF Protection
- All forms include CSRF tokens
- Protected against CSRF attacks

## Example: Site Configuration

```php
// In your layout template
<!DOCTYPE html>
<html>
<head>
    <title>{{ setting('site_name') }} - {{ setting('site_tagline') }}</title>
    <meta name="description" content="{{ setting('site_description') }}">
    <meta name="keywords" content="{{ setting('site_keywords') }}">
    <link rel="icon" href="{{ asset(setting('site_favicon')) }}">
    
    @if(setting('google_analytics_id'))
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
    @endif
</head>
<body>
    <header>
        <img src="{{ asset(setting('site_logo')) }}" alt="{{ setting('site_name') }}">
    </header>
    
    <footer>
        <p>Contact: {{ setting('contact_email') }}</p>
        <p>Phone: {{ setting('contact_phone') }}</p>
        
        <div class="social-links">
            <a href="{{ setting('social_facebook') }}">Facebook</a>
            <a href="{{ setting('social_twitter') }}">Twitter</a>
            <a href="{{ setting('social_instagram') }}">Instagram</a>
        </div>
    </footer>
</body>
</html>
```

## Troubleshooting

### Settings Not Saving
- Check database connection
- Verify write permissions on uploads directory
- Check for SQL errors in logs

### Images Not Uploading
- Ensure `/public/uploads/settings/` exists
- Check directory permissions (755)
- Verify PHP upload_max_filesize setting

### Settings Not Loading
- Run installation script
- Check if settings table exists
- Verify default settings are inserted

## API Endpoints (Optional)

You can add API endpoints for settings:

```php
// Get single setting
GET /api/settings/{key}

// Get all settings
GET /api/settings

// Response format
{
    "success": true,
    "data": {
        "key": "site_name",
        "value": "Madagascar Green Tours"
    }
}
```

---

**Your settings system is now ready to use!** ⚙️

# Frontend Page Rendering System

## Overview

Pages created in the admin panel are now automatically accessible on the website frontend. The system dynamically renders pages from the database with their templates and sections.

## How It Works

### 1. **Homepage**
- URL: `/`
- Checks database for a page with `is_homepage = 1` and `status = 'published'`
- If found, renders that page with its template and sections
- If not found, falls back to default homepage

### 2. **Dynamic Pages**
- URL: `/{slug}` (e.g., `/about`, `/contact`, `/services`)
- Looks up page by slug in database
- Only shows pages with `status = 'published'`
- Renders page with its assigned template and sections
- Shows 404 if page not found or not published

## Components

### FrontendController
**File**: `app/Http/Controllers/FrontendController.php`

**Methods**:
- `index()` - Handles homepage
- `showPage($slug)` - Handles dynamic pages by slug
- `renderWithTemplate()` - Renders page with custom template
- `renderBasic()` - Fallback rendering without template
- `buildMenuHtml()` - Generates navigation menu
- `renderSections()` - Renders all page sections with content
- `notFound()` - Shows 404 page

### Frontend Template
**File**: `resources/views/frontend/page.blade.php`

Basic page template with:
- Responsive header with navigation
- Dynamic menu from database
- Page title and content
- Section rendering
- Footer

## Routes

### Updated Routes
**File**: `routes/web.php`

```php
// Homepage - Dynamic from database
$router->get('/', 'App\Http\Controllers\FrontendController@index');

// Catch-all for dynamic pages (must be last route)
$router->get('/{slug}', 'App\Http\Controllers\FrontendController@showPage');
```

**Important**: The catch-all route `/{slug}` must be the LAST route in the file to avoid overriding other routes.

## Page Rendering Flow

### With Custom Template

1. User visits `/{slug}`
2. FrontendController looks up page by slug
3. Checks if page is published
4. Loads page's template from database
5. Loads page's sections from database
6. Renders each section with its content
7. Replaces template variables:
   - `{{ page_title }}` → Page title
   - `{{ meta_description }}` → Meta description
   - `{{ site_name }}` → Site name from .env
   - `{{ menu_items }}` → Navigation menu HTML
   - `{{ page_sections }}` → Rendered sections HTML
   - `{{ custom_css }}` → Custom CSS
   - `{{ custom_js }}` → Custom JavaScript
8. Returns rendered HTML

### Without Custom Template (Fallback)

1. Uses `frontend/page.blade.php` template
2. Displays page title and sections
3. Includes navigation menu
4. Basic styling

## Template Variables

Templates can use these variables:

| Variable | Description |
|----------|-------------|
| `{{ page_title }}` | Page title |
| `{{ meta_description }}` | Meta description for SEO |
| `{{ meta_keywords }}` | Meta keywords for SEO |
| `{{ site_name }}` | Site name from .env |
| `{{ menu_items }}` | Navigation menu HTML |
| `{{ page_sections }}` | All page sections rendered |
| `{{ custom_css }}` | Custom CSS injection point |
| `{{ custom_js }}` | Custom JavaScript injection point |

## Section Rendering

Sections are rendered with:
1. **HTML Template**: Section's HTML structure
2. **Content**: Content blocks from database
3. **CSS**: Section's custom CSS (wrapped in `<style>` tags)
4. **JavaScript**: Section's custom JS (wrapped in `<script>` tags)

The `{{ content }}` variable in section templates is replaced with actual content from the database.

## Menu Generation

Navigation menu is automatically generated from:
- Pages with `show_in_menu = 1`
- Pages with `status = 'published'`
- Ordered by `menu_order` ASC, then `title` ASC

Menu HTML structure:
```html
<ul class="menu">
    <li class="active"><a href="/">Home</a></li>
    <li><a href="/about">About</a></li>
    <li><a href="/contact">Contact</a></li>
</ul>
```

## Example Usage

### 1. Create a Page in Admin

1. Go to `/admin/pages/create`
2. Fill in:
   - Title: "About Us"
   - Slug: "about" (auto-generated)
   - Meta Description: "Learn about our company"
   - Select Template: "Default Template"
   - Status: "Published"
   - Check "Show in Menu"
3. Save page

### 2. Add Sections

1. Click "Manage Sections"
2. Create section with Monaco Editor
3. Add content with Summernote

### 3. View on Website

Visit: `http://your-domain/about`

The page will render with:
- Custom template (if assigned)
- All sections in order
- Navigation menu
- SEO meta tags

## SEO Features

Pages automatically include:
- `<title>` tag with page title
- Meta description
- Meta keywords
- Open Graph tags (if template includes them)

## Responsive Design

The default frontend template includes:
- Mobile-friendly navigation
- Responsive layout
- Touch-friendly menu
- Viewport meta tag

## Customization

### Custom Templates

Create templates in admin panel with:
- HTML structure
- CSS styling
- JavaScript functionality
- Template variables

### Custom Sections

Design sections with:
- Monaco Editor for HTML/CSS/JS
- Summernote for content
- JSON settings for configuration

### Styling

Override default styles by:
1. Creating custom template with your CSS
2. Adding CSS to sections
3. Modifying `frontend/page.blade.php`

## Troubleshooting

### Page Not Showing

**Check**:
1. Page status is "Published"
2. Slug is correct
3. Template is assigned (if using custom template)
4. Sections are active

### Template Not Rendering

**Check**:
1. Template status is "Active"
2. Template variables are correct
3. No syntax errors in template HTML

### Sections Not Displaying

**Check**:
1. Sections are active (`is_active = 1`)
2. Sections have content
3. Section order is correct
4. Content is active

### Menu Not Showing

**Check**:
1. Pages have `show_in_menu = 1`
2. Pages are published
3. Menu order is set

## Performance

The system:
- Queries database once per page load
- Caches Blade views
- Minimal overhead
- Fast rendering

## Security

- Only published pages are accessible
- SQL injection prevention (PDO prepared statements)
- XSS protection (htmlspecialchars)
- CSRF protection on forms

## Future Enhancements

Possible improvements:
- Page caching
- CDN integration
- Image optimization
- Lazy loading
- AMP support
- PWA features

---

**Status**: ✅ Fully Functional  
**Version**: 1.0.0  
**Last Updated**: 2024

# Complete CMS Page Management System

## Overview

This is a comprehensive Content Management System with a hierarchical structure:

```
Page → Template → Section → Content
```

- **Pages**: Main content pages with SEO settings
- **Templates**: Customizable HTML/CSS/JS templates (Monaco Editor)
- **Sections**: Modular page sections (Monaco Editor)
- **Content**: Rich text content blocks (Summernote Editor)

## Installation

1. Run the installation script:
```bash
php install_cms.php
```

2. Access the CMS:
```
http://your-domain/admin/pages
```

## Features

### 1. Page Management

**Location**: `/admin/pages`

**Features**:
- Create, edit, delete pages
- SEO optimization (meta title, description, keywords)
- Featured image upload
- Page status (draft, published, archived)
- Homepage designation
- Menu management with ordering
- Parent-child page relationships
- Slug auto-generation
- Search and filtering
- Pagination

**Page Fields**:
- Title (required)
- Slug (auto-generated)
- Meta Title
- Meta Description
- Meta Keywords
- Featured Image
- Template Selection
- Status
- Homepage Flag
- Show in Menu
- Menu Order
- Parent Page

### 2. Template Management

**Location**: `/admin/templates`

**Features**:
- Create custom templates with Monaco Editor
- Edit HTML, CSS, and JavaScript separately
- Syntax highlighting and code completion
- Template preview
- Template duplication
- Default template designation
- Thumbnail upload
- Active/Inactive status

**Monaco Editor**:
- Dark theme
- Syntax highlighting for HTML, CSS, JS
- Auto-completion
- Minimap
- Multi-tab interface

**Template Variables**:
```
{{ page_title }}        - Page title
{{ meta_description }}  - Meta description
{{ meta_keywords }}     - Meta keywords
{{ site_name }}         - Site name
{{ menu_items }}        - Navigation menu
{{ page_sections }}     - Page sections content
{{ custom_css }}        - Custom CSS injection
{{ custom_js }}         - Custom JS injection
```

**Example Template**:
```html
<!DOCTYPE html>
<html>
<head>
    <title>{{ page_title }}</title>
    <meta name="description" content="{{ meta_description }}">
    {{ custom_css }}
</head>
<body>
    <header>
        <h1>{{ site_name }}</h1>
        <nav>{{ menu_items }}</nav>
    </header>
    <main>
        {{ page_sections }}
    </main>
    {{ custom_js }}
</body>
</html>
```

### 3. Section Management

**Location**: `/admin/sections?page_id=X`

**Features**:
- Create modular sections for pages
- Monaco Editor for HTML template, CSS, and JavaScript
- Drag-and-drop reordering (SortableJS)
- Section types (content, hero, features, gallery, etc.)
- JSON settings for customization
- Active/Inactive toggle
- Multiple content blocks per section

**Section Types**:
- Content
- Hero
- Features
- Gallery
- Testimonials
- Contact
- Custom

**Section Variables**:
```
{{ content }}  - Section content from content blocks
```

### 4. Content Management

**Location**: `/admin/sections/edit?id=X`

**Features**:
- Rich text editor (Summernote)
- Multiple content blocks per section
- Multi-language support (en, fr, es, de)
- Content types (HTML, Plain Text, Markdown)
- Active/Inactive toggle
- Drag-and-drop ordering

**Summernote Features**:
- WYSIWYG editing
- Image upload
- Video embedding
- Table creation
- Code view
- Fullscreen mode
- Font styling
- Lists and formatting

## Database Schema

### Tables

1. **templates**
   - id, name, slug, description
   - html_content, css_content, js_content
   - thumbnail, is_default, status
   - created_by, created_at, updated_at

2. **pages**
   - id, template_id, title, slug
   - meta_title, meta_description, meta_keywords
   - featured_image, status
   - is_homepage, show_in_menu, menu_order
   - parent_id, author_id
   - published_at, created_at, updated_at

3. **sections**
   - id, page_id, name, slug, type
   - html_template, css_styles, js_scripts
   - settings (JSON), order_index, is_active
   - created_at, updated_at

4. **contents**
   - id, section_id, title, content
   - content_type, language, order_index
   - is_active, created_at, updated_at

## Workflow

### Creating a Complete Page

1. **Create a Template** (Optional - can use default)
   - Go to `/admin/templates/create`
   - Design HTML structure with Monaco Editor
   - Add CSS styling
   - Add JavaScript functionality
   - Use template variables for dynamic content
   - Save template

2. **Create a Page**
   - Go to `/admin/pages/create`
   - Enter page title and SEO information
   - Select template
   - Set page status and options
   - Upload featured image
   - Save page

3. **Add Sections to Page**
   - Go to page edit → "Manage Sections"
   - Create new section
   - Design section template with Monaco Editor
   - Add CSS and JavaScript
   - Save section

4. **Add Content to Section**
   - Go to section edit → "Add Content"
   - Use Summernote to create rich content
   - Select language and content type
   - Save content

5. **Preview and Publish**
   - Preview page to see final result
   - Change status to "Published"
   - Page is now live!

## Routes

### Page Routes
- GET `/admin/pages` - List pages
- GET `/admin/pages/create` - Create page form
- POST `/admin/pages/store` - Store new page
- GET `/admin/pages/edit?id=X` - Edit page form
- POST `/admin/pages/update` - Update page
- POST `/admin/pages/delete` - Delete page
- GET `/admin/pages/preview?id=X` - Preview page

### Template Routes
- GET `/admin/templates` - List templates
- GET `/admin/templates/create` - Create template form
- POST `/admin/templates/store` - Store new template
- GET `/admin/templates/edit?id=X` - Edit template form
- POST `/admin/templates/update` - Update template
- POST `/admin/templates/delete` - Delete template
- GET `/admin/templates/preview?id=X` - Preview template
- POST `/admin/templates/duplicate` - Duplicate template

### Section Routes
- GET `/admin/sections?page_id=X` - List sections
- GET `/admin/sections/create?page_id=X` - Create section form
- POST `/admin/sections/store` - Store new section
- GET `/admin/sections/edit?id=X` - Edit section form
- POST `/admin/sections/update` - Update section
- POST `/admin/sections/delete` - Delete section
- POST `/admin/sections/reorder` - Reorder sections (AJAX)

### Content Routes
- GET `/admin/sections/add-content?section_id=X` - Add content form
- POST `/admin/sections/store-content` - Store new content
- GET `/admin/sections/edit-content?id=X` - Edit content form
- POST `/admin/sections/update-content` - Update content
- POST `/admin/sections/delete-content` - Delete content

## Models

### Page Model
```php
Page::all()                    // Get all pages
Page::find($id)                // Find by ID
Page::getPublished()           // Get published pages
Page::getBySlug($slug)         // Get by slug
Page::getHomepage()            // Get homepage
Page::getMenuPages()           // Get menu pages
Page::search($query, $status)  // Search pages
Page::paginate($page, $perPage, $status)  // Paginate
```

### Template Model
```php
Template::all()           // Get all templates
Template::find($id)       // Find by ID
Template::getActive()     // Get active templates
Template::getBySlug($slug)  // Get by slug
Template::getDefault()    // Get default template
```

### Section Model
```php
Section::find($id)                    // Find by ID
Section::getByPage($pageId, $activeOnly)  // Get by page
Section::getBySlug($slug, $pageId)    // Get by slug
Section::reorder($pageId, $sectionIds)  // Reorder sections
```

### Content Model
```php
Content::find($id)                           // Find by ID
Content::getBySection($sectionId, $activeOnly, $language)  // Get by section
Content::reorder($sectionId, $contentIds)    // Reorder contents
```

## Security

- CSRF token protection on all forms
- File upload validation
- SQL injection prevention (PDO prepared statements)
- XSS protection
- Authentication required for admin access
- Input sanitization

## Best Practices

1. **Templates**:
   - Use semantic HTML
   - Keep CSS modular
   - Minimize JavaScript
   - Use template variables for dynamic content

2. **Sections**:
   - Create reusable section templates
   - Use JSON settings for customization
   - Keep sections focused and modular

3. **Content**:
   - Use appropriate content types
   - Organize content logically
   - Leverage multi-language support

4. **SEO**:
   - Always fill meta descriptions
   - Use descriptive titles
   - Add relevant keywords
   - Use featured images

## Troubleshooting

### Templates not showing
- Check template status is "active"
- Verify template variables are correct
- Check for syntax errors in Monaco Editor

### Sections not displaying
- Ensure section is active
- Check section order
- Verify section has content

### Content not appearing
- Check content is active
- Verify correct language
- Ensure content is saved

## Support

For issues or questions:
1. Check this documentation
2. Review database structure
3. Check browser console for JavaScript errors
4. Verify file permissions for uploads

## Credits

- **Monaco Editor**: Microsoft
- **Summernote**: Summernote Team
- **SortableJS**: RubaXa
- **Bootstrap**: Twitter
- **Font Awesome**: Fonticons

---

**Version**: 1.0.0  
**Last Updated**: 2024

# CMS Quick Start Guide

## Installation

### Step 1: Run Installation Script
```bash
php install_cms.php
```

This will:
- Create database tables (templates, pages, sections, contents)
- Insert default template
- Create sample homepage
- Create upload directories

### Step 2: Access the CMS
Navigate to: `http://your-domain/admin/pages`

## Quick Tutorial

### Create Your First Page

1. **Go to Pages**
   - Click "Pages" in the sidebar
   - Click "Create New Page"

2. **Fill Page Information**
   - Title: "About Us"
   - Slug: (auto-generated as "about-us")
   - Meta Description: "Learn more about our company"
   - Select Template: "Default Template"
   - Status: "Published"
   - Check "Show in Menu"

3. **Save Page**
   - Click "Create Page"

### Add Sections to Your Page

1. **Manage Sections**
   - From page edit screen, click "Manage Sections"
   - Click "Add Section"

2. **Create Hero Section**
   - Name: "Hero Section"
   - Type: "Hero"
   - HTML Template (Monaco Editor):
   ```html
   <div class="hero">
       <div class="container">
           {{ content }}
       </div>
   </div>
   ```
   - CSS Styles:
   ```css
   .hero {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       color: white;
       padding: 5rem 0;
       text-align: center;
   }
   ```
   - Click "Create Section"

### Add Content to Section

1. **Add Content**
   - From section edit screen, click "Add Content"

2. **Create Content with Summernote**
   - Title: "Welcome"
   - Content: Use Summernote editor to create rich content:
   ```html
   <h1>Welcome to Our Company</h1>
   <p>We are dedicated to providing excellent service...</p>
   ```
   - Language: English
   - Click "Add Content"

### Preview and Publish

1. **Preview**
   - Click "Preview Page" to see your page
   
2. **Publish**
   - If satisfied, ensure status is "Published"
   - Your page is now live!

## Structure Overview

```
📄 Page (About Us)
  └── 🎨 Template (Default Template)
       └── 📦 Section (Hero Section)
            └── 📝 Content (Welcome message with Summernote)
```

## Key Features

### Monaco Editor (Templates & Sections)
- **HTML Tab**: Write your HTML structure
- **CSS Tab**: Add styling
- **JavaScript Tab**: Add interactivity
- Dark theme with syntax highlighting
- Auto-completion

### Summernote Editor (Content)
- Rich text editing
- Image upload
- Video embedding
- Tables
- Code view
- Fullscreen mode

### Drag & Drop
- Reorder sections by dragging
- Changes save automatically

## Common Tasks

### Create a Custom Template

1. Go to `/admin/templates/create`
2. Name: "Two Column Layout"
3. HTML (Monaco Editor):
```html
<!DOCTYPE html>
<html>
<head>
    <title>{{ page_title }}</title>
    {{ custom_css }}
</head>
<body>
    <header>
        <h1>{{ site_name }}</h1>
        <nav>{{ menu_items }}</nav>
    </header>
    <main class="two-column">
        <div class="content">
            {{ page_sections }}
        </div>
        <aside class="sidebar">
            <h3>Sidebar</h3>
        </aside>
    </main>
    {{ custom_js }}
</body>
</html>
```
4. CSS:
```css
.two-column {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}
```
5. Save template

### Set Homepage

1. Edit any page
2. Check "Set as Homepage"
3. Save
4. This page will now appear at `/`

### Add to Menu

1. Edit page
2. Check "Show in Menu"
3. Set "Menu Order" (lower numbers appear first)
4. Save

## Template Variables

Use these in your templates:

| Variable | Description |
|----------|-------------|
| `{{ page_title }}` | Page title |
| `{{ meta_description }}` | Meta description |
| `{{ meta_keywords }}` | Meta keywords |
| `{{ site_name }}` | Site name from .env |
| `{{ menu_items }}` | Navigation menu HTML |
| `{{ page_sections }}` | All page sections |
| `{{ custom_css }}` | CSS injection point |
| `{{ custom_js }}` | JavaScript injection point |

## Tips

1. **Start with Default Template**: Modify it to learn the system
2. **Use Sections for Modularity**: Create reusable section templates
3. **Preview Often**: Check your changes before publishing
4. **SEO Matters**: Always fill meta descriptions and titles
5. **Organize Content**: Use multiple content blocks for better organization

## Next Steps

- Explore template customization with Monaco Editor
- Create different section types (hero, features, gallery)
- Add multi-language content
- Customize CSS for your brand
- Add JavaScript interactions

## Need Help?

- Check `CMS_DOCUMENTATION.md` for detailed information
- Review database schema in `database/migrations/005_create_cms_tables.sql`
- Check browser console for errors
- Verify upload directory permissions

---

**Happy Building! 🚀**

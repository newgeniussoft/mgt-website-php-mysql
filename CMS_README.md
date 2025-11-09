# 🎨 Complete CMS Page Management System

A comprehensive, hierarchical Content Management System with visual editors for complete control over your website.

## 🌟 Features

### Hierarchical Structure
```
📄 Page → 🎨 Template → 📦 Section → 📝 Content
```

### 🎯 Core Components

#### 1. **Page Management**
- Full CRUD operations
- SEO optimization (meta tags, keywords, descriptions)
- Featured image upload
- Status management (draft, published, archived)
- Homepage designation
- Menu management with ordering
- Parent-child relationships
- Search and filtering

#### 2. **Template Management** (Monaco Editor)
- Visual code editor with syntax highlighting
- Separate HTML, CSS, and JavaScript editing
- Template variables for dynamic content
- Template preview
- Template duplication
- Default template system
- Dark theme with auto-completion

#### 3. **Section Management** (Monaco Editor)
- Modular page sections
- Drag-and-drop reordering
- Custom HTML/CSS/JS per section
- Multiple section types
- JSON-based settings
- Active/inactive toggle

#### 4. **Content Management** (Summernote)
- Rich text WYSIWYG editor
- Image and video embedding
- Multi-language support
- Content types (HTML, Text, Markdown)
- Multiple content blocks per section

## 🚀 Installation

```bash
# Run installation script
php install_cms.php
```

## 📁 File Structure

```
app/
├── Models/
│   ├── Page.php          # Page model with CRUD operations
│   ├── Template.php      # Template model with rendering
│   ├── Section.php       # Section model with ordering
│   └── Content.php       # Content model with multi-language
├── Http/Controllers/
│   ├── PageController.php      # Page CRUD operations
│   ├── TemplateController.php  # Template management
│   └── SectionController.php   # Section & content management
database/
└── migrations/
    └── 005_create_cms_tables.sql  # Complete database schema
resources/
└── views/
    └── admin/
        ├── pages/        # Page management views
        ├── templates/    # Template editor views (Monaco)
        └── sections/     # Section & content views (Monaco + Summernote)
```

## 🎨 Editors

### Monaco Editor (Templates & Sections)
- Professional code editor by Microsoft
- Syntax highlighting for HTML, CSS, JavaScript
- IntelliSense auto-completion
- Dark theme
- Multi-tab interface
- Minimap navigation

### Summernote (Content)
- WYSIWYG rich text editor
- Image upload and embedding
- Video embedding
- Table creation
- Code view
- Fullscreen mode
- Font and color styling

## 📊 Database Schema

### Tables
- **templates**: HTML/CSS/JS templates with Monaco editor
- **pages**: Main pages with SEO and settings
- **sections**: Modular page sections with ordering
- **contents**: Rich content blocks with multi-language

### Relationships
```
templates (1) ──→ (N) pages
pages (1) ──→ (N) sections
sections (1) ──→ (N) contents
```

## 🔗 Routes

### Admin Routes
```
/admin/pages              # Page management
/admin/templates          # Template management
/admin/sections           # Section management
/admin/sections/*-content # Content management
```

## 🎯 Quick Start

### 1. Create a Template
```
/admin/templates/create
→ Design with Monaco Editor (HTML/CSS/JS)
→ Use template variables
→ Save
```

### 2. Create a Page
```
/admin/pages/create
→ Enter title and SEO info
→ Select template
→ Set status and options
→ Save
```

### 3. Add Sections
```
/admin/sections?page_id=X
→ Create section
→ Design with Monaco Editor
→ Add CSS and JavaScript
→ Save
```

### 4. Add Content
```
/admin/sections/edit?id=X
→ Add content
→ Use Summernote editor
→ Save
```

## 🎨 Template Variables

Use in your templates:

```html
{{ page_title }}        - Page title
{{ meta_description }}  - Meta description
{{ meta_keywords }}     - Meta keywords
{{ site_name }}         - Site name
{{ menu_items }}        - Navigation menu
{{ page_sections }}     - Page sections
{{ custom_css }}        - CSS injection
{{ custom_js }}         - JavaScript injection
```

## 📝 Example Template

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
    <footer>
        <p>&copy; 2024 {{ site_name }}</p>
    </footer>
    {{ custom_js }}
</body>
</html>
```

## 🔒 Security Features

- ✅ CSRF token protection
- ✅ SQL injection prevention (PDO)
- ✅ XSS protection
- ✅ File upload validation
- ✅ Authentication required
- ✅ Input sanitization

## 📚 Documentation

- **CMS_DOCUMENTATION.md**: Complete documentation
- **CMS_QUICKSTART.md**: Quick start guide
- **Database Schema**: `database/migrations/005_create_cms_tables.sql`

## 🛠️ Technologies

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 4, jQuery
- **Editors**: Monaco Editor, Summernote
- **Drag & Drop**: SortableJS
- **Icons**: Font Awesome

## 🎯 Use Cases

- Corporate websites
- Blogs and news sites
- Portfolio websites
- Landing pages
- Marketing pages
- Multi-language sites

## 🌈 Benefits

1. **Complete Control**: Edit every aspect of your pages
2. **Professional Editors**: Monaco and Summernote
3. **Modular Design**: Reusable sections and templates
4. **SEO Optimized**: Built-in SEO tools
5. **Multi-Language**: Support for multiple languages
6. **Drag & Drop**: Easy section reordering
7. **Preview**: Live preview before publishing

## 📖 Workflow

```
1. Design Template (Monaco Editor)
   ↓
2. Create Page (Select Template)
   ↓
3. Add Sections (Monaco Editor)
   ↓
4. Add Content (Summernote)
   ↓
5. Preview & Publish
```

## 🎓 Learning Path

1. Start with default template
2. Create your first page
3. Add a simple section
4. Add content with Summernote
5. Preview and publish
6. Create custom template
7. Build complex sections
8. Master template variables

## 🤝 Support

For help:
1. Check documentation files
2. Review code comments
3. Check browser console
4. Verify database structure

## 📄 License

This CMS system is part of the Madagascar Green Tours project.

---

**Built with ❤️ for easy content management**

**Version**: 1.0.0  
**Last Updated**: 2024

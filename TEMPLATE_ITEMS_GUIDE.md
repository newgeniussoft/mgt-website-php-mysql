# Template Items System - Complete Guide

## Overview

The Template Items System allows you to create reusable, customizable templates for displaying items from different models (media, posts, pages, tours, etc.) throughout your website. Each template can have:

- **Dynamic Variables** - Automatically extracted from your HTML
- **Custom HTML** - Full control over markup structure
- **Custom CSS** - Styling specific to each template
- **Custom JavaScript** - Interactive behaviors
- **Model Association** - Templates specific to data models
- **Default Templates** - Set preferred templates per model

## Installation

1. Run the installation script:
   ```
   http://yoursite.com/install_template_items.php
   ```

2. Access the Template Items manager:
   ```
   /admin/template-items
   ```

## Table Structure

```sql
template_items
├── id (int) - Primary key
├── name (varchar) - Template name
├── slug (varchar) - URL-friendly identifier
├── description (text) - Template description
├── model_name (varchar) - Associated model (media, post, page, tour, etc.)
├── html_template (longtext) - HTML template with {{ $item.variable }} syntax
├── css_styles (text) - Custom CSS styles
├── js_code (text) - Custom JavaScript code
├── variables (text) - JSON array of variables [{key, label, type, default}]
├── default_keys (varchar) - Comma-separated default display keys
├── thumbnail (varchar) - Preview image path
├── is_default (tinyint) - Default template for model flag
├── status (enum) - active, draft, archived
├── created_at (timestamp)
└── updated_at (timestamp)
```

## Template Variable Syntax

### Basic Variable Usage

Use `{{ $item.fieldname }}` to display data from your items:

```html
<div class="item">
    <h3>{{ $item.title }}</h3>
    <p>{{ $item.description }}</p>
    <img src="{{ $item.image }}" alt="{{ $item.title }}">
    <span class="price">{{ $item.price }}</span>
</div>
```

### Available Variables by Model

#### Media Model
```
{{ $item.url }}
{{ $item.original_filename }}
{{ $item.file_type }}
{{ $item.file_size }}
{{ $item.alt_text }}
{{ $item.title }}
```

#### Post Model
```
{{ $item.title }}
{{ $item.slug }}
{{ $item.excerpt }}
{{ $item.content }}
{{ $item.featured_image }}
{{ $item.author }}
{{ $item.created_at }}
```

#### Page Model
```
{{ $item.title }}
{{ $item.slug }}
{{ $item.content }}
{{ $item.meta_description }}
{{ $item.featured_image }}
```

#### Tour Model
```
{{ $item.name }}
{{ $item.slug }}
{{ $item.description }}
{{ $item.image }}
{{ $item.price }}
{{ $item.duration }}
{{ $item.location }}
```

## Creating a Template

### Method 1: Using the Admin Interface

1. Navigate to `/admin/template-items`
2. Click "Create New Template"
3. Fill in the basic information:
   - **Name**: Descriptive name (e.g., "Blog Post Card")
   - **Description**: What this template is for
   - **Model Name**: Select the model (post, media, page, etc.)
   - **Default Keys**: Comma-separated fields to display

4. Write your HTML template:
   ```html
   <article class="blog-card">
       <img src="{{ $item.featured_image }}" alt="{{ $item.title }}">
       <h3>{{ $item.title }}</h3>
       <p>{{ $item.excerpt }}</p>
       <a href="/post/{{ $item.slug }}">Read More</a>
   </article>
   ```

5. Click "Extract Variables" to automatically detect variables
6. Add custom CSS:
   ```css
   .blog-card {
       border: 1px solid #ddd;
       border-radius: 8px;
       padding: 20px;
       transition: transform 0.3s;
   }
   .blog-card:hover {
       transform: translateY(-5px);
       box-shadow: 0 5px 15px rgba(0,0,0,0.1);
   }
   ```

7. Optionally add JavaScript for interactivity
8. Upload a thumbnail image
9. Set status and default template option
10. Click "Create Template"

### Method 2: Using the Database

```php
use App\Models\TemplateItem;

$template = new TemplateItem();
$template->name = 'Product Card';
$template->slug = TemplateItem::generateSlug('Product Card');
$template->model_name = 'product';
$template->html_template = '<div class="product">...</div>';
$template->css_styles = '.product { ... }';
$template->variables = json_encode([
    ['key' => 'name', 'label' => 'Name', 'type' => 'text', 'default' => ''],
    ['key' => 'price', 'label' => 'Price', 'type' => 'number', 'default' => '0']
]);
$template->is_default = 1;
$template->status = 'active';
$template->save();
```

## Using Templates

### In Page Sections

Use the `<items>` tag with your template:

```html
<!-- Use default template for model -->
<items name="post" limit="6" />

<!-- Use specific template by slug -->
<items name="media" template="media-grid" limit="12" />

<!-- Specify which fields to display -->
<items name="tour" template="tour-card" keys="name,image,price,location" limit="8" />
```

### In PHP Code

```php
use App\Models\TemplateItem;
use App\Models\Post;

// Get template
$template = TemplateItem::getBySlug('blog-post-card');

// Get items
$posts = Post::getPublished();

// Render each item
foreach ($posts as $post) {
    echo $template->render($post);
}
```

### Programmatic Rendering

```php
$template = TemplateItem::getDefaultForModel('media');

if ($template) {
    $media = Media::limit(10)->get();
    
    echo '<div class="media-grid">';
    foreach ($media as $item) {
        echo $template->render($item);
    }
    echo '</div>';
}
```

## Model Methods

### TemplateItem Model Methods

```php
// Get all active templates
$templates = TemplateItem::getActive();

// Get template by slug
$template = TemplateItem::getBySlug('blog-post-card');

// Get templates for specific model
$templates = TemplateItem::getByModel('post');

// Get default template for model
$template = TemplateItem::getDefaultForModel('media');

// Generate unique slug
$slug = TemplateItem::generateSlug('My Template');

// Get variables as array
$variables = $template->getVariablesArray();

// Add a variable
$template->addVariable('price', 'Price', 'number', '0');

// Remove a variable
$template->removeVariable('old_field');

// Extract variables from HTML
$variables = $template->extractVariablesFromTemplate();

// Render template with data
$html = $template->render($item);

// Duplicate template
$newTemplate = $template->duplicate();
```

## Default Templates

The system comes with 4 pre-built templates:

### 1. Media Grid Template
- **Model**: media
- **Features**: Thumbnail, filename, type, size, download button
- **Use Case**: Photo galleries, file libraries

### 2. Blog Post Card
- **Model**: post
- **Features**: Featured image, title, excerpt, author, date
- **Use Case**: Blog listings, article grids

### 3. Tour Package Card
- **Model**: tour
- **Features**: Image, name, location, duration, price, booking button
- **Use Case**: Travel packages, tour listings

### 4. Page List Item
- **Model**: page
- **Features**: Icon, title, description, view link
- **Use Case**: Page directories, navigation

## Advanced Features

### Custom Variable Types

When defining variables, you can specify different types:

- **text** - Standard text input
- **number** - Numeric values
- **url** - URL/link fields
- **date** - Date/time fields

```php
$template->addVariable('publish_date', 'Publish Date', 'date', '');
$template->addVariable('rating', 'Rating', 'number', '5');
$template->addVariable('website', 'Website URL', 'url', '');
```

### Default Values

Set default values for variables when data is missing:

```json
{
    "key": "price",
    "label": "Price",
    "type": "number",
    "default": "0.00"
}
```

### Conditional Logic in Templates

While the system doesn't support complex conditionals in templates, you can handle this in your models:

```php
// In your model
public function getDisplayPrice() {
    return $this->price > 0 ? '$' . $this->price : 'Free';
}

// In template
{{ $item.display_price }}
```

### Responsive Design

Add responsive CSS to your templates:

```css
.item-card {
    width: 100%;
}

@media (min-width: 768px) {
    .item-card {
        width: 48%;
    }
}

@media (min-width: 1024px) {
    .item-card {
        width: 31%;
    }
}
```

## Best Practices

### 1. Template Organization
- Use descriptive names for templates
- Add detailed descriptions
- Keep templates focused on single use cases
- Group related templates by model

### 2. Variable Naming
- Use clear, descriptive variable names
- Follow snake_case convention (e.g., `featured_image`, `publish_date`)
- Keep variable names consistent across templates

### 3. CSS Scoping
- Use unique class prefixes for each template
- Avoid global style modifications
- Test templates in isolation

### 4. Performance
- Minimize CSS and JavaScript code
- Use efficient selectors
- Avoid heavy computations in templates
- Cache rendered templates when possible

### 5. Accessibility
- Include alt text for images
- Use semantic HTML elements
- Ensure proper heading hierarchy
- Add ARIA labels where needed

## Troubleshooting

### Variables Not Showing

**Problem**: Variables display as `{{ $item.field }}` instead of actual values

**Solutions**:
1. Check variable is properly extracted
2. Verify field exists in model
3. Ensure template variables are saved
4. Check variable JSON format

### Styling Not Applied

**Problem**: CSS styles not showing

**Solutions**:
1. Check CSS syntax is valid
2. Verify template status is "active"
3. Clear browser cache
4. Check for CSS conflicts

### Template Not Available

**Problem**: Template doesn't appear in dropdown

**Solutions**:
1. Ensure status is "active"
2. Check model_name matches usage
3. Verify template is saved
4. Check for duplicate slugs

## API Reference

### Routes

```
GET    /admin/template-items                    - List all templates
GET    /admin/template-items/create             - Create form
POST   /admin/template-items/store              - Save new template
GET    /admin/template-items/edit?id={id}       - Edit form
POST   /admin/template-items/update             - Update template
POST   /admin/template-items/delete             - Delete template
GET    /admin/template-items/duplicate?id={id}  - Duplicate template
GET    /admin/template-items/preview?id={id}    - Preview template
POST   /admin/template-items/extract-variables  - Extract variables (AJAX)
```

### AJAX Variable Extraction

```javascript
fetch('/admin/template-items/extract-variables', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'html=' + encodeURIComponent(htmlContent)
})
.then(response => response.json())
.then(data => {
    console.log(data.variables);
});
```

## Examples

### Example 1: Product Grid

```html
<div class="product-card">
    <div class="product-image">
        <img src="{{ $item.image }}" alt="{{ $item.name }}">
        <span class="badge">{{ $item.category }}</span>
    </div>
    <div class="product-info">
        <h4>{{ $item.name }}</h4>
        <p>{{ $item.description }}</p>
        <div class="product-footer">
            <span class="price">${{ $item.price }}</span>
            <button class="btn-buy">Add to Cart</button>
        </div>
    </div>
</div>
```

### Example 2: Team Member Card

```html
<div class="team-member">
    <img src="{{ $item.photo }}" alt="{{ $item.name }}" class="member-photo">
    <h5>{{ $item.name }}</h5>
    <p class="position">{{ $item.position }}</p>
    <p class="bio">{{ $item.bio }}</p>
    <div class="social-links">
        <a href="{{ $item.linkedin }}"><i class="fab fa-linkedin"></i></a>
        <a href="{{ $item.twitter }}"><i class="fab fa-twitter"></i></a>
    </div>
</div>
```

### Example 3: Testimonial Card

```html
<div class="testimonial">
    <div class="quote-icon">"</div>
    <p class="testimonial-text">{{ $item.testimonial }}</p>
    <div class="author">
        <img src="{{ $item.author_photo }}" alt="{{ $item.author_name }}">
        <div>
            <strong>{{ $item.author_name }}</strong>
            <small>{{ $item.author_title }}</small>
        </div>
    </div>
</div>
```

## Support & Resources

- **Documentation**: `/TEMPLATE_ITEMS_GUIDE.md`
- **Admin Panel**: `/admin/template-items`
- **Installation**: `/install_template_items.php`

## License

Part of the MGT CMS v5.2 - All rights reserved.

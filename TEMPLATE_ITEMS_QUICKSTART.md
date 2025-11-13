# Template Items - Quick Start Guide

## 🚀 Installation (1 minute)

```
1. Visit: http://yoursite.com/install_template_items.php
2. Click "Go to Template Items"
3. Done!
```

## 📝 Basic Usage

### Create a Template

1. Go to `/admin/template-items`
2. Click **"Create New Template"**
3. Fill in:
   - **Name**: "My Product Card"
   - **Model**: Select "product" (or your model)
   - **HTML Template**:
     ```html
     <div class="item">
         <h3>{{ $item.name }}</h3>
         <p>{{ $item.description }}</p>
         <span>${{ $item.price }}</span>
     </div>
     ```
4. Click **"Extract Variables"** (auto-detects `name`, `description`, `price`)
5. Add CSS (optional):
   ```css
   .item {
       border: 1px solid #ddd;
       padding: 20px;
       border-radius: 8px;
   }
   ```
6. Click **"Create Template"**

### Use the Template

In your page sections:

```html
<items name="product" template="my-product-card" limit="6" />
```

That's it! Your items will now display using your custom template.

## 🎯 Quick Examples

### Blog Posts

```html
<div class="post">
    <img src="{{ $item.featured_image }}" alt="{{ $item.title }}">
    <h3>{{ $item.title }}</h3>
    <p>{{ $item.excerpt }}</p>
    <a href="/post/{{ $item.slug }}">Read More</a>
</div>
```

### Media Gallery

```html
<div class="media-item">
    <img src="{{ $item.url }}" alt="{{ $item.original_filename }}">
    <p>{{ $item.original_filename }}</p>
    <a href="{{ $item.url }}" download>Download</a>
</div>
```

### Tour Packages

```html
<div class="tour">
    <img src="{{ $item.image }}" alt="{{ $item.name }}">
    <h3>{{ $item.name }}</h3>
    <p>📍 {{ $item.location }}</p>
    <p>⏱️ {{ $item.duration }} days</p>
    <span class="price">${{ $item.price }}</span>
</div>
```

## 💡 Key Features

- **Auto Variable Detection**: Click "Extract Variables" to find all `{{ $item.field }}` automatically
- **Custom Styling**: Add CSS directly in the template
- **JavaScript Support**: Add interactivity with custom JS
- **Default Templates**: Mark one template as default per model
- **Preview**: Test templates before publishing

## 📚 Variable Syntax

```html
{{ $item.fieldname }}     <!-- Display field value -->
```

Common fields by model:

| Model   | Common Fields |
|---------|--------------|
| post    | title, excerpt, featured_image, slug |
| media   | url, original_filename, file_type, file_size |
| page    | title, content, meta_description, slug |
| tour    | name, image, price, duration, location |

## ⚙️ Admin Routes

- **List**: `/admin/template-items`
- **Create**: `/admin/template-items/create`
- **Edit**: `/admin/template-items/edit?id=X`
- **Preview**: `/admin/template-items/preview?id=X`

## 🛠️ Model Methods

```php
// Get template
$template = TemplateItem::getBySlug('my-template');

// Render with data
$html = $template->render($item);

// Get default for model
$template = TemplateItem::getDefaultForModel('post');
```

## 🎨 4 Default Templates Included

1. **Media Grid** - For images and files
2. **Blog Post Card** - For blog posts
3. **Tour Package Card** - For tours
4. **Page List Item** - For pages

## 🔧 Tips

1. **Test First**: Use preview to check templates before saving
2. **Extract Variables**: Always click "Extract Variables" after writing HTML
3. **Unique Classes**: Use unique CSS class names to avoid conflicts
4. **Set Defaults**: Mark frequently used templates as default
5. **Organize**: Use descriptive names and add descriptions

## 📖 Need More Help?

See full documentation: `TEMPLATE_ITEMS_GUIDE.md`

---

**Happy templating! 🎉**

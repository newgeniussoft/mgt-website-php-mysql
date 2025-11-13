# Blade @verbatim Directive Usage

## Overview

The `@verbatim` directive in Blade allows you to display double curly braces `{{ }}` without them being processed by the Blade template engine. This is essential when you need to show template variable syntax in documentation or when working with JavaScript frameworks that also use `{{ }}` syntax.

## Problem

When you write this in a Blade template:
```blade
<code>{{ '{{ variable }}' }}</code>
```

Blade tries to process the inner `{{ variable }}` and may cause errors or unexpected output.

## Solution: @verbatim Directive

Use the `@verbatim` directive to preserve the double braces:

```blade
<code>@verbatim{{ variable }}@endverbatim</code>
```

This will output exactly: `{{ variable }}`

## How It Works

The BladeEngine has been updated with two methods:

1. **compileVerbatimBlocks()**: Runs before other compilation steps
   - Finds all `@verbatim...@endverbatim` blocks
   - Replaces them with unique placeholders
   - Stores the original content

2. **restoreVerbatimBlocks()**: Runs after all compilation
   - Replaces placeholders with original content
   - Content is preserved exactly as written

## Usage Examples

### Single Variable
```blade
<li><code>@verbatim{{ page_title }}@endverbatim</code> - Page title</li>
```
**Output**: `{{ page_title }}` - Page title

### Multiple Variables
```blade
<ul>
    <li><code>@verbatim{{ page_title }}@endverbatim</code></li>
    <li><code>@verbatim{{ meta_description }}@endverbatim</code></li>
    <li><code>@verbatim{{ site_name }}@endverbatim</code></li>
</ul>
```

### Code Block
```blade
<pre><code>@verbatim
<!DOCTYPE html>
<html>
<head>
    <title>{{ page_title }}</title>
</head>
<body>
    {{ content }}
</body>
</html>
@endverbatim</code></pre>
```

### JavaScript Integration
```blade
<script>
@verbatim
var app = new Vue({
    el: '#app',
    data: {
        message: '{{ message }}'
    }
});
@endverbatim
</script>
```

## Where It's Used in CMS

### Template Management Views
- `resources/views/admin/templates/create.blade.php`
- `resources/views/admin/templates/edit.blade.php`

Shows available template variables:
```blade
<li><code>@verbatim{{ page_title }}@endverbatim</code></li>
<li><code>@verbatim{{ meta_description }}@endverbatim</code></li>
<li><code>@verbatim{{ site_name }}@endverbatim</code></li>
```

### Section Management Views
- `resources/views/admin/sections/create.blade.php`

Shows available section variables:
```blade
<li><code>@verbatim{{ content }}@endverbatim</code> - Section content</li>
```

## Compilation Order

The BladeEngine processes directives in this order:

1. `@extends`, `@section`, `@yield`, `@include`
2. `@component`, `@stack`, `@push`
3. `@php`, `@if`, `@foreach`, `@switch`
4. Helper functions (`@url`, `@asset`, etc.)
5. **`@verbatim` blocks are extracted** ← Happens here
6. `{!! raw echo !!}`
7. `{{ escaped echo }}`
8. **`@verbatim` blocks are restored** ← Happens here

This ensures verbatim content is never processed by Blade.

## Alternative: Raw Echo

For single variables, you can also use raw echo with quotes:
```blade
<code>{!! '{{ variable }}' !!}</code>
```

But `@verbatim` is cleaner and more semantic:
```blade
<code>@verbatim{{ variable }}@endverbatim</code>
```

## Benefits

1. **Clean Syntax**: More readable than escaping
2. **Safe**: Content is never processed
3. **Flexible**: Works with any content, not just variables
4. **Standard**: Same syntax as Laravel Blade

## Common Use Cases

### 1. Documentation
Show template syntax to users:
```blade
<p>Use @verbatim{{ page_title }}@endverbatim in your template</p>
```

### 2. JavaScript Frameworks
Prevent Blade from processing Vue/Angular syntax:
```blade
<div id="app">
    @verbatim
    <h1>{{ message }}</h1>
    @endverbatim
</div>
```

### 3. Code Examples
Display code snippets:
```blade
<pre>@verbatim
Template example:
{{ page_title }}
{{ content }}
@endverbatim</pre>
```

### 4. API Documentation
Show JSON response examples:
```blade
<code>@verbatim
{
    "title": "{{ title }}",
    "content": "{{ content }}"
}
@endverbatim</code>
```

## Tips

1. **Keep it minimal**: Only wrap what needs protection
2. **Indent properly**: Verbatim content preserves whitespace
3. **Nest carefully**: Don't nest `@verbatim` blocks
4. **Use for blocks**: For multiple variables, wrap the whole block

## Troubleshooting

### Issue: Variables still being processed
**Solution**: Make sure you're using `@verbatim` not `@verbatim()`

### Issue: Extra whitespace in output
**Solution**: Verbatim preserves all whitespace. Format your code accordingly:
```blade
<code>@verbatim{{ variable }}@endverbatim</code>
```
Not:
```blade
<code>
    @verbatim
    {{ variable }}
    @endverbatim
</code>
```

### Issue: Syntax highlighting broken
**Solution**: Your IDE may not recognize `@verbatim`. This is cosmetic only.

## Clear Cache

After updating BladeEngine, clear the view cache:
```php
// In your browser or CLI
php -r "array_map('unlink', glob('storage/cache/views/*.php'));"
```

Or manually delete files in `storage/cache/views/`

---

**Updated**: BladeEngine now supports `@verbatim` directive  
**Location**: `app/View/BladeEngine.php`  
**Methods**: `compileVerbatimBlocks()`, `restoreVerbatimBlocks()`

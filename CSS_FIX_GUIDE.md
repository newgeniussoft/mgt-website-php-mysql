# CSS/JS Not Taking Effect - Fix Guide

## Problem
CSS files are accessible via direct URL but styles are not being applied when referenced in pages.

## Root Cause
The most common cause is **incorrect Content-Type header**. When CSS files are served with the wrong MIME type (e.g., `text/plain` or `text/html` instead of `text/css`), browsers refuse to apply them for security reasons.

## Fixes Applied

### 1. Fixed `config/app.php`
**File**: `config/app.php`

Created proper configuration that reads from `.env`:
```php
<?php

return [
    'name' => env('APP_NAME', 'Madagascar Green Tours'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost/mgt-v5_2'),
    'admin_prefix' => env('APP_ADMIN_PREFIX', 'cpanel'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
];
```

### 2. Fixed Root `.htaccess`
**File**: `.htaccess`

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect all requests to public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 3. Enhanced `public/.htaccess` with MIME Types
**File**: `public/.htaccess`

Added explicit MIME type declarations:
```apache
# Force correct MIME types for static assets
<IfModule mod_mime.c>
    AddType text/css .css
    AddType application/javascript .js
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
    AddType font/woff .woff
    AddType font/woff2 .woff2
    AddType font/ttf .ttf
    AddType font/otf .otf
    AddType application/vnd.ms-fontobject .eot
</IfModule>
```

## Testing Steps

### Step 1: Test Static HTML File
1. Open: `http://localhost/mgt-v5_2/test-css-loading.html`
2. Check if the text appears **GREEN**
3. Check browser console for errors

### Step 2: Test PHP URL Generation
1. Open: `http://localhost/mgt-v5_2/debug-url.php`
2. Verify generated URLs are correct
3. Click test links to ensure files are accessible

### Step 3: Test Full Page
1. Open: `http://localhost/mgt-v5_2/check-css.php`
2. Check all diagnostic information
3. Verify CSS is loading and applying

### Step 4: Browser DevTools Check
1. Press **F12** to open DevTools
2. Go to **Network** tab
3. Refresh page (Ctrl+F5)
4. Find `styles.css` in the list
5. Check:
   - **Status**: Should be `200` (not 404)
   - **Type**: Should be `css` or `stylesheet`
   - **Size**: Should be ~54 KB
6. Click on `styles.css` and check **Headers** tab:
   - **Content-Type**: MUST be `text/css`

### Step 5: Console Check
1. Go to **Console** tab in DevTools
2. Look for errors like:
   - "Refused to apply style from ... because its MIME type ('text/html') is not a supported stylesheet MIME type"
   - "Failed to load resource: the server responded with a status of 404"

## Common Issues & Solutions

### Issue 1: Wrong Content-Type
**Symptom**: Console shows "MIME type is not a supported stylesheet MIME type"

**Solution**: 
- Ensure `public/.htaccess` has MIME type declarations (already added)
- Restart Apache: `sudo service apache2 restart` or restart XAMPP

### Issue 2: 404 Not Found
**Symptom**: Network tab shows 404 for CSS files

**Solution**:
- Check `APP_URL` in `.env` matches your actual URL
- Verify files exist in `public/css/` and `public/js/`
- Check `.htaccess` files are present and correct

### Issue 3: Wrong URL Generated
**Symptom**: `asset()` generates incorrect URLs

**Solution**:
- Update `.env` file: `APP_URL=http://localhost/mgt-v5_2`
- Clear browser cache (Ctrl+F5)
- Check `config/app.php` exists and is not empty

### Issue 4: Cached Old Version
**Symptom**: Changes to CSS don't appear

**Solution**:
- Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
- Clear browser cache completely
- Try incognito/private mode
- Add cache busting: `asset('css/styles.css?v=' . time())`

### Issue 5: .htaccess Not Working
**Symptom**: Static files return 404 or wrong content

**Solution**:
1. Check if `mod_rewrite` is enabled in Apache
2. Check if `mod_mime` is enabled
3. Verify `AllowOverride All` in Apache config
4. Restart Apache after changes

## File Structure
```
public/
├── .htaccess (with MIME types)
├── css/
│   ├── styles.css (53,931 bytes)
│   ├── bootstrap.min.css
│   └── ... other CSS files
├── js/
│   ├── bootstrap.min.js
│   ├── jquery-3.2.1.slim.min.js
│   └── ... other JS files
└── fonts/
    └── ... font files
```

## Expected Behavior

### Correct Asset URLs
With `APP_URL=http://localhost/mgt-v5_2`:
- `asset('css/styles.css')` → `http://localhost/mgt-v5_2/css/styles.css`
- `asset('js/bootstrap.min.js')` → `http://localhost/mgt-v5_2/js/bootstrap.min.js`

### Correct HTTP Headers
When accessing `http://localhost/mgt-v5_2/css/styles.css`:
```
HTTP/1.1 200 OK
Content-Type: text/css
Content-Length: 53931
```

## Verification Checklist

- [ ] `.env` has correct `APP_URL`
- [ ] `config/app.php` exists and is not empty
- [ ] Root `.htaccess` redirects to `public/`
- [ ] `public/.htaccess` has MIME type declarations
- [ ] CSS files exist in `public/css/`
- [ ] JS files exist in `public/js/`
- [ ] Apache `mod_rewrite` is enabled
- [ ] Apache `mod_mime` is enabled
- [ ] Browser cache cleared
- [ ] DevTools shows `Content-Type: text/css`
- [ ] DevTools shows status `200` for CSS files
- [ ] Console has no MIME type errors
- [ ] Test page shows green text

## Quick Diagnostic Command

Open browser console and run:
```javascript
fetch('/mgt-v5_2/css/styles.css')
  .then(r => {
    console.log('Status:', r.status);
    console.log('Content-Type:', r.headers.get('content-type'));
    return r.text();
  })
  .then(css => console.log('CSS loaded:', css.length, 'characters'));
```

Expected output:
```
Status: 200
Content-Type: text/css
CSS loaded: 53931 characters
```

## If Still Not Working

1. **Check Apache Error Log**:
   - XAMPP: `C:\xampp\apache\logs\error.log`
   - Look for rewrite or permission errors

2. **Test with absolute path**:
   ```html
   <link rel="stylesheet" href="/mgt-v5_2/css/styles.css">
   ```

3. **Verify file permissions**:
   - Directories: 755
   - Files: 644

4. **Disable browser extensions**:
   - Ad blockers might interfere
   - Try incognito mode

5. **Check for conflicting CSS**:
   - Other stylesheets might override
   - Use DevTools to inspect element styles

## Test Files Created

1. **test-css-loading.html** - Static HTML test (no PHP)
2. **debug-url.php** - URL generation diagnostic
3. **check-css.php** - Comprehensive CSS loading test

Access these at:
- `http://localhost/mgt-v5_2/test-css-loading.html`
- `http://localhost/mgt-v5_2/debug-url.php`
- `http://localhost/mgt-v5_2/check-css.php`

## Success Indicators

✅ Text appears GREEN on test pages
✅ DevTools Network tab shows `200` status
✅ DevTools shows `Content-Type: text/css`
✅ Console has no errors
✅ Computed styles show correct values
✅ Fonts render correctly

## Next Steps After Fix

1. Test all pages in your application
2. Clear any PHP opcode cache if using
3. Test in different browsers
4. Remove test files if desired (optional)
5. Document any custom configurations

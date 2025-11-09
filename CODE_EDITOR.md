# Code Editor Documentation

## Overview
A professional code editor with file explorer sidebar, powered by CodeMirror. Edit any file in your project directly from the browser.

## Features

### ✅ File Explorer Sidebar
- **Tree View** - Browse all project files and folders
- **Collapsible Folders** - Expand/collapse folder structure
- **File Icons** - Visual file type indicators
- **Click to Open** - Click any file to edit
- **Search Files** - Quick file search
- **Refresh Tree** - Reload file structure

### ✅ CodeMirror Editor
- **Syntax Highlighting** - PHP, JavaScript, CSS, HTML, SQL, JSON, YAML, Markdown
- **Line Numbers** - Easy navigation
- **Auto-Closing Brackets** - Automatic bracket completion
- **Bracket Matching** - Highlight matching brackets
- **Active Line Highlight** - Current line highlighting
- **Code Folding** - Collapse code blocks
- **Search & Replace** - Find and replace text
- **Multiple Themes** - Monokai (default), Dracula, Material

### ✅ File Operations
- **Save File** - Save changes (Ctrl+S)
- **Create File** - Create new files
- **Create Folder** - Create new folders
- **Rename File** - Rename files
- **Delete File** - Delete files
- **Auto Backup** - Automatic backup before save

### ✅ Editor Features
- **Multi-Mode Support** - Detects file type automatically
- **Line Wrapping** - Wrap long lines
- **Indentation** - Smart indentation (4 spaces)
- **Keyboard Shortcuts** - Ctrl+S (save), Ctrl+F (find), Ctrl+H (replace)
- **Status Bar** - Line/column, character count, line count

## Supported File Types

### Editable Files
- **PHP** - `.php`, `.blade.php`
- **JavaScript** - `.js`
- **CSS** - `.css`
- **HTML** - `.html`
- **JSON** - `.json`
- **XML** - `.xml`
- **SQL** - `.sql`
- **Markdown** - `.md`
- **YAML** - `.yml`, `.yaml`
- **Config** - `.ini`, `.conf`, `.env`, `.htaccess`
- **Text** - `.txt`

### Syntax Modes
```php
'php' => 'application/x-httpd-php'
'js' => 'text/javascript'
'json' => 'application/json'
'css' => 'text/css'
'html' => 'text/html'
'xml' => 'application/xml'
'sql' => 'text/x-sql'
'md' => 'text/x-markdown'
'yml' => 'text/x-yaml'
```

## Usage Guide

### Accessing Code Editor
Navigate to: `http://localhost:8000/cpanel/codeeditor`

### Opening Files

**From File Explorer:**
1. Browse file tree in left sidebar
2. Click folder to expand
3. Click file to open

**Direct URL:**
```
http://localhost:8000/cpanel/codeeditor?file=app/Models/User.php
```

### Editing Files

1. Open file from explorer
2. Make changes in editor
3. Press **Ctrl+S** or click **Save** button
4. File is saved with automatic backup

### Creating Files

**Method 1 - Sidebar Button:**
1. Click **New File** button (+ icon)
2. Enter file path (e.g., `app/test.php`)
3. File is created and opened

**Method 2 - Menu:**
1. Click **File** menu
2. Select **New File**
3. Enter path
4. File is created

### Creating Folders

1. Click **New Folder** button (folder+ icon)
2. Enter folder path (e.g., `app/NewFolder`)
3. Folder is created
4. Refresh tree to see it

### Renaming Files

1. Right-click file in explorer
2. Select **Rename**
3. Enter new name
4. File is renamed on server

### Deleting Files

1. Right-click file in explorer
2. Select **Delete**
3. Confirm deletion
4. File is deleted (backup created)

### Searching Files

1. Type in search box at top of explorer
2. Matching files are displayed
3. Click to open

### Find & Replace

**Find:**
- Press **Ctrl+F**
- Enter search term
- Navigate results

**Replace:**
- Press **Ctrl+H**
- Enter search and replace terms
- Replace individually or all

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| Ctrl+S | Save file |
| Ctrl+F | Find text |
| Ctrl+H | Replace text |
| Ctrl+Z | Undo |
| Ctrl+Y | Redo |
| Ctrl+/ | Toggle comment |
| Tab | Indent |
| Shift+Tab | Outdent |
| Ctrl+D | Delete line |
| Ctrl+Home | Go to start |
| Ctrl+End | Go to end |

## API Endpoints

### Code Editor Routes
```php
GET  /cpanel/codeeditor                 // Main editor interface
GET  /cpanel/codeeditor/file-tree       // Get file tree (AJAX)
POST /cpanel/codeeditor/save            // Save file
POST /cpanel/codeeditor/create-file     // Create new file
POST /cpanel/codeeditor/create-folder   // Create new folder
POST /cpanel/codeeditor/delete          // Delete file
POST /cpanel/codeeditor/rename          // Rename file
GET  /cpanel/codeeditor/search          // Search files
```

### Parameters

**Save File:**
```php
file_path: string (required) - Relative path to file
content: string (required) - File content
```

**Create File:**
```php
file_path: string (required) - Directory path
file_name: string (required) - File name
```

**Create Folder:**
```php
folder_path: string (required) - Parent directory
folder_name: string (required) - Folder name
```

**Rename File:**
```php
old_path: string (required) - Current path
new_name: string (required) - New name
```

**Delete File:**
```php
file_path: string (required) - File to delete
```

**Search:**
```php
q: string (required) - Search query (min 2 chars)
```

## Controller Methods

### CodeEditorController

```php
__construct()               // Initialize project root
index()                     // Display editor
getFileTree()               // Get file tree
buildFileTree()             // Build tree recursively
save()                      // Save file with backup
createFile()                // Create new file
createFolder()              // Create new folder
deleteFile()                // Delete file with backup
renameFile()                // Rename file
search()                    // Search files
searchFiles()               // Search recursively
getFileInfo()               // Get file metadata
getEditorMode()             // Determine syntax mode
isEditable()                // Check if file is editable
sanitizePath()              // Sanitize path input
isPathSafe()                // Validate path security
```

## Security Features

### Path Security
```php
// Prevents directory traversal
sanitizePath() {
    $path = str_replace(['../', '..\\', '\\'], '', $path);
    return trim($path, '/');
}

// Validates path is within project
isPathSafe() {
    $realRoot = realpath($this->projectRoot);
    $realPath = realpath($path);
    return strpos($realPath, $realRoot) === 0;
}
```

### Filename Sanitization
```php
// Only allows safe characters
$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
```

### Automatic Backups
- Backup created before save: `file.php.backup`
- Backup created before delete: `file.php.deleted`
- Prevents accidental data loss

### Excluded Directories
These directories are hidden from file tree:
- `vendor/` - Composer dependencies
- `node_modules/` - NPM packages
- `.git/` - Git repository
- `.idea/` - IDE settings
- `old/` - Old files
- `cache/` - Cache files
- `logs/` - Log files

## File Tree Structure

```
project/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── UserController.php ✓
│   ├── Models/
│   │   └── User.php ✓
│   └── View/
│       └── View.php ✓
├── config/
│   └── database.php ✓
├── public/
│   ├── css/
│   │   └── style.css ✓
│   └── js/
│       └── app.js ✓
├── resources/
│   └── views/
│       └── home.blade.php ✓
├── routes/
│   └── web.php ✓
├── .env ✓
└── README.md ✓
```

## CodeMirror Configuration

```javascript
editor = CodeMirror.fromTextArea(textarea, {
    mode: 'application/x-httpd-php',  // Syntax mode
    theme: 'monokai',                 // Color theme
    lineNumbers: true,                // Show line numbers
    autoCloseBrackets: true,          // Auto-close brackets
    matchBrackets: true,              // Match brackets
    indentUnit: 4,                    // 4 spaces indent
    lineWrapping: true,               // Wrap long lines
    extraKeys: {
        "Ctrl-S": saveFile,           // Save shortcut
        "Ctrl-F": findText,           // Find shortcut
        "Ctrl-H": replaceText         // Replace shortcut
    }
});
```

## Themes Available

### Monokai (Default)
Dark theme with vibrant colors, popular among developers.

### Dracula
Dark theme with purple accents, easy on the eyes.

### Material
Modern dark theme inspired by Material Design.

### Default
Light theme with classic colors.

**Change Theme:**
```javascript
editor.setOption('theme', 'dracula');
```

## Best Practices

### Before Editing
1. **Backup Important Files** - Make manual backups
2. **Test Changes** - Test in development first
3. **Use Version Control** - Commit changes to Git
4. **Check Syntax** - Verify code syntax

### While Editing
1. **Save Frequently** - Press Ctrl+S often
2. **Use Search** - Find code quickly
3. **Check Line Numbers** - Navigate easily
4. **Use Indentation** - Keep code readable

### After Editing
1. **Test Changes** - Verify functionality
2. **Clear Cache** - Clear application cache
3. **Check Logs** - Look for errors
4. **Commit Changes** - Save to version control

## Troubleshooting

### Cannot Save File
**Problem:** "Failed to save file"

**Solutions:**
- Check file permissions (644 for files)
- Verify directory is writable
- Ensure disk space available
- Check file is not locked

### File Not Showing in Tree
**Problem:** File exists but not visible

**Solutions:**
- Click refresh button
- Check file extension is supported
- Verify file is not in excluded directory
- Clear browser cache

### Syntax Highlighting Not Working
**Problem:** Code appears plain text

**Solutions:**
- Check file extension
- Verify CodeMirror mode loaded
- Refresh page
- Check browser console for errors

### Cannot Create File
**Problem:** "Failed to create file"

**Solutions:**
- Check directory permissions (755)
- Verify path is valid
- Ensure parent directory exists
- Check filename is valid

## Advanced Features

### Custom Syntax Modes
Add new file types by extending `getEditorMode()`:

```php
private function getEditorMode($filePath) {
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    
    $modes = [
        'php' => 'application/x-httpd-php',
        'py' => 'text/x-python',
        'rb' => 'text/x-ruby',
        // Add more...
    ];
    
    return $modes[$extension] ?? 'text/plain';
}
```

### Custom Themes
Add new themes by including CSS:

```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/solarized.min.css">
```

### File Templates
Create templates for new files:

```php
public function createFile() {
    // ...
    $template = $this->getTemplate($fileName);
    file_put_contents($fullPath, $template);
    // ...
}

private function getTemplate($fileName) {
    if (strpos($fileName, 'Controller.php') !== false) {
        return "<?php\n\nnamespace App\\Http\\Controllers;\n\nclass Controller {\n    // Your code here\n}\n";
    }
    return '';
}
```

## Integration

### Opening Files from Other Pages
```php
<a href="{{ admin_url('codeeditor?file=app/Models/User.php') }}">
    Edit User Model
</a>
```

### Embedding in Admin Panel
Already integrated in admin sidebar under "Code Editor".

### API Usage
```javascript
// Save file via AJAX
fetch('/cpanel/codeeditor/save', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `file_path=app/test.php&content=${encodeURIComponent(code)}`
})
.then(r => r.json())
.then(data => console.log(data));
```

## Performance Tips

1. **Limit File Size** - Large files (>1MB) may be slow
2. **Close Unused Files** - Close files when done
3. **Use Search** - Don't browse entire tree
4. **Refresh Sparingly** - Only refresh when needed
5. **Clear Cache** - Clear browser cache periodically

## Security Warnings

⚠️ **Important Security Notes:**

1. **Restrict Access** - Only allow trusted admins
2. **Backup Regularly** - Always backup before editing
3. **Test Changes** - Test in development first
4. **Monitor Activity** - Log all file changes
5. **Use Version Control** - Track changes with Git

## Comparison with Other Editors

| Feature | Code Editor | VS Code | Notepad++ |
|---------|------------|---------|-----------|
| Browser-based | ✅ | ❌ | ❌ |
| Syntax Highlighting | ✅ | ✅ | ✅ |
| File Explorer | ✅ | ✅ | ✅ |
| Auto-save | ✅ | ✅ | ❌ |
| Multi-file | ❌ | ✅ | ✅ |
| Extensions | ❌ | ✅ | ✅ |
| Git Integration | ❌ | ✅ | ❌ |
| Remote Editing | ✅ | ✅ | ❌ |

---

**Your code editor is now ready for professional development!** 💻✨

Access it at: `http://localhost:8000/cpanel/codeeditor`

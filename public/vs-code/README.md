# PHP Code Editor - Full Feature File Manager

## 📦 Files Required

Place these 5 files in your web server directory:

1. **index.php** - Main editor interface
2. **api.php** - Backend API for file operations
3. **upload.php** - Chunked upload handler for large files
4. **script.js** - Frontend JavaScript logic
5. **style.css** - All styles

## 🚀 New Features Added

### ⬆️ **File Upload**
- **Drag & drop** files directly into the upload area
- **Multiple file upload** support
- **Large file support** using Resumable.js (chunked uploads)
- **Progress tracking** with visual progress bar
- **Fallback** for browsers without chunked upload support

### 📦 **Move Files/Folders**
- Right-click → Move
- Select destination folder from tree
- Works with files and entire folders
- Auto-closes tabs of moved files

### 📋 **Copy Files/Folders**
- Right-click → Copy
- Select destination folder from tree
- Recursive copy for folders
- Preserves all file contents

### ⬇️ **Download Files/Folders**
- Right-click → Download
- Files download directly
- Folders are automatically zipped before download
- No size limits

### 🗜️ **Compress to ZIP**
- Right-click → Compress to ZIP
- Enter custom ZIP filename
- Works on files and folders
- Recursive compression

### 📂 **Extract ZIP Files**
- Right-click on .zip file → Extract ZIP
- Extracts to folder with same name
- Preserves directory structure
- Automatic extraction

## 📚 Libraries Used

- **Monaco Editor** - VS Code editor engine
- **Resumable.js** - Chunked upload for large files (1MB chunks)
- **JSZip** - Client-side ZIP operations (optional)
- **PHP ZipArchive** - Server-side ZIP operations

## ⚙️ PHP Requirements

```php
// Required PHP extensions:
- ZipArchive (for compression/extraction)
- File permissions for read/write

// Increase limits in php.ini for large uploads:
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 256M
```

## 🔧 Configuration

### Change Project Directory

In **index.php**, **api.php**, and **upload.php**:

```php
define('ROOT_DIR', __DIR__ . '/project'); 
// Change to your project path
```

### Configure Upload Settings

In **upload.php**:

```php
define('UPLOAD_TEMP_DIR', __DIR__ . '/temp_uploads');
// Temporary storage for chunked uploads
```

In **script.js**:

```javascript
resumableUpload = new Resumable({
    target: 'upload.php',
    chunkSize: 1 * 1024 * 1024, // 1MB chunks (adjust as needed)
    simultaneousUploads: 3,      // Parallel uploads
    // ...
});
```

## 🎯 Usage

### Multi-Select Files

**Select Multiple Files:**
- **Ctrl+Click** - Add/remove individual files to selection
- **Shift+Click** - Select range of files
- **Ctrl+A** - Select all files (when not in editor)
- **Click empty area** - Clear selection
- **ESC** - Clear selection

**Selected files are highlighted in blue**

### Batch Operations

With multiple files selected:
- **Right-click** → Shows batch operation menu
- **Delete key** → Delete all selected files
- All operations work on selected files:
  - ✅ Copy multiple files
  - ✅ Move multiple files
  - ✅ Delete multiple files
  - ✅ Download multiple files (staggered)
  - ✅ Compress multiple files to ZIP

### Upload Files
1. Click ⬆️ button in Explorer header
2. Drag files or click to browse
3. Watch progress bar
4. Files appear in current folder

**Upload Features:**
- Multiple file upload
- Large file support (chunked)
- Drag & drop
- Progress tracking

### Move/Copy Files
1. Select one or multiple files (Ctrl+Click)
2. Right-click selection
3. Select "Move" or "Copy"
4. Choose destination from folder tree
5. Click "Move" or "Copy"

### Download Files
1. Select one or multiple files
2. Right-click selection
3. Select "Download"
4. Files download (folders as ZIP)
5. Multiple files download with delay between each

### Compress Files
1. Select one or multiple files
2. Right-click selection
3. Select "Compress to ZIP"
4. Enter ZIP name (e.g., "archive.zip")
5. Click "Compress"

### Extract ZIP
1. Right-click .zip file
2. Select "Extract ZIP"
3. Folder created automatically

## 🎹 Keyboard Shortcuts

- **Ctrl+S** - Save current file
- **Ctrl+W** - Close current tab
- **Ctrl+A** - Select all files (in explorer)
- **Ctrl+Click** - Multi-select files
- **Shift+Click** - Select range
- **Delete** - Delete selected files
- **ESC** - Clear selection / Close modals

## 🔧 Visual Feedback

- **Selected files** = Blue highlight
- **Active file** (editing) = Gray highlight
- **Modified file** = Dot (●) in tab
- **Upload progress** = Progress bar
- **Status messages** = Bottom left corner

## 🔒 Security Features

- **Path sanitization** prevents directory traversal (../)
- **Operations restricted** to ROOT_DIR only
- **File validation** on uploads
- **Chunked uploads** prevent memory issues
- **Temporary files** cleaned automatically

## 📋 Context Menu Options

Right-click any file or folder:

- ✏️ Rename
- 📋 Copy
- 📦 Move
- ⬇️ Download
- 🗜️ Compress to ZIP
- 📂 Extract ZIP (ZIP files only)
- 🗑️ Delete
- + New File
- 📁 New Folder

## 🎨 Interface Features

- **VS Code-style** interface
- **Monaco Editor** with syntax highlighting
- **Tab management** with unsaved changes detection
- **Resizable sidebar**
- **File tree** with folders and subfolders
- **Breadcrumb navigation**
- **Status bar** with file info
- **Keyboard shortcuts** (Ctrl+S, Ctrl+W)

## 🐛 Troubleshooting

### Upload Not Working
- Check PHP file upload settings
- Verify temp directory permissions
- Check browser console for errors
- Try fallback upload (automatic)

### ZIP Operations Failing
- Ensure ZipArchive extension is enabled
- Check file permissions
- Verify enough disk space

### Large Files Timing Out
- Increase PHP max_execution_time
- Increase memory_limit
- Reduce chunkSize in script.js

## 📝 File Permissions

```bash
# Make directories writable
chmod 755 project/
chmod 755 temp_uploads/

# Make PHP files executable
chmod 644 *.php
chmod 644 *.js
chmod 644 *.css
```

## 🎉 All Features Summary

✅ Create, edit, save files
✅ Create folders and subfolders
✅ Rename files and folders
✅ Delete files and folders
✅ **Move files and folders**
✅ **Copy files and folders**
✅ **Upload single/multiple files**
✅ **Upload large files (chunked)**
✅ **Download files and folders**
✅ **Compress to ZIP**
✅ **Extract ZIP files**
✅ Multiple tabs
✅ Syntax highlighting
✅ Unsaved changes detection
✅ Context menu
✅ Drag & drop upload
✅ Progress tracking

## 🚀 Getting Started

1. Copy all 5 files to web server
2. Open http://yourserver/index.php
3. Start uploading, editing, and managing files!

## 📄 License

Free to use and modify for your projects.
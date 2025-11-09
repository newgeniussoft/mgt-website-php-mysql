# File Manager Documentation

## Overview
A complete file system manager that works directly with the server's file system in the `/public/uploads/` directory. This is like Windows Explorer or macOS Finder - it manages real folders and files on your server.

## Key Features

### ✅ Real File System Management
- **Direct Folder Creation** - Creates actual folders on the server
- **Nested Folders** - Create folders inside folders (unlimited depth)
- **File Upload** - Upload files to any folder
- **Rename** - Rename files and folders on the server
- **Move** - Move files and folders between directories
- **Delete** - Delete files and folders (with recursive deletion)
- **Download** - Download any file

### ✅ File Explorer Interface
- **Breadcrumb Navigation** - Navigate through folder hierarchy
- **Visual Grid Layout** - See folders and files with icons
- **Image Previews** - Thumbnail previews for images
- **File Type Icons** - Different icons for different file types
- **Statistics Dashboard** - Total files, folders, images, size

### ✅ Security Features
- **Path Sanitization** - Prevents directory traversal attacks
- **Safe Path Validation** - Ensures operations stay within uploads directory
- **Filename Sanitization** - Removes dangerous characters
- **Size Limits** - 50MB per file upload
- **CSRF Protection** - All forms protected

## How It Works

### File System Structure
```
public/
└── uploads/              # Base directory (managed by File Manager)
    ├── images/           # You can create this folder
    │   ├── logo.png
    │   └── products/     # Nested folder
    │       └── item1.jpg
    ├── documents/
    │   ├── invoice.pdf
    │   └── contracts/
    │       └── 2024/
    │           └── contract.pdf
    └── videos/
        └── promo.mp4
```

### Database vs File System

**Old Media System (Database):**
- Folders stored in `media_folders` table
- Files stored in `media` table
- Database and file system can get out of sync

**New File Manager (File System):**
- No database tables needed
- Works directly with server folders
- What you see is what's on the server
- Always in sync

## Usage Guide

### Accessing File Manager
Navigate to: `http://localhost:8000/cpanel/filemanager`

### Creating Folders

1. Click **"New Folder"** button
2. Enter folder name (letters, numbers, underscores, hyphens only)
3. Click **"Create Folder"**
4. Folder is created in current directory

**Example:**
- Current location: `/uploads/`
- Create folder: `images`
- Result: `/uploads/images/` folder created on server

**Nested Folders:**
- Navigate to `/uploads/images/`
- Create folder: `products`
- Result: `/uploads/images/products/` created

### Uploading Files

1. Navigate to desired folder
2. Click **"Upload Files"** button
3. Select one or multiple files
4. Click **"Upload Files"**
5. Files are uploaded to current folder

**Features:**
- Multiple file upload
- Up to 50MB per file
- Automatic duplicate handling (adds _1, _2, etc.)
- Progress indication

### Renaming Files/Folders

1. Click **"Rename"** button on any item
2. Enter new name in prompt
3. Press OK
4. Item is renamed on server

**Notes:**
- Special characters are replaced with underscores
- File extensions are preserved
- Cannot rename if name already exists

### Moving Files/Folders

1. Click **"Move"** button on any item
2. Select destination folder from tree
3. Or type destination path manually
4. Click **"Move Here"**
5. Item is moved to new location

**Path Examples:**
- Root: (leave empty)
- Single folder: `images`
- Nested: `images/products`
- Deep nesting: `documents/contracts/2024`

### Deleting Files/Folders

1. Click **"Delete"** button on any item
2. Confirm deletion
3. Item is permanently deleted from server

**Warning:**
- Deleting a folder deletes ALL contents recursively
- This action cannot be undone
- Files are permanently removed from server

### Downloading Files

1. Click **"Download"** button on any file
2. File is downloaded to your computer

**Note:** Folders cannot be downloaded (only files)

### Viewing Files

1. Click **"View"** button on any file
2. File opens in new browser tab
3. Works for images, PDFs, videos, etc.

## Navigation

### Breadcrumb Navigation
```
Root > images > products
```
- Click any breadcrumb to jump to that folder
- Click "Root" to go back to `/uploads/`

### Folder Navigation
- Click on any folder card to enter it
- Use breadcrumb to go back
- Current path shown in card header

## API Endpoints

### File Manager Routes
```php
GET  /cpanel/filemanager                    // Main interface
POST /cpanel/filemanager/create-folder      // Create folder
POST /cpanel/filemanager/upload             // Upload files
POST /cpanel/filemanager/rename             // Rename item
POST /cpanel/filemanager/delete             // Delete item
POST /cpanel/filemanager/move               // Move item
GET  /cpanel/filemanager/download?path=X    // Download file
GET  /cpanel/filemanager/folder-tree        // Get folder tree (AJAX)
```

### Parameters

**Create Folder:**
```php
folder_name: string (required)
current_path: string (current location)
```

**Upload:**
```php
files[]: file[] (required, multiple files)
current_path: string (destination)
```

**Rename:**
```php
old_name: string (required)
new_name: string (required)
current_path: string (current location)
```

**Delete:**
```php
item_name: string (required)
current_path: string (current location)
```

**Move:**
```php
item_name: string (required)
source_path: string (source location)
dest_path: string (destination location)
```

## Controller Methods

### FileManagerController

```php
__construct()                   // Initialize paths
index()                         // Display file manager
getDirectoryContents()          // Get files/folders in directory
getDirectorySize()              // Calculate folder size recursively
getDirectoryStats()             // Get statistics
getBreadcrumb()                 // Build breadcrumb trail
createFolder()                  // Create new folder
rename()                        // Rename file/folder
delete()                        // Delete file/folder
move()                          // Move file/folder
upload()                        // Handle file upload
download()                      // Download file
getFolderTree()                 // Get folder tree for move operation
buildFolderTree()               // Build tree recursively
deleteDirectory()               // Delete folder recursively
sanitizePath()                  // Sanitize path input
isPathSafe()                    // Validate path security
getFileType()                   // Determine file type
```

## Security Features

### Path Security
```php
// Prevents directory traversal
$path = str_replace(['../', '..\\', '\\'], '', $path);

// Validates path is within uploads directory
$realBase = realpath($this->baseUploadDir);
$realPath = realpath($path);
return strpos($realPath, $realBase) === 0;
```

### Filename Sanitization
```php
// Only allows safe characters
$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
```

### Upload Security
- File size validation (50MB limit)
- Duplicate filename handling
- Automatic extension preservation
- MIME type detection

## File Types Supported

### Images
- JPG, JPEG, PNG, GIF, WebP, SVG, BMP, ICO
- Shows thumbnail preview
- Displays dimensions

### Videos
- MP4, AVI, MOV, WMV, FLV, WebM, MKV
- Video icon
- Can be viewed in browser

### Audio
- MP3, WAV, OGG, AAC, FLAC, M4A
- Audio icon
- Can be played in browser

### Documents
- PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX
- TXT, CSV, RTF
- Document icon

### Archives
- ZIP, RAR, 7Z, TAR, GZ
- Archive icon

### Code Files
- PHP, JS, CSS, HTML, JSON, XML, SQL
- Code icon

## Statistics

The dashboard shows:
- **Total Files** - Count of all files
- **Total Folders** - Count of all folders
- **Images** - Count of image files
- **Total Size** - Combined size of all files

Statistics are calculated recursively for current folder and all subfolders.

## Best Practices

### Folder Organization
```
uploads/
├── images/
│   ├── products/
│   ├── banners/
│   └── logos/
├── documents/
│   ├── invoices/
│   ├── contracts/
│   └── reports/
├── videos/
│   ├── tutorials/
│   └── promotions/
└── downloads/
    ├── software/
    └── manuals/
```

### Naming Conventions
- Use lowercase for folder names
- Use hyphens or underscores instead of spaces
- Be descriptive: `product-images` not `imgs`
- Use dates for archives: `invoices-2024`

### File Management
- Keep folder structure shallow (3-4 levels max)
- Delete unused files regularly
- Organize by type or purpose
- Use consistent naming

## Troubleshooting

### Cannot Create Folder
**Problem:** "Failed to create folder"

**Solutions:**
- Check `/public/uploads/` directory exists
- Verify directory permissions (755)
- Ensure web server has write access
- Check disk space

### Cannot Upload Files
**Problem:** Files not uploading

**Solutions:**
- Check PHP `upload_max_filesize` setting
- Verify `post_max_size` is larger
- Ensure destination folder is writable
- Check file size (max 50MB)

### Cannot Rename/Move
**Problem:** Rename or move fails

**Solutions:**
- Check file/folder exists
- Verify no duplicate names
- Ensure proper permissions
- Check path is valid

### Cannot Delete Folder
**Problem:** "Failed to delete folder"

**Solutions:**
- Check folder permissions
- Verify no locked files inside
- Ensure web server has write access
- Check folder is not in use

## Differences from Database Media System

| Feature | Database Media | File Manager |
|---------|---------------|--------------|
| Storage | Database tables | File system only |
| Folders | Virtual (DB records) | Real directories |
| Sync | Can get out of sync | Always in sync |
| Speed | Slower (DB queries) | Faster (direct access) |
| Complexity | More complex | Simpler |
| Metadata | Rich metadata | Basic file info |
| Search | Advanced search | Basic navigation |
| Best For | Content management | File storage |

## When to Use

### Use File Manager When:
- You need simple file storage
- You want real folder structure
- You're managing downloads/uploads
- You need fast file access
- You want to see actual server structure

### Use Database Media When:
- You need rich metadata (titles, descriptions, tags)
- You want advanced search
- You need file relationships
- You want usage tracking
- You're building a media library

## Integration

### Accessing Files in Code
```php
// File Manager files are in /public/uploads/
$fileUrl = '/uploads/images/logo.png';

// Use in templates
<img src="{{ asset('/uploads/images/logo.png') }}" alt="Logo">

// Download link
<a href="{{ asset('/uploads/documents/file.pdf') }}" download>Download</a>
```

### Checking if File Exists
```php
$filePath = __DIR__ . '/public/uploads/images/logo.png';
if (file_exists($filePath)) {
    // File exists
}
```

### Getting File Info
```php
$filePath = __DIR__ . '/public/uploads/images/logo.png';
$size = filesize($filePath);
$modified = filemtime($filePath);
$type = mime_content_type($filePath);
```

## Advanced Usage

### Programmatic File Operations
```php
use App\Http\Controllers\FileManagerController;

$fm = new FileManagerController();

// Create folder programmatically
mkdir(__DIR__ . '/public/uploads/new-folder', 0755, true);

// Move file
rename(
    __DIR__ . '/public/uploads/old.jpg',
    __DIR__ . '/public/uploads/images/new.jpg'
);

// Delete file
unlink(__DIR__ . '/public/uploads/old-file.pdf');
```

### Bulk Operations
Currently, bulk operations are done one at a time through the UI. For bulk operations, you can:
1. Use FTP/SFTP client
2. Use server command line
3. Extend controller with bulk methods

## Future Enhancements

Potential features to add:
- Drag-and-drop upload
- Folder compression/download
- Bulk select and operations
- File preview modal
- Image editing
- File versioning
- Access permissions
- File sharing links

---

**Your file manager is now a complete file system explorer!** 📁✨

Access it at: `http://localhost:8000/cpanel/filemanager`

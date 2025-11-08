# Media Management System Documentation

## Overview
A complete file management system with folder organization, bulk operations, and advanced file handling capabilities.

## Features

### ✅ File Management
- **Upload** - Multiple file upload with drag-and-drop
- **Rename** - Rename files with automatic extension handling
- **Move** - Move files between folders
- **Delete** - Remove files from disk and database
- **Download** - Download files with tracking
- **Edit** - Update metadata (title, alt text, description)

### ✅ Folder Management
- **Create Folders** - Organize files into folders
- **Nested Folders** - Parent-child folder relationships
- **Rename Folders** - Update folder names with slug generation
- **Delete Folders** - Remove empty folders
- **Folder Navigation** - Sidebar with folder tree

### ✅ Bulk Operations
- **Bulk Delete** - Delete multiple files at once
- **Bulk Move** - Move multiple files to a folder
- **Checkbox Selection** - Select files for bulk actions
- **Action Bar** - Dynamic bulk action toolbar

### ✅ Search & Filter
- **Search** - Find files by name, title, alt text
- **Type Filter** - Filter by file type (images, videos, audio, documents)
- **Folder Filter** - View files in specific folders
- **Statistics** - Total files, size, and type breakdown

## Installation

### 1. Run Migration
```bash
# Navigate to installation script
http://localhost:8000/install_media.php
```

This will:
- Create `media` and `media_folders` tables
- Insert default folders
- Create upload directories
- Set proper permissions

### 2. Verify Installation
Check that these directories exist:
- `/public/uploads/media/`
- `/public/uploads/media/thumbnails/`

## Usage

### Accessing Media Library
Navigate to: `http://localhost:8000/cpanel/media`

### Uploading Files

1. Click **"Upload Files"** button
2. Select files or drag-and-drop
3. Choose destination folder (optional)
4. Click **"Upload Files"**

**Supported Formats:**
- **Images:** JPG, PNG, GIF, WebP, SVG, BMP
- **Videos:** MP4, AVI, MOV, WMV, FLV, WebM
- **Audio:** MP3, WAV, OGG, AAC, FLAC
- **Documents:** PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV

**Limits:**
- Maximum file size: 10MB per file
- Multiple files: Unlimited

### Creating Folders

1. Click **"New Folder"** button
2. Enter folder name
3. Select parent folder (optional)
4. Add description (optional)
5. Click **"Create Folder"**

### Renaming Files

1. Click the **rename icon** (i-cursor) on a file
2. Enter new filename
3. Extension is automatically added
4. File is renamed on disk and in database

### Moving Files

**Single File:**
1. Click the **move icon** (folder-open) on a file
2. Select destination folder
3. Click **"Move File"**

**Multiple Files:**
1. Check boxes next to files
2. Select **"Move to Folder"** from bulk actions
3. Choose destination folder
4. Click **"Apply"**

### Deleting Files

**Single File:**
1. Click the **delete icon** (trash) on a file
2. Confirm deletion
3. File is removed from disk and database

**Multiple Files:**
1. Check boxes next to files
2. Select **"Delete"** from bulk actions
3. Click **"Apply"**
4. Confirm deletion

### Editing File Metadata

1. Click the **edit icon** on a file
2. Update:
   - Title
   - Alt text (for images)
   - Description
   - Folder location
   - Public/Private status
3. Click **"Save Changes"**

### Managing Folders

Navigate to: `http://localhost:8000/cpanel/media/folders`

**Create Folder:**
- Click "New Folder"
- Fill in details
- Submit

**Edit Folder:**
- Click edit icon
- Update details
- Submit

**Delete Folder:**
- Click delete icon
- Folder must be empty
- Confirm deletion

## API Endpoints

### Media Routes
```php
GET  /cpanel/media                  // Media library
GET  /cpanel/media/upload           // Upload form
POST /cpanel/media/store            // Handle upload
GET  /cpanel/media/edit?id=X        // Edit form
POST /cpanel/media/update           // Update media
POST /cpanel/media/delete           // Delete media
GET  /cpanel/media/download?id=X    // Download file
POST /cpanel/media/rename           // Rename file
POST /cpanel/media/move             // Move file
POST /cpanel/media/bulk-action      // Bulk operations
```

### Folder Routes
```php
GET  /cpanel/media/folders          // Folder management
POST /cpanel/media/folder/create    // Create folder
POST /cpanel/media/folder/update    // Update folder
POST /cpanel/media/folder/delete    // Delete folder
```

## Controller Methods

### MediaController

#### File Operations
```php
index()         // Display media library
create()        // Show upload form
store()         // Handle file upload
edit()          // Show edit form
update()        // Update media metadata
destroy()       // Delete media
download()      // Download file
renameFile()    // Rename file
moveFile()      // Move file to folder
bulkAction()    // Handle bulk operations
```

#### Folder Operations
```php
folders()       // Show folder management
createFolder()  // Create new folder
updateFolder()  // Update folder
deleteFolder()  // Delete folder
```

## Model Methods

### Media Model

```php
// Retrieval
Media::all()                        // Get all media
Media::find($id)                    // Find by ID
Media::getByType($type)             // Get by type
Media::getByFolder($folderId)       // Get by folder
Media::search($query)               // Search media
Media::getRecent($limit)            // Get recent uploads

// Statistics
Media::getStats()                   // Get statistics

// Utilities
$media->getFormattedSize()          // Human-readable size
$media->isImage()                   // Check if image
$media->isVideo()                   // Check if video
$media->isAudio()                   // Check if audio
$media->isDocument()                // Check if document
$media->incrementDownloads()        // Track downloads
$media->deleteFile()                // Delete from disk
```

### MediaFolder Model

```php
// Retrieval
MediaFolder::all()                  // Get all folders
MediaFolder::find($id)              // Find by ID
MediaFolder::getRootFolders()       // Get root folders

// Relationships
$folder->getChildren()              // Get child folders
$folder->getParent()                // Get parent folder
$folder->getMedia()                 // Get folder media
$folder->getMediaCount()            // Count media files

// Utilities
MediaFolder::generateSlug($name)    // Generate unique slug
$folder->getBreadcrumb()            // Get navigation path
```

## Database Schema

### media Table
```sql
id                  INT PRIMARY KEY
filename            VARCHAR(255)
original_filename   VARCHAR(255)
path                VARCHAR(500)
url                 VARCHAR(500)
mime_type           VARCHAR(100)
extension           VARCHAR(20)
size                BIGINT
width               INT (for images)
height              INT (for images)
title               VARCHAR(255)
alt_text            VARCHAR(255)
description         TEXT
folder_id           INT (foreign key)
user_id             INT (foreign key)
type                ENUM (image, video, audio, document, other)
is_public           TINYINT(1)
downloads           INT
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### media_folders Table
```sql
id              INT PRIMARY KEY
name            VARCHAR(255)
slug            VARCHAR(255) UNIQUE
parent_id       INT (foreign key)
path            VARCHAR(500)
description     TEXT
order           INT
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

## UI Components

### Media Library
- **Folder Sidebar** - Navigate folders with file counts
- **Statistics Cards** - Total files, images, documents, size
- **Search & Filter** - Find files quickly
- **Bulk Actions Bar** - Appears when files selected
- **Media Grid** - Responsive card layout
- **File Actions** - Rename, move, edit, delete buttons

### Upload Interface
- **Drag-and-Drop Zone** - Visual file upload
- **File Preview** - See selected files before upload
- **Progress Bar** - Upload progress indicator
- **Folder Selection** - Choose destination
- **File Type Info** - Supported formats

### Edit Interface
- **File Preview** - Image/video/audio preview
- **Metadata Form** - Title, alt text, description
- **Folder Selection** - Move to different folder
- **File Information** - Size, type, dimensions, downloads
- **Quick Actions** - Download, copy URL, delete

### Folder Management
- **Folder Table** - List all folders
- **Parent-Child Display** - Show folder hierarchy
- **File Count** - Number of files per folder
- **CRUD Operations** - Create, edit, delete folders

## Security Features

### File Upload Security
- **File Type Validation** - MIME type checking
- **Size Limits** - Maximum 10MB per file
- **Extension Whitelist** - Only allowed extensions
- **Unique Filenames** - Prevent overwrites
- **Directory Permissions** - Proper file permissions

### Access Control
- **Authentication Required** - Admin login required
- **CSRF Protection** - All forms protected
- **Session Management** - Secure sessions
- **Input Validation** - Sanitize all inputs

### File Operations Security
- **Path Validation** - Prevent directory traversal
- **Existence Checks** - Verify files exist
- **Permission Checks** - Verify write permissions
- **Atomic Operations** - Database and file sync

## Troubleshooting

### Files Not Uploading
- Check PHP `upload_max_filesize` setting
- Verify `post_max_size` is larger than `upload_max_filesize`
- Ensure `/public/uploads/media/` is writable (755)
- Check disk space

### Rename/Move Not Working
- Verify file exists on disk
- Check directory permissions
- Ensure no duplicate filenames
- Check database connection

### Folders Not Showing
- Run installation script
- Check `media_folders` table exists
- Verify default folders inserted
- Clear browser cache

### Bulk Actions Failing
- Check JavaScript console for errors
- Verify CSRF token is present
- Ensure files are selected
- Check server error logs

## Best Practices

### File Organization
- Use descriptive folder names
- Group related files together
- Keep folder structure shallow (2-3 levels max)
- Use consistent naming conventions

### File Naming
- Use descriptive filenames
- Avoid special characters
- Use hyphens instead of spaces
- Include version numbers if needed

### Metadata
- Always add alt text for images (SEO & accessibility)
- Write descriptive titles
- Add relevant descriptions
- Tag files appropriately

### Performance
- Optimize images before upload
- Use appropriate file formats
- Delete unused files regularly
- Monitor disk space usage

## Advanced Features

### Bulk Operations
Select multiple files and perform actions:
- Move to folder
- Delete files
- Change privacy settings (future)
- Add tags (future)

### File Statistics
Track file usage:
- Download counts
- View counts (future)
- Last accessed (future)
- Popular files (future)

### Integration
Use media in other parts of the CMS:
- Page featured images
- Content images
- Gallery sections
- Download links

---

**Your media management system is now fully equipped with professional file handling capabilities!** 📁✨

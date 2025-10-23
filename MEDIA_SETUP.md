# Media Management System Documentation

## Overview

The Media Management System provides comprehensive file upload, organization, and management capabilities for your CMS. It includes features for uploading various file types, organizing them in folders, and integrating them seamlessly with your pages and content.

## Features

### 🚀 Core Features
- **Multi-file Upload**: Drag-and-drop interface with support for multiple files
- **File Type Support**: Images, videos, audio, documents, and other file types
- **Automatic Thumbnails**: Generated for images with GD extension
- **Folder Organization**: Hierarchical folder structure for better organization
- **Search & Filter**: Find files by name, type, or folder
- **File Validation**: Security checks and file type restrictions
- **Download Tracking**: Monitor file download statistics
- **Privacy Controls**: Public/private file access settings

### 🎨 User Interface
- **Modern Design**: Clean, responsive interface built with Tailwind CSS
- **Grid View**: Visual file browser with thumbnails and file information
- **Media Picker**: Modal component for selecting files in other parts of the system
- **Breadcrumb Navigation**: Easy navigation through folder hierarchy
- **Bulk Operations**: Select and manage multiple files at once

### 🔒 Security Features
- **File Type Validation**: Only allowed file types can be uploaded
- **MIME Type Checking**: Server-side validation of actual file types
- **Upload Directory Protection**: .htaccess rules prevent script execution
- **CSRF Protection**: All forms protected against cross-site request forgery
- **User Authentication**: All operations require admin authentication

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- GD extension (for image processing)
- FileInfo extension (for MIME type detection)
- MBString extension (for file name handling)

### Installation Steps

1. **Run the Installation Script**
   ```
   http://yoursite.com/install_media.php
   ```

2. **Manual Installation** (if needed)
   ```bash
   # Import database schema
   mysql -u username -p database_name < migrate_media.sql
   
   # Create upload directories
   mkdir -p uploads/{images,videos,audios,documents,others,thumbnails}
   chmod 755 uploads/
   chmod 755 uploads/*
   ```

3. **Verify Installation**
   - Check that all directories are created and writable
   - Verify database tables are created
   - Test file upload functionality

## Database Schema

### Tables Created

#### `media`
Stores information about uploaded files:
- `id` - Primary key
- `filename` - Generated unique filename
- `original_name` - Original uploaded filename
- `file_path` - Relative path to the file
- `file_size` - File size in bytes
- `mime_type` - MIME type of the file
- `file_type` - Category (image, video, audio, document, other)
- `alt_text` - Alternative text for images (accessibility)
- `title` - Display title for the file
- `description` - Optional description
- `width`, `height` - Image dimensions (if applicable)
- `thumbnail_path` - Path to generated thumbnail
- `uploaded_by` - User who uploaded the file
- `folder_id` - Folder organization
- `is_public` - Privacy setting
- `download_count` - Number of downloads
- `created_at`, `updated_at` - Timestamps

#### `media_folders`
Organizes files into folders:
- `id` - Primary key
- `name` - Folder name
- `slug` - URL-friendly identifier
- `parent_id` - For nested folders
- `description` - Optional description
- `created_by` - User who created the folder
- `created_at`, `updated_at` - Timestamps

## File Structure

```
app/
├── controllers/admin/
│   └── MediaController.php          # Main media controller
├── models/
│   ├── Media.php                    # Media model
│   └── MediaFolder.php              # Folder model
├── views/admin/media/
│   ├── index.blade.php              # Media library main view
│   ├── upload.blade.php             # File upload interface
│   ├── edit.blade.php               # Edit media details
│   ├── folders.blade.php            # Folder management
│   └── picker.blade.php             # Media picker modal
├── core/
│   ├── Router.php                   # Updated with media routes
│   └── Helper.php                   # Added formatFileSize function
uploads/
├── images/                          # Image uploads
├── videos/                          # Video uploads
├── audios/                          # Audio uploads
├── documents/                       # Document uploads
├── others/                          # Other file types
├── thumbnails/                      # Generated thumbnails
└── .htaccess                        # Security configuration
```

## Usage Guide

### Accessing the Media Library

1. **Admin Panel Navigation**
   - Log into the admin panel at `/admin`
   - Click "Media" in the sidebar navigation
   - You'll see the media library dashboard

### Uploading Files

1. **Upload Interface**
   - Click "Upload Files" button
   - Drag and drop files or click "Choose Files"
   - Select destination folder (optional)
   - Files are automatically validated and processed

2. **Supported File Types**
   - **Images**: JPG, PNG, GIF, WebP, SVG, BMP
   - **Videos**: MP4, AVI, MOV, WMV, FLV, WebM
   - **Audio**: MP3, WAV, OGG, AAC, FLAC
   - **Documents**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV

3. **Upload Limits**
   - Maximum file size: 10MB (configurable)
   - Multiple files can be uploaded simultaneously
   - Automatic thumbnail generation for images

### Managing Files

1. **File Information**
   - Click "Edit" on any file to modify its details
   - Add titles, descriptions, and alt text
   - Move files between folders
   - Set privacy controls

2. **File Operations**
   - **View**: Preview files in browser
   - **Edit**: Modify file information
   - **Download**: Download original file
   - **Delete**: Remove file permanently

### Folder Management

1. **Creating Folders**
   - Click "Manage Folders" in the media library
   - Click "New Folder" to create a folder
   - Folders can be nested for better organization

2. **Folder Operations**
   - **View Files**: See all files in a folder
   - **Edit**: Modify folder name and description
   - **Delete**: Remove empty folders

### Using the Media Picker

The media picker is a reusable component for selecting files in other parts of the system:

```javascript
// Open media picker
openMediaPicker(function(selectedMedia) {
    console.log('Selected file:', selectedMedia);
    // Use the selected media file
}, {
    type: 'image' // Optional: filter by file type
});
```

## API Endpoints

### Media Routes
- `GET /admin/media` - Media library dashboard
- `GET /admin/media/upload` - Upload form
- `POST /admin/media/upload` - Handle file upload
- `GET /admin/media/edit?id=X` - Edit media form
- `POST /admin/media/edit?id=X` - Update media
- `POST /admin/media/delete` - Delete media
- `GET /admin/media/download?id=X` - Download file
- `GET /admin/media/picker` - AJAX media picker data

### Folder Routes
- `GET /admin/media/folders` - Folder management
- `POST /admin/media/folders` - Create/edit/delete folders

## Configuration

### File Upload Settings

Edit `MediaController.php` to modify upload settings:

```php
// Maximum file size (in bytes)
private $maxFileSize = 10485760; // 10MB

// Allowed file extensions
private $allowedExtensions = [
    'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'],
    'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
    'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
    'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv']
];
```

### Thumbnail Settings

Modify thumbnail generation in `Media.php`:

```php
// Thumbnail dimensions
$thumbnailWidth = 300;
$thumbnailHeight = 300;

// JPEG quality
$jpegQuality = 85;
```

### Directory Structure

Upload directories are automatically created:
- `/uploads/images/` - Image files
- `/uploads/videos/` - Video files
- `/uploads/audios/` - Audio files
- `/uploads/documents/` - Document files
- `/uploads/others/` - Other file types
- `/uploads/thumbnails/` - Generated thumbnails

## Security Considerations

### File Validation
- MIME type checking prevents malicious file uploads
- File extension validation ensures only allowed types
- File size limits prevent abuse

### Directory Protection
The `.htaccess` file in uploads directory:
- Prevents directory listing
- Blocks execution of PHP and other scripts
- Sets proper MIME types

### Access Control
- All media operations require admin authentication
- CSRF tokens protect against cross-site attacks
- File privacy settings control public access

## Troubleshooting

### Common Issues

1. **Upload Fails**
   - Check file size limits in PHP configuration
   - Verify upload directory permissions (755)
   - Ensure required PHP extensions are loaded

2. **Thumbnails Not Generated**
   - Install/enable GD extension
   - Check write permissions on thumbnails directory
   - Verify image file is not corrupted

3. **Files Not Displaying**
   - Check file paths in database
   - Verify web server can serve static files
   - Check .htaccess configuration

### PHP Configuration

Recommended PHP settings:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
```

### Directory Permissions

Ensure proper permissions:
```bash
chmod 755 uploads/
chmod 755 uploads/*/
chown www-data:www-data uploads/ -R  # On Linux
```

## Integration Examples

### Using Media in Pages

```php
// In page templates
if ($media_id) {
    $media = new Media();
    $file = $media->getById($media_id);
    echo "<img src='{$file['file_path']}' alt='{$file['alt_text']}'>";
}
```

### Media Picker Integration

```html
<!-- Include media picker -->
@include('admin.media.picker')

<!-- Button to open picker -->
<button onclick="openMediaPicker(handleMediaSelection, {type: 'image'})">
    Select Image
</button>

<script>
function handleMediaSelection(media) {
    // Handle selected media
    document.getElementById('selected-image').src = media.file_path;
    document.getElementById('media-id').value = media.id;
}
</script>
```

## Maintenance

### Regular Tasks
- Monitor disk space usage
- Clean up unused files periodically
- Backup media files regularly
- Update file statistics

### Database Maintenance
```sql
-- Clean up orphaned thumbnails
DELETE FROM media WHERE thumbnail_path IS NOT NULL 
AND thumbnail_path NOT IN (SELECT file_path FROM media);

-- Update file statistics
UPDATE media_folders SET file_count = (
    SELECT COUNT(*) FROM media WHERE folder_id = media_folders.id
);
```

## Support

For issues or questions:
1. Check the troubleshooting section
2. Verify all requirements are met
3. Check server error logs
4. Ensure proper file permissions

The media management system provides a robust foundation for handling all your file upload and management needs within the CMS.

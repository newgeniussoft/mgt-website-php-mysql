<?php

class UploadImage
{
    private $allowedTypes;
    private $maxSize;
    private $uploadDir;
    private $errors = [];
    public $filename = "";

    /**
     * Constructor
     * @param string $uploadDir Directory to upload images
     * @param array $allowedTypes Allowed MIME types (default: jpg, jpeg, png, gif)
     * @param int $maxSize Maximum file size in bytes (default: 5MB)
     */
    public function __construct($uploadDir, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], $maxSize = 15242880)
    {
        $this->uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Upload the image file
     * @param array $file $_FILES['your_input_name']
     * @return string|false Uploaded filename or false on failure
     */
    public function upload($file, $withThumbnail = false, $thumbnailWidth = 550)
    {
        $this->errors = [];
        if (!isset($file['error']) || is_array($file['error'])) {
            $this->errors[] = 'Invalid file parameters.';
            return false;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->codeToMessage($file['error']);
            return false;
        }
        if ($file['size'] > $this->maxSize) {
            $this->errors[] = 'File size exceeds the maximum allowed.';
            return false;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mimeType, $this->allowedTypes)) {
            $this->errors[] = 'Invalid file type.';
            return false;
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        // Generate base filename
        $baseName = uniqid('img_', true);
        $filename = $baseName . '.webp';
        $destination = $this->uploadDir . $filename;
        
        // Load image based on type
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $this->errors[] = 'Failed to read image.';
            return false;
        }
        
        list($width, $height, $type) = $imageInfo;
        
        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImg = imagecreatefromjpeg($file['tmp_name']);
                break;
            case IMAGETYPE_PNG:
                $srcImg = imagecreatefrompng($file['tmp_name']);
                break;
            case IMAGETYPE_GIF:
                $srcImg = imagecreatefromgif($file['tmp_name']);
                break;
            case IMAGETYPE_WEBP:
                $srcImg = imagecreatefromwebp($file['tmp_name']);
                break;
            default:
                $this->errors[] = 'Unsupported image type.';
                return false;
        }
        
        // Create webp image
        if (!imagewebp($srcImg, $destination, 80)) {
            $this->errors[] = 'Failed to convert image to webp.';
            imagedestroy($srcImg);
            return false;
        }
        
        imagedestroy($srcImg);
        $this->filename = $filename;

        $result = ['original' => $filename];

        // Handle thumbnail
        if ($withThumbnail) {
            $thumbFilename = $baseName . '_thumbnail.webp';
            $thumbPath = $this->uploadDir . $thumbFilename;
            if ($this->createThumbnail($destination, $thumbPath, $thumbnailWidth)) {
                $result['thumbnail'] = $thumbFilename;
            } else {
                $this->errors[] = 'Thumbnail creation failed.';
            }
        }

        return empty($this->errors) ? $result : false;
    }

    /**
     * Create a thumbnail of an image and save as .webp
     * @param string $srcPath
     * @param string $destPath
     * @param int $thumbWidth
     * @return bool
     */
    private function createThumbnail($srcPath, $destPath, $thumbWidth)
    {
        $imageInfo = getimagesize($srcPath);
        if ($imageInfo === false) {
            return false;
        }
        list($width, $height, $type) = $imageInfo;
        $ratio = $width / $height;
        $thumbHeight = intval($thumbWidth / $ratio);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImg = imagecreatefromjpeg($srcPath);
                break;
            case IMAGETYPE_PNG:
                $srcImg = imagecreatefrompng($srcPath);
                break;
            case IMAGETYPE_GIF:
                $srcImg = imagecreatefromgif($srcPath);
                break;
            case IMAGETYPE_WEBP:
                $srcImg = imagecreatefromwebp($srcPath);
                break;
            default:
                return false;
        }
        $thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        $result = imagewebp($thumbImg, $destPath);
        imagedestroy($srcImg);
        imagedestroy($thumbImg);
        return $result;
    }

    public function getOriginalFilename() {
        return $this->filename;
    }
    
    public function getThumbnailFilename() {
        $baseName = pathinfo($this->filename, PATHINFO_FILENAME);
        return $baseName . '_thumbnail.webp';
    }

    /**
     * Get errors from last upload attempt
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File is too large.';
            case UPLOAD_ERR_PARTIAL:
                return 'File was only partially uploaded.';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload.';
            default:
                return 'Unknown upload error.';
        }
    }
}

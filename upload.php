
<?php
// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Validate upload
    if ($file['error'] === 0) {
        $tmpPath = $file['tmp_name'];
        $fileType = mime_content_type($tmpPath);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($fileType, $allowedTypes)) {
            // Load image with GD
            switch ($fileType) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($tmpPath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($tmpPath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($tmpPath);
                    break;
                default:
                    die("Unsupported image type");
            }

            // Set destination path
            $outputPath = 'uploads/' . uniqid('img_') . '.webp';

            // Save as WEBP
            if (imagewebp($image, $outputPath, 80)) {
                echo "Image uploaded and converted to <a href='$outputPath' target='_blank'>WebP</a>!";
            } else {
                echo "Failed to convert image.";
            }

            // Free memory
            imagedestroy($image);
        } else {
            echo "Unsupported file type.";
        }
    } else {
        echo "Upload error.";
    }
}
?>

<!-- Simple HTML form -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Upload and Convert</button>
</form>

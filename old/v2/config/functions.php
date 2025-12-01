<?php
function loadConst($name)
{
    $file = __DIR__ . "/../constants/$name.php";
    if (file_exists($file)) {
        return include $file;
    }
    return null;
}

function deleteFile($file) {
    if (file_exists($file)) {
        if (unlink($file)) {
            echo "File '$file' has been deleted.";
        } else {
            echo "Error deleting the file '$file'.";
        }
    } else {
        echo "The file '$file' does not exist.";
    }
}

function mainUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . $host;
}

function uploadImage($file, $path) {

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

            // Resize image if width and height are provided
            $newWidth = isset($_POST['width']) && intval($_POST['width']) > 0 ? intval($_POST['width']) : imagesx($image);
            $newHeight = isset($_POST['height']) && intval($_POST['height']) > 0 ? intval($_POST['height']) : imagesy($image);

            if ($newWidth !== imagesx($image) || $newHeight !== imagesy($image)) {
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                // Preserve transparency for PNG and GIF
                if ($fileType === 'image/png' || $fileType === 'image/gif') {
                    imagecolortransparent($resized, imagecolorallocatealpha($resized, 0, 0, 0, 127));
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                }
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
                imagedestroy($image);
                $image = $resized;
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
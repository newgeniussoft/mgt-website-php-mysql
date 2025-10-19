<?php

/**
 * Controller class
 *
 * This class is the base class for all controllers in the application
 * It provides basic functionality for rendering views and handling file uploads
 *
 * @author Your Name <georgino197@gmail.com>
 */

// Import required classes
require_once __DIR__ . '/UploadImage.php';
require_once __DIR__ . '/BladeEngine.php';

class Controller
{
    /**
     * The language of the current request
     *
     * @var string|null
     */
    public $language = null;

    /**
     * Constructor for the Controller class
     *
     * @param string|null $lang The language of the current request
     */
    public function __construct( ?string $lang = 'en')
    {
        $this->language = $lang;
    }

    /**
     * Render a view
     *
     * @param string $view The name of the view to render
     * @param array $variables The variables to pass to the view
     */
    public function render(string $view, array $variables): void
    {
        require_once __DIR__ . '/Translator.php';

        // Initialize translator
        Translator::init(__DIR__ . '/../languages', $this->language); // Default to English

        // Create a new instance of BladeEngine
        $blade = new BladeEngine(__DIR__ . '/../views', __DIR__ . '/../../storage/cache');

        // Render the view and echo the result
        echo $blade->render($view, $variables);

        // Clear the cache
        $blade->clearCache();
    }

    /**
     * Upload a file to the specified directory
     *
     * @param string $dir The directory to upload the file to
     * @param mixed $file The file to upload
     * @return object An object containing information about the upload
     */
    public function upload(string $dir, $file): object
    {
        // Create a new instance of UploadImage
        $uploadImage = new UploadImage($dir);

        // Upload the file and get the errors
        $uploadImage->upload($file, true);
        $errors = $uploadImage->getErrors();

        // Return an object with the result of the upload
        return (object) [
            "success" => empty($errors),
            "errors" => $errors,
            "filename" => $uploadImage->filename,
        ];
    }
}
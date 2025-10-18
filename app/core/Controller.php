<?php
   require_once 'TemplateEngine.php';
   require_once 'UploadImage.php';
    
    class Controller
    {
        public $language = null;
        public $model = null;
        public $engine;
    
        public function __construct($model, $lang = 'en') {
            $this->language = $lang;
            $this->model =$model;
            $this->engine = new TemplateEngine();
        }

        public function view($view, $variables) {
            $this->variables = $variables;
            $this->engine->directive('include', function ($expression) {
                return $this->engine->render("app/views/".str_replace('.','/',$expression).".blade.php", $this->variables);
            });
            return $this->engine->render(__DIR__ .'/../views/'. str_replace('.','/',$view).'.blade.php', $variables);
        }
        

    public function upload($dir, $file): object
    {
        $uploadImage = new UploadImage($dir);
        $uploadImage->upload($file, true);
        $errors = $uploadImage->getErrors();
        return (object) [
            "success" => empty($errors),
            "errors" => $errors,
            "filename" => $uploadImage->filename,
        ];
    }
        
    }   
    ?>
<?php
   require_once __DIR__ . '/../../config/TemplateEngine.php';
   require_once __DIR__ . '/../../models/Tour.php';

    
    class Controller
    {
        public $language = null;
        public $engine;
        public $tourModel;
        public $tours;
        public $meta;

        public function __construct($lang = null)
        {
            $this->language = $lang;
            $this->engine = new TemplateEngine();
            $this->tourModel = new Tour();
            $this->tours = $this->tourModel->fetchAll();
        }

        public function setLang($lang) {
            // Update language if provided
            if ($lang !== null) {
                $this->language = $lang;
            }
        }
    
        public function view($view, $variables) {
            $this->variables = $variables;
            $this->engine->directive('include', function ($expression) {
                return $this->engine->render("app/views/".str_replace('.','/',$expression).".blade.php", $this->variables);
            });
            return $this->engine->render(__DIR__ .'/../../views/'. str_replace('.','/',$view).'.blade.php', $variables);
        }
    }

?>
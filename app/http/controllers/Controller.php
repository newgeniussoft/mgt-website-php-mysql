<?php
   require_once __DIR__ . '/../../config/TemplateEngine.php';
   require_once __DIR__ . '/../../models/Tour.php';
   require_once __DIR__ . '/../../models/Info.php';
   require_once __DIR__ . '/../../models/SocialMedia.php';

    
    class Controller
    {
        public $language = null;
        public $engine;
        public $tourModel;
        public $infoModel;
        public $socialMediaModel;
        public $tours;
        public $info;
        public $meta;
        public $socialMedia;

        public function __construct($lang = null)
        {
            $this->language = $lang;
            $this->engine = new TemplateEngine();
            $this->tourModel = new Tour();
            $this->infoModel = new Info();
            $this->socialMediaModel = new SocialMedia();

            $this->info = $this->infoModel->getInfo();
            $this->tours = $this->tourModel->fetchAll();
            $this->socialMedia = $this->socialMediaModel->all();
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

        // Add this so all controllers can use middleware
        public function applyMiddleware($callback, $middlewares = []) {
            require_once __DIR__ . '/../middleware/MiddlewareHandler.php';
            $handler = new MiddlewareHandler();
            foreach ($middlewares as $middleware) {
                $handler->add($middleware);
            }
            return $handler->run($callback);
        }
    }

?>
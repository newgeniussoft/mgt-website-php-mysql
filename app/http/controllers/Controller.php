<?php
   require_once __DIR__ . '/../../config/TemplateEngine.php';

    
    class Controller
    {
        public $engine;

        public function __construct($lang = null)
        {
            $this->engine = new TemplateEngine();
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
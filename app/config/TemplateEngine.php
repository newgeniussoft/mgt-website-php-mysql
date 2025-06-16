<?php 
class TemplateEngine {
    protected $directives = [];

    public function __construct() {
        // Register built-in directives
        $this->registerDefaultDirectives();
    }

    protected function registerDefaultDirectives() {
        // Conditional directives
        $this->directive('if', function ($expression) {
            return "<?php if ($expression): ?>";
        });

        $this->directive('elseif', function ($expression) {
            return "<?php elseif ($expression): ?>";
        });

        $this->directive('else', function () {
            return "<?php else: ?>";
        });

        $this->directive('endif', function () {
            return "<?php endif; ?>";
        });

        // Loop directives
       $this->directive('foreach', function ($expression) {
            return "<?php foreach ($expression): ?>";
        });

        $this->directive('endforeach', function () {
            return "<?php endforeach; ?>";
        });

        $this->directive('for', function ($expression) {
            return "<?php for ($expression): ?>";
        });

        $this->directive('endfor', function () {
            return "<?php endfor; ?>";
        });
        
        $this->directive('import', function ($expression) {
            return "<?php include $expression; ?>";
        });

        $this->directive('navlink', function ($path) {
            $params = explode(",", $path);
            $template = '<li class="menu-item x-menu-item x-sub-menu-standard">';
            $template .= '<a href="{{route(\''.$params[1].'\')}}" class="x-menu-a-text">';
            $template .= '<span class="x-menu-text">'.$params[0].'</span></a></li>';
            return $template;
        });
        
    }

    public function directive($name, callable $handler) {
        $this->directives[$name] = $handler;
    }

    public function render($template, $variables = []) {
        // Load the template content
        $content = file_get_contents($template);

        // Replace custom directives with PHP code
        foreach ($this->directives as $name => $handler) {
            $pattern = '/@' . $name . '\((.*?)\)/';
            $content = preg_replace_callback($pattern, function ($matches) use ($handler) {
                return call_user_func($handler, $matches[1]);
            }, $content);

            // Handle directives without parameters
            $pattern = '/@' . $name . '\b/';
            $content = preg_replace_callback($pattern, function ($matches) use ($handler) {
                return call_user_func($handler);
            }, $content);
        }
        // Replace {{ }} with PHP echo statements
        $content = preg_replace('/\{\{(.+?)\}\}/', '<?php echo $1; ?>', $content);
        // Extract variables into the current symbol table
        extract($variables);

        // Evaluate the PHP code
        ob_start();
        eval('?>' . $content);
        return ob_get_clean();
    }
}

?>
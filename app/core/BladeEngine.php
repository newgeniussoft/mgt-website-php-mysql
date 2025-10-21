<?php

class BladeEngine
{
    protected $viewPath;
    protected $cachePath;
    protected $extensions = [];
    protected $host;
    
    public function __construct($viewPath, $cachePath)
    {
        $this->viewPath = rtrim($viewPath, '/');
        $this->cachePath = rtrim($cachePath, '/');
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $this->host = "{$protocol}://{$_SERVER['HTTP_HOST']}";
        
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
        
        // Auto-load helper functions if not already loaded
        if (!function_exists('url')) {
            $this->loadHelpers();
        }
    }
    
    /**
     * Load helper functions
     */
    protected function loadHelpers()
    {
        require_once __DIR__ . '/Helper.php';
    }

    
    /**
     * Render a blade template
     */
    public function render($view, $data = [])
    {
        $viewFile = $this->viewPath . '/' . str_replace('.', '/', $view) . '.blade.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View [$view] not found at: $viewFile");
        }
        
        $cacheFile = $this->getCacheFile($viewFile);
        
        if (!file_exists($cacheFile) || filemtime($viewFile) > filemtime($cacheFile)) {
            $compiled = $this->compile(file_get_contents($viewFile));
            file_put_contents($cacheFile, $compiled);
        }
        
        return $this->evaluate($cacheFile, $data);
    }
    
    /**
     * Compile blade syntax to PHP
     */
    protected function compile($content)
    {
        // Apply all compilation methods in order
        $content = $this->compileExtends($content);
        $content = $this->compileSections($content);
        $content = $this->compileYields($content);
        $content = $this->compileIncludes($content);
        $content = $this->compileComponents($content);
        $content = $this->compileStacks($content);
        $content = $this->compileComments($content);
        $content = $this->compilePhp($content);
        $content = $this->compileAuth($content);
        $content = $this->compileGuest($content);
        $content = $this->compileProduction($content);
        $content = $this->compileEnv($content);
        $content = $this->compileConditionals($content);
        $content = $this->compileLoops($content);
        $content = $this->compileSwitchStatements($content);
        $content = $this->compileJson($content);
        $content = $this->compileCsrf($content);
        $content = $this->compileMethod($content);
        $content = $this->compileTranslations($content);
        $content = $this->compileHelpers($content);
        $content = $this->compileRawEchos($content);
        $content = $this->compileEchos($content);
        
        return $content;
    }
    
    /**
     * Compile {{ $var }} to escaped echo
     */
    protected function compileEchos($content)
    {
        return preg_replace('/\{\{\s*(.+?)\s*\}\}/s', '<?php echo htmlspecialchars($1 ?? \'\', ENT_QUOTES, \'UTF-8\'); ?>', $content);
    }
    
    /**
     * Compile {!! $var !!} to raw echo
     */
    protected function compileRawEchos($content)
    {
        return preg_replace('/\{!!\s*(.+?)\s*!!\}/s', '<?php echo $1; ?>', $content);
    }
    
    /**
     * Compile {{-- comments --}}
     */
    protected function compileComments($content)
    {
        return preg_replace('/\{\{--.+?--\}\}/s', '', $content);
    }
    
    /**
     * Compile @php and @endphp
     */
    protected function compilePhp($content)
    {
        $content = preg_replace('/\@php\s*\((.+?)\)/s', '<?php $1; ?>', $content);
        $content = preg_replace('/\@php/', '<?php', $content);
        $content = preg_replace('/\@endphp/', '?>', $content);
        return $content;
    }
    
    /**
     * Compile @if, @elseif, @else, @endif
     */
    protected function compileConditionals($content)
    {
        // Use callback to properly handle nested parentheses
        $content = preg_replace_callback('/\@if\s*\((.+)\)/', [$this, 'compileConditionalCallback'], $content);
        $content = preg_replace_callback('/\@elseif\s*\((.+)\)/', [$this, 'compileElseifCallback'], $content);
        $content = preg_replace('/\@else/', '<?php else: ?>', $content);
        $content = preg_replace('/\@endif/', '<?php endif; ?>', $content);
        
        // @unless, @endunless
        $content = preg_replace_callback('/\@unless\s*\((.+)\)/', [$this, 'compileUnlessCallback'], $content);
        $content = preg_replace('/\@endunless/', '<?php endif; ?>', $content);
        
        // @isset, @endisset
        $content = preg_replace_callback('/\@isset\s*\((.+)\)/', [$this, 'compileIssetCallback'], $content);
        $content = preg_replace('/\@endisset/', '<?php endif; ?>', $content);
        
        // @empty, @endempty
        $content = preg_replace_callback('/\@empty\s*\((.+)\)/', [$this, 'compileEmptyCallback'], $content);
        $content = preg_replace('/\@endempty/', '<?php endif; ?>', $content);
        
        return $content;
    }
    
    /**
     * Extract balanced parentheses content
     */
    protected function extractBalancedParentheses($content, $startPos = 0)
    {
        $level = 0;
        $start = strpos($content, '(', $startPos);
        if ($start === false) return false;
        
        $length = strlen($content);
        for ($i = $start; $i < $length; $i++) {
            if ($content[$i] === '(') {
                $level++;
            } elseif ($content[$i] === ')') {
                $level--;
                if ($level === 0) {
                    return substr($content, $start + 1, $i - $start - 1);
                }
            }
        }
        return false;
    }
    
    /**
     * Compile @if callback
     */
    protected function compileConditionalCallback($matches)
    {
        $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
        return '<?php if(' . $condition . '): ?>';
    }
    
    /**
     * Compile @elseif callback
     */
    protected function compileElseifCallback($matches)
    {
        $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
        return '<?php elseif(' . $condition . '): ?>';
    }
    
    /**
     * Compile @unless callback
     */
    protected function compileUnlessCallback($matches)
    {
        $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
        return '<?php if(!(' . $condition . ')): ?>';
    }
    
    /**
     * Compile @isset callback
     */
    protected function compileIssetCallback($matches)
    {
        $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
        return '<?php if(isset(' . $condition . ')): ?>';
    }
    
    /**
     * Compile @empty callback
     */
    protected function compileEmptyCallback($matches)
    {
        $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
        return '<?php if(empty(' . $condition . ')): ?>';
    }
    
    /**
     * Compile @auth, @guest
     */
    protected function compileAuth($content)
    {
        $content = preg_replace('/\@auth(\s*\((.+?)\))?/', '<?php if(isset($__auth) && $__auth): ?>', $content);
        $content = preg_replace('/\@endauth/', '<?php endif; ?>', $content);
        return $content;
    }
    
    protected function compileGuest($content)
    {
        $content = preg_replace('/\@guest/', '<?php if(!isset($__auth) || !$__auth): ?>', $content);
        $content = preg_replace('/\@endguest/', '<?php endif; ?>', $content);
        return $content;
    }
    
    /**
     * Compile @production, @env
     */
    protected function compileProduction($content)
    {
        $content = preg_replace('/\@production/', '<?php if(getenv(\'APP_ENV\') === \'production\'): ?>', $content);
        $content = preg_replace('/\@endproduction/', '<?php endif; ?>', $content);
        return $content;
    }
    
    protected function compileEnv($content)
    {
        $content = preg_replace('/\@env\s*\((.+?)\)/', '<?php if(getenv(\'APP_ENV\') === $1): ?>', $content);
        $content = preg_replace('/\@endenv/', '<?php endif; ?>', $content);
        return $content;
    }
    
    /**
     * Compile loops: @foreach, @for, @while, @forelse
     */
    protected function compileLoops($content)
    {
        // @foreach - use callback for balanced parentheses
        $content = preg_replace_callback('/\@foreach\s*\((.+)\)/', function($matches) {
            $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
            return '<?php foreach(' . $condition . '): ?>';
        }, $content);
        $content = preg_replace('/\@endforeach/', '<?php endforeach; ?>', $content);
        
        // @forelse - more complex pattern, handle carefully
        $content = preg_replace_callback('/\@forelse\s*\((.+)\s+as\s+(.+?)\)/', function($matches) {
            // Extract the array part before 'as'
            $fullMatch = $matches[1] . ' as ' . $matches[2];
            $condition = $this->extractBalancedParentheses('(' . $fullMatch . ')');
            
            // Split on ' as ' to get array and variable parts
            $parts = explode(' as ', $condition);
            if (count($parts) >= 2) {
                $array = trim($parts[0]);
                $variable = trim($parts[1]);
                return '<?php if(!empty(' . $array . ')): foreach(' . $array . ' as ' . $variable . '): ?>';
            }
            return '<?php if(!empty($1)): foreach($1 as $2): ?>';
        }, $content);
        $content = preg_replace('/\@empty/', '<?php endforeach; else: ?>', $content);
        $content = preg_replace('/\@endforelse/', '<?php endif; ?>', $content);
        
        // @for - use callback for balanced parentheses
        $content = preg_replace_callback('/\@for\s*\((.+)\)/', function($matches) {
            $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
            return '<?php for(' . $condition . '): ?>';
        }, $content);
        $content = preg_replace('/\@endfor/', '<?php endfor; ?>', $content);
        
        // @while - use callback for balanced parentheses
        $content = preg_replace_callback('/\@while\s*\((.+)\)/', function($matches) {
            $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
            return '<?php while(' . $condition . '): ?>';
        }, $content);
        $content = preg_replace('/\@endwhile/', '<?php endwhile; ?>', $content);
        
        // @continue, @break - handle optional conditions
        $content = preg_replace_callback('/\@continue(\s*\((.+)\))?/', function($matches) {
            if (isset($matches[2]) && !empty($matches[2])) {
                $condition = $this->extractBalancedParentheses('(' . $matches[2] . ')');
                return '<?php if(' . $condition . ') continue; ?>';
            }
            return '<?php continue; ?>';
        }, $content);
        
        $content = preg_replace_callback('/\@break(\s*\((.+)\))?/', function($matches) {
            if (isset($matches[2]) && !empty($matches[2])) {
                $condition = $this->extractBalancedParentheses('(' . $matches[2] . ')');
                return '<?php if(' . $condition . ') break; ?>';
            }
            return '<?php break; ?>';
        }, $content);
        
        return $content;
    }
    
    /**
     * Compile @switch, @case, @default
     */
    protected function compileSwitchStatements($content)
    {
        // @switch - use callback for balanced parentheses
        $content = preg_replace_callback('/\@switch\s*\((.+)\)/', function($matches) {
            $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
            return '<?php switch(' . $condition . '): ?>';
        }, $content);
        
        // @case - use callback for balanced parentheses
        $content = preg_replace_callback('/\@case\s*\((.+)\)/', function($matches) {
            $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
            return '<?php case ' . $condition . ': ?>';
        }, $content);
        
        $content = preg_replace('/\@default/', '<?php default: ?>', $content);
        $content = preg_replace('/\@endswitch/', '<?php endswitch; ?>', $content);
        return $content;
    }
    
    /**
     * Compile @extends
     */
    protected function compileExtends($content)
    {
        preg_match('/\@extends\s*\([\'"](.+?)[\'"]\)/', $content, $matches);
        
        if (!empty($matches)) {
            $layout = $matches[1];
            $content = preg_replace('/\@extends\s*\([\'"](.+?)[\'"]\)/', '<?php $__layout = "' . $layout . '"; ?>', $content);
        }
        
        return $content;
    }
    
    /**
     * Compile @section and @endsection
     */
    protected function compileSections($content)
    {
        // @section inline (process first)
        $content = preg_replace('/\@section\s*\([\'"](.+?)[\'"]\s*,\s*[\'"](.+?)[\'"]\)/', '<?php $__sections["$1"] = "$2"; ?>', $content);
        
        // @section with content
        $content = preg_replace('/\@section\s*\([\'"](.+?)[\'"]\)/', '<?php $__currentSection = "$1"; ob_start(); ?>', $content);
        $content = preg_replace('/\@endsection/', '<?php $__sections[$__currentSection] = ob_get_clean(); ?>', $content);
        
        return $content;
    }
    
    /**
     * Compile @yield
     */
    protected function compileYields($content)
    {
        $content = preg_replace('/\@yield\s*\([\'"](.+?)[\'"](?:\s*,\s*[\'"](.+?)[\'"]\s*)?\)/', '<?php echo $__sections["$1"] ?? "$2"; ?>', $content);
        return $content;
    }
    
    /**
     * Compile @include
     */
    protected function compileIncludes($content)
    {
        $content = preg_replace_callback('/\@include\s*\([\'"](.+?)[\'"](?:\s*,\s*(\[.+?\]))?\)/', function ($matches) {
            $view = $matches[1];
            $data = $matches[2] ?? '[]';
            return '<?php echo $this->render("' . $view . '", array_merge(get_defined_vars(), ' . $data . ')); ?>';
        }, $content);
        
        return $content;
    }
    
    /**
     * Compile @component
     */
    protected function compileComponents($content)
    {
        $content = preg_replace('/\@component\s*\([\'"](.+?)[\'"]\)/', '<?php $this->startComponent("$1"); ?>', $content);
        $content = preg_replace('/\@endcomponent/', '<?php echo $this->renderComponent(); ?>', $content);
        $content = preg_replace('/\@slot\s*\([\'"](.+?)[\'"]\)/', '<?php $this->slot("$1"); ?>', $content);
        $content = preg_replace('/\@endslot/', '<?php $this->endSlot(); ?>', $content);
        return $content;
    }
    
    /**
     * Compile @push, @stack
     */
    protected function compileStacks($content)
    {
        // Use a callback to properly capture stack names
        $content = preg_replace_callback('/\@push\s*\([\'"](.+?)[\'"]\)(.*?)\@endpush/s', function($matches) {
            $stackName = $matches[1];
            $stackContent = $matches[2];
            return '<?php if(!isset($__stacks["' . $stackName . '"])) $__stacks["' . $stackName . '"] = []; ob_start(); ?>' 
                   . $stackContent 
                   . '<?php $__stacks["' . $stackName . '"][] = ob_get_clean(); ?>';
        }, $content);
        
        $content = preg_replace('/\@stack\s*\([\'"](.+?)[\'"]\)/', '<?php echo isset($__stacks["$1"]) ? implode("", $__stacks["$1"]) : ""; ?>', $content);
        return $content;
    }
    
    /**
     * Compile @json
     */
    protected function compileJson($content)
    {
        $content = preg_replace_callback('/\@json\s*\((.+)\)/', function($matches) {
            $condition = $this->extractBalancedParentheses('(' . $matches[1] . ')');
            return '<?php echo json_encode(' . $condition . '); ?>';
        }, $content);
        return $content;
    }
    
    /**
     * Compile @csrf
     */
    protected function compileCsrf($content)
    {
        $content = preg_replace('/\@csrf/', '<?php echo \'<input type="hidden" name="_token" value="\' . ($__csrf ?? \'\') . \'">\'; ?>', $content);
        return $content;
    }
    
    /**
     * Compile @method
     */
    protected function compileMethod($content)
    {
        $content = preg_replace('/\@method\s*\([\'"](.+?)[\'"]\)/', '<?php echo \'<input type="hidden" name="_method" value="$1">\'; ?>', $content);
        return $content;
    }
    
    /**
     * Compile helper functions: @route, @asset, @url, etc.
     */
    protected function compileHelpers($content)
    {
        // @route directive
        $content = preg_replace('/\@route\s*\((.+?)\)/', '<?php echo route($1); ?>', $content);
        
        // @url directive
        $content = preg_replace('/\@url\s*\((.+?)\)/', '<?php echo url($1); ?>', $content);
        
        // @asset directive
        $content = preg_replace('/\@asset\s*\((.+?)\)/', '<?php echo asset($1); ?>', $content);

        // @assets directive
        $content = preg_replace('/\@assets\s*\((.+?)\)/', '<?php echo $this->host . "/assets/" . $1; ?>', $content);
        
        // @css directive
        $content = preg_replace('/\@css\s*\((.+?)\)/', '<?php echo css($1); ?>', $content);
        
        // @js directive
        $content = preg_replace('/\@js\s*\((.+?)\)/', '<?php echo js($1); ?>', $content);
        
        // @image directive
        $content = preg_replace('/\@image\s*\((.+?)\)/', '<?php echo image($1); ?>', $content);

        // @set_language directive
        $content = preg_replace('/\@set_language\s*\((.+?)\)/', '<?php echo set_language($1); ?>', $content);
        
        return $content;
    }
    
    /**
     * Compile translation directives: @lang, @choice
     */
    protected function compileTranslations($content)
    {
        // @lang directive - same as trans()
        $content = preg_replace('/\@lang\s*\((.+?)\)/', '<?php echo trans($1); ?>', $content);
        
        // @choice directive - for pluralization
        $content = preg_replace('/\@choice\s*\((.+?)\)/', '<?php echo trans_choice($1); ?>', $content);
        
        return $content;
    }
    
    /**
     * Get cache file path
     */
    protected function getCacheFile($viewFile)
    {
        return $this->cachePath . '/' . md5($viewFile) . '.php';
    }
    
    /**
     * Evaluate compiled template
     */
    protected function evaluate($path, $data)
    {
        $obLevel = ob_get_level();
        ob_start();
        
        extract($data, EXTR_SKIP);
        
        // Initialize blade variables
        if (!isset($__sections)) $__sections = [];
        if (!isset($__stacks)) $__stacks = [];
        if (!isset($__layout)) $__layout = null;
        if (!isset($__currentSection)) $__currentSection = null;
        
        try {
            include $path;
            
            // If layout exists, render it
            if (isset($__layout) && $__layout) {
                ob_end_clean(); // Clear current buffer
                return $this->render($__layout, array_merge($data, [
                    '__sections' => $__sections,
                    '__stacks' => $__stacks
                ]));
            }
            
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        }
        
        return ob_get_clean();
    }
    
    /**
     * Clear all cached views
     */
    public function clearCache()
    {
        $files = glob($this->cachePath . '/*.php');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
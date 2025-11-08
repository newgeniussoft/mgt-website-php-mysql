<?php 

namespace App\View;

class View {
    protected static $engine;
    protected static $shared = [];
    
    public static function setEngine(BladeEngine $engine) {
        self::$engine = $engine;
    }
    
    public static function make($view, $data = []) {
        if (!self::$engine) {
            throw new \Exception("Blade engine not initialized");
        }
        
        $data = array_merge(self::$shared, $data);
        
        echo self::$engine->render($view, $data);
    }
    
    public static function share($key, $value = null) {
        if (is_array($key)) {
            self::$shared = array_merge(self::$shared, $key);
        } else {
            self::$shared[$key] = $value;
        }
    }
    
    public static function composer($views, $callback) {
        // View composer logic (for future implementation)
        // This allows you to bind data to specific views automatically
    }
    
    public static function exists($view) {
        try {
            self::$engine->findView($view);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
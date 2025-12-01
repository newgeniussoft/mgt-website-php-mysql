<?php 

namespace App\View;

use App\View\ViewException;

class View {
    protected static $engine;
    protected static $shared = [];
    
    public static function setEngine(BladeEngine $engine) {
        self::$engine = $engine;
    }
    
    public static function make($view, $data = []) {
        if (!self::$engine) {
            throw ViewException::engineError("Blade engine not initialized");
        }
        
        try {
            $data = array_merge(self::$shared, $data);
            
            echo self::$engine->render($view, $data);
            self::$engine->clearCache();
        } catch (\Exception $e) {
            throw ViewException::compilationError($view, $e->getMessage());
        }
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
            if (!self::$engine) {
                throw ViewException::engineError("Blade engine not initialized");
            }
            self::$engine->findView($view);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
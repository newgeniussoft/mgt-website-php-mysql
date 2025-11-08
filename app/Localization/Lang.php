<?php

namespace App\Localization;

class Lang {
    protected static $locale = 'en';
    protected static $fallbackLocale = 'en';
    protected static $translations = [];
    protected static $langPath;
    
    public static function init($langPath = null) {
        self::$langPath = $langPath ?: __DIR__ . '/../../resources/lang';
    }
    
    public static function setLocale($locale) {
        self::$locale = $locale;
    }
    
    public static function getLocale() {
        return self::$locale;
    }
    
    public static function setFallbackLocale($locale) {
        self::$fallbackLocale = $locale;
    }
    
    public static function get($key, $replace = []) {
        $translation = self::getTranslation($key, self::$locale);
        
        if ($translation === null) {
            $translation = self::getTranslation($key, self::$fallbackLocale);
        }
        
        if ($translation === null) {
            return $key;
        }
        
        return self::makeReplacements($translation, $replace);
    }
    
    protected static function getTranslation($key, $locale) {
        list($file, $item) = self::parseKey($key);
        
        if (!isset(self::$translations[$locale][$file])) {
            self::loadTranslations($locale, $file);
        }
        
        $translations = self::$translations[$locale][$file] ?? [];
        
        return self::getNestedValue($translations, $item);
    }
    
    protected static function parseKey($key) {
        $segments = explode('.', $key);
        $file = array_shift($segments);
        $item = implode('.', $segments);
        
        return [$file, $item];
    }
    
    protected static function loadTranslations($locale, $file) {
        $path = self::$langPath . '/' . $locale . '/' . $file . '.php';
        
        if (file_exists($path)) {
            self::$translations[$locale][$file] = require $path;
        } else {
            self::$translations[$locale][$file] = [];
        }
    }
    
    protected static function getNestedValue($array, $key) {
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return null;
            }
            $array = $array[$segment];
        }
        
        return $array;
    }
    
    protected static function makeReplacements($line, array $replace) {
        foreach ($replace as $key => $value) {
            $line = str_replace(':' . $key, $value, $line);
        }
        
        return $line;
    }
    
    public static function choice($key, $number, $replace = []) {
        $line = self::get($key, $replace);
        
        $replace[':count'] = $number;
        
        return self::makeReplacements($line, $replace);
    }
    
    public static function has($key) {
        return self::get($key) !== $key;
    }
}
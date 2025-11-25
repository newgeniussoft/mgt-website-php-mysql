<?php

namespace App\Localization;
use App\Models\Translation;

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
        // 1) Try DB in current locale
        $translation = self::getDbTranslation($key, self::$locale);
        $sourceLocale = null;
        
        if ($translation === null) {
            // 2) Try DB in fallback locale
            $translation = self::getDbTranslation($key, self::$fallbackLocale);
            if ($translation !== null) { $sourceLocale = self::$fallbackLocale; }
        } else {
            $sourceLocale = self::$locale;
        }

        if ($translation === null) {
            // 3) Try file in current locale
            $translation = self::getTranslation($key, self::$locale);
            if ($translation !== null) { $sourceLocale = self::$locale; }
        }
        
        if ($translation === null) {
            // 4) Try file in fallback locale
            $translation = self::getTranslation($key, self::$fallbackLocale);
            if ($translation !== null) { $sourceLocale = self::$fallbackLocale; }
        }
        
        if ($translation === null) {
            return $key;
        }

        // Seed DB when value came from files, so DB becomes source of truth next time
        if ($sourceLocale !== null) {
            try {
                // Only seed if not already in DB for that locale
                if (self::getDbTranslation($key, $sourceLocale) === null) {
                    Translation::setValue($key, $sourceLocale, $translation);
                }
            } catch (\Throwable $e) {
                // Ignore seeding errors silently
            }
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
    
    protected static function getDbTranslation($key, $locale) {
        try {
            $row = Translation::findByKeyAndLocale($key, $locale);
            if ($row && isset($row->value)) {
                return $row->value;
            }
        } catch (\Throwable $e) {
            // Silently ignore DB errors (e.g., during early bootstrap or missing table)
        }
        return null;
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
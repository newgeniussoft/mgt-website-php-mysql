<?php

/**
 * Translation/Localization System
 * Supports multi-language with placeholders
 */

class Translator
{
    protected static $locale = 'en';
    protected static $fallbackLocale = 'en';
    protected static $translations = [];
    protected static $langPath = '';
    
    /**
     * Initialize translator
     */
    public static function init($langPath, $locale = 'en')
    {
        self::$langPath = rtrim($langPath, '/');
        self::$locale = $locale;
        self::loadTranslations($locale);
    }
    
    /**
     * Set current locale
     */
    public static function setLocale($locale)
    {
        self::$locale = $locale;
        self::loadTranslations($locale);
    }
    
    /**
     * Get current locale
     */
    public static function getLocale()
    {
        return self::$locale;
    }
    
    /**
     * Load translations for a locale
     */
    protected static function loadTranslations($locale)
    {
        $file = self::$langPath . '/' . $locale . '.php';
        
        if (file_exists($file)) {
            self::$translations[$locale] = require $file;
        } else {
            self::$translations[$locale] = [];
        }
    }
    
    /**
     * Get translation
     */
    public static function get($key, $replace = [], $locale = null)
    {
        $locale = $locale ?? self::$locale;
        
        // Load locale if not loaded
        if (!isset(self::$translations[$locale])) {
            self::loadTranslations($locale);
        }
        
        // Get translation using dot notation
        $translation = self::getFromArray(self::$translations[$locale], $key);
        
        // Fallback to key if not found
        if ($translation === null) {
            // Try fallback locale
            if ($locale !== self::$fallbackLocale) {
                return self::get($key, $replace, self::$fallbackLocale);
            }
            $translation = $key;
        }
        
        // Replace placeholders
        return self::replacePlaceholders($translation, $replace);
    }
    
    /**
     * Get value from array using dot notation
     */
    protected static function getFromArray($array, $key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return null;
            }
        }
        
        return $array;
    }
    
    /**
     * Replace placeholders in translation
     */
    protected static function replacePlaceholders($translation, $replace)
    {
        if (empty($replace)) {
            return $translation;
        }
        
        foreach ($replace as $key => $value) {
            $translation = str_replace([':' . $key, ':' . strtoupper($key)], $value, $translation);
        }
        
        return $translation;
    }
    
    /**
     * Check if translation exists
     */
    public static function has($key, $locale = null)
    {
        $locale = $locale ?? self::$locale;
        
        if (!isset(self::$translations[$locale])) {
            self::loadTranslations($locale);
        }
        
        return self::getFromArray(self::$translations[$locale], $key) !== null;
    }
    
    /**
     * Get translation with pluralization
     */
    public static function choice($key, $count, $replace = [], $locale = null)
    {
        $translation = self::get($key, $replace, $locale);
        
        // Simple pluralization logic
        if (is_array($translation)) {
            if ($count == 0 && isset($translation['zero'])) {
                return self::replacePlaceholders($translation['zero'], array_merge($replace, ['count' => $count]));
            } elseif ($count == 1 && isset($translation['one'])) {
                return self::replacePlaceholders($translation['one'], array_merge($replace, ['count' => $count]));
            } elseif (isset($translation['other'])) {
                return self::replacePlaceholders($translation['other'], array_merge($replace, ['count' => $count]));
            }
        }
        
        return self::replacePlaceholders($translation, array_merge($replace, ['count' => $count]));
    }
}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Translate a message (full function name)
 */
function trans($key, $replace = [], $locale = null) {
    return Translator::get($key, $replace, $locale);
}

/**
 * Translate a message (short alias)
 */
function __($key, $replace = [], $locale = null) {
    return Translator::get($key, $replace, $locale);
}

/**
 * Translate with pluralization
 */
function trans_choice($key, $count, $replace = [], $locale = null) {
    return Translator::choice($key, $count, $replace, $locale);
}

/**
 * Get current locale
 */
function app_locale() {
    return Translator::getLocale();
}

/**
 * Set locale
 */
function set_locale($locale) {
    Translator::setLocale($locale);
}

/**
 * Check if translation key exists
 */
function has_trans($key, $locale = null) {
    return Translator::has($key, $locale);
}
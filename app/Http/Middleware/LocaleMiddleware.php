<?php
namespace App\Http\Middleware;

use App\Localization\Lang;

class LocaleMiddleware extends Middleware {
    protected $supportedLocales = ['en', 'es'];
    protected $defaultLocale = 'en';
    
    public function handle($request, $next) {
        $locale = $this->detectLocale();
        
        Lang::setLocale($locale);
        
        // Store in session
        $_SESSION['locale'] = $locale;
        
        return $next($request);
    }
    
    protected function detectLocale() {
        // 1. Check URL path
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', trim($uri, '/'));
        
        if (!empty($segments[0]) && in_array($segments[0], $this->supportedLocales)) {
            return $segments[0];
        }
        
        // 2. Check session
        if (isset($_SESSION['locale']) && in_array($_SESSION['locale'], $this->supportedLocales)) {
            return $_SESSION['locale'];
        }
        
        // 3. Check browser language
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($browserLang, $this->supportedLocales)) {
                return $browserLang;
            }
        }
        
        // 4. Default locale
        return $this->defaultLocale;
    }
}
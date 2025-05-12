<header class="site-header">
        <div class="container">
            <a href="{{ $_ENV['APP_URL'] }}" class="site-logo">
                <img src="{{ assets('img/logo/logo_new_updated.png') }}" alt="Madagascar Green Tours Logo" width="180" height="40">
            </a>
            
            <nav class="site-nav" aria-label="Main Navigation">
                <ul>
                    <li><a href="{{ $_ENV['APP_URL'] }}" {{ $currentPage == 'home' ? 'aria-current="page"' : '' }}>Home</a></li>
                    <li><a href="{{ $_ENV['APP_URL'] }}/about" {{ $currentPage == 'about' ? 'aria-current="page"' : '' }}>About</a></li>
                    <li><a href="{{ $_ENV['APP_URL'] }}/" {{ $currentPage == '' ? 'aria-current="page"' : '' }}>SEO Guide</a></li>
                    <li>
                        <a href="{{ $language == 'es' ? $_ENV['APP_URL'] : $_ENV['APP_URL'] . '/es' . ($_SERVER['REQUEST_URI'] != '/' ? $_SERVER['REQUEST_URI'] : '') }}">
                            <img src="{{ assets('img/flags/' . ($language == 'es' ? 'uk' : 'spain') . '.png') }}" alt="{{ $language == 'es' ? 'English' : 'Español' }}" width="20">
                            
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
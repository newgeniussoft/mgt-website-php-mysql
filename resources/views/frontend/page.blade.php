<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }}</title>
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .site-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .menu ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }
        .menu a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        .menu a:hover {
            opacity: 0.8;
        }
        .menu .active a {
            border-bottom: 2px solid white;
        }
        main {
            min-height: 60vh;
            padding: 2rem 0;
        }
        .page-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        .page-content {
            margin-top: 2rem;
        }
        footer {
            background: #34495e;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            .menu ul {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="site-title">{{ $_ENV['APP_NAME'] ?? 'My Website' }}</div>
                <nav class="menu">
                    <ul>
                        @foreach($menuPages as $menuPage)
                            <li class="{{ $menuPage->slug === $page->slug ? 'active' : '' }}">
                                <a href="{{ $menuPage->is_homepage ? '/' : '/' . $menuPage->slug }}">
                                    {{ $menuPage->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h1 class="page-title">{{ $page->title }}</h1>
            
            <div class="page-content">
                @if(!empty($sections))
                    @foreach($sections as $section)
                        @php
                            echo $section->render();
                        @endphp
                    @endforeach
                @else
                    <p>No content available.</p>
                @endif
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ $_ENV['APP_NAME'] ?? 'My Website' }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

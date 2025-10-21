<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @if($meta_description)
        <meta name="description" content="{{ $meta_description }}">
    @endif
    @if($meta_keywords)
        <meta name="keywords" content="{{ $meta_keywords }}">
    @endif
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $title }}">
    @if($meta_description)
        <meta property="og:description" content="{{ $meta_description }}">
    @endif
    @if($page->featured_image)
        <meta property="og:image" content="{{ $_SERVER['HTTP_HOST'] }}/uploads/pages/{{ $page->featured_image }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] }}">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .content-area {
            line-height: 1.7;
        }
        .content-area h1, .content-area h2, .content-area h3, .content-area h4, .content-area h5, .content-area h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .content-area h1 { font-size: 2.25rem; }
        .content-area h2 { font-size: 1.875rem; }
        .content-area h3 { font-size: 1.5rem; }
        .content-area h4 { font-size: 1.25rem; }
        .content-area h5 { font-size: 1.125rem; }
        .content-area h6 { font-size: 1rem; }
        .content-area p { margin-bottom: 1rem; }
        .content-area ul, .content-area ol { margin-bottom: 1rem; padding-left: 2rem; }
        .content-area li { margin-bottom: 0.5rem; }
        .content-area img { max-width: 100%; height: auto; margin: 1rem 0; border-radius: 0.5rem; }
        .content-area blockquote { 
            border-left: 4px solid #e5e7eb; 
            padding-left: 1rem; 
            margin: 1rem 0; 
            font-style: italic; 
            color: #6b7280; 
        }
        .content-area table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 1rem 0; 
        }
        .content-area th, .content-area td { 
            border: 1px solid #e5e7eb; 
            padding: 0.75rem; 
            text-align: left; 
        }
        .content-area th { 
            background-color: #f9fafb; 
            font-weight: 600; 
        }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/" class="text-xl font-bold text-gray-900">
                            <i class="fas fa-home mr-2 text-blue-600"></i>
                            Your Website
                        </a>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-600 hover:text-blue-600 transition duration-200">Home</a>
                    @foreach($menu_pages as $menuPage)
                        <a href="/{{ $menuPage['slug'] }}" 
                           class="text-gray-600 hover:text-blue-600 transition duration-200 {{ $page->slug === $menuPage['slug'] ? 'text-blue-600 font-medium' : '' }}">
                            {{ $menuPage['title'] }}
                        </a>
                    @endforeach
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t">
                    <a href="/" class="block px-3 py-2 text-gray-600 hover:text-blue-600">Home</a>
                    @foreach($menu_pages as $menuPage)
                        <a href="/{{ $menuPage['slug'] }}" 
                           class="block px-3 py-2 text-gray-600 hover:text-blue-600 {{ $page->slug === $menuPage['slug'] ? 'text-blue-600 font-medium' : '' }}">
                            {{ $menuPage['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    @if($page->featured_image)
        <div class="hero-gradient relative h-96 flex items-center justify-center text-white">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <img src="/uploads/pages/{{ $page->featured_image }}" 
                 alt="{{ $page->title }}" 
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
                <h1 class="text-5xl font-bold mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-xl opacity-90">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    @else
        <div class="hero-gradient py-20 text-white text-center">
            <div class="max-w-4xl mx-auto px-4">
                <h1 class="text-5xl font-bold mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-xl opacity-90">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        @if($page->content)
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="content-area prose prose-lg max-w-none">
                    {!! $page->content !!}
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Content Coming Soon</h3>
                <p class="text-gray-600">This page is under construction. Please check back later.</p>
            </div>
        @endif
        
        <!-- Page Meta Information -->
        @if($page->updated_at)
            <div class="mt-8 text-center text-sm text-gray-500">
                Last updated: {{ date('F j, Y', strtotime($page->updated_at)) }}
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Your Website</h3>
                    <p class="text-gray-300">
                        Building amazing experiences with modern web technology.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-300 hover:text-white transition duration-200">Home</a></li>
                        @foreach($menu_pages as $menuPage)
                            <li>
                                <a href="/{{ $menuPage['slug'] }}" 
                                   class="text-gray-300 hover:text-white transition duration-200">
                                    {{ $menuPage['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Your Website. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading animation for images
        document.querySelectorAll('.content-area img').forEach(img => {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
            });
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
        });
    </script>
</body>
</html>

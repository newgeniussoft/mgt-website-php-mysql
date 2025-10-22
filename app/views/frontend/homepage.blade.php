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
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
                    <a href="/" class="text-blue-600 font-medium">Home</a>
                    @foreach($menu_pages as $menuPage)
                    @if(!$menuPage['is_homepage'])
                        <a href="/{{ $menuPage['slug'] }}" 
                           class="text-gray-600 hover:text-blue-600 transition duration-200">
                            {{ $menuPage['title'] }}
                        </a>
                    @endif
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
                    <a href="/" class="block px-3 py-2 text-blue-600 font-medium">Home</a>
                    @foreach($menu_pages as $menuPage)
                        <a href="/{{ $menuPage['slug'] }}" 
                           class="block px-3 py-2 text-gray-600 hover:text-blue-600">
                            {{ $menuPage['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    @if($page->featured_image)
        <div class="hero-gradient relative h-screen flex items-center justify-center text-white">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <img src="/uploads/pages/{{ $page->featured_image }}" 
                 alt="{{ $page->title }}" 
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
                <h1 class="text-6xl font-bold mb-6">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-2xl opacity-90 mb-8">{{ $page->excerpt }}</p>
                @endif
                <div class="space-x-4">
                    <a href="#content" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                        Learn More
                    </a>
                    <a href="#contact" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="hero-gradient py-32 text-white text-center">
            <div class="max-w-4xl mx-auto px-4">
                <h1 class="text-6xl font-bold mb-6">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-2xl opacity-90 mb-8">{{ $page->excerpt }}</p>
                @endif
                <div class="space-x-4">
                    <a href="#content" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                        Learn More
                    </a>
                    <a href="#contact" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    @if($page->content)
        <section id="content" class="py-16">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="content-area prose prose-lg max-w-none">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Us?</h2>
                <p class="text-xl text-gray-600">Discover what makes us different</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-rocket text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Fast & Reliable</h3>
                    <p class="text-gray-600">Lightning-fast performance with 99.9% uptime guarantee.</p>
                </div>
                
                <div class="feature-card bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Secure</h3>
                    <p class="text-gray-600">Enterprise-grade security to protect your data and privacy.</p>
                </div>
                
                <div class="feature-card bg-gray-50 p-8 rounded-lg text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">24/7 Support</h3>
                    <p class="text-gray-600">Round-the-clock customer support whenever you need help.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section id="contact" class="py-16 hero-gradient text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl opacity-90 mb-8">Join thousands of satisfied customers today.</p>
            <div class="space-x-4">
                <a href="/contact" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                    Contact Us
                </a>
                <a href="/about" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Your Website</h3>
                    <p class="text-gray-300 mb-4">
                        Building amazing experiences with modern web technology. 
                        We're committed to delivering excellence in everything we do.
                    </p>
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
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-envelope mr-2"></i> info@yourwebsite.com</li>
                        <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Main St, City, State</li>
                    </ul>
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

        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-gradient');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>

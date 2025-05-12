<?php
// Import necessary helper functions
require_once __DIR__ . '/../utils/helpers/helper.php';

// Get current language and time
$language = isset($language) ? $language : 'en';
$currentPage = isset($currentPage) ? $currentPage : '';
$lastUpdated = isset($lastUpdated) ? $lastUpdated : '2025-05-11T17:14:42+03:00';
$metaTitle = isset($metaTitle) ? $metaTitle : 'SEO Best Practices - Performance Optimized Page';
$metaDescription = isset($metaDescription) ? $metaDescription : 'This test page demonstrates SEO best practices for content structure, semantic HTML, performance optimization, and user experience enhancements.';
$metaKeywords = isset($metaKeywords) ? $metaKeywords : 'seo best practices, web performance, content optimization, semantic html, page speed, user experience';
$metaImage = isset($metaImage) ? $metaImage : 'img/images/lemur.webp';
?>
<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Page Title -->
    <title>{{ $metaTitle }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="author" content="Madagascar Green Tours">
    <meta name="robots" content="index,follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $_ENV['APP_URL'] }}/seo-test">
    
    <!-- Language Alternates for SEO -->
    <link rel="alternate" hreflang="en" href="{{ $_ENV['APP_URL'] }}/seo-test">
    <link rel="alternate" hreflang="es" href="{{ $_ENV['APP_URL'] }}/es/seo-test">
    <link rel="alternate" hreflang="x-default" href="{{ $_ENV['APP_URL'] }}/seo-test">
    
    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:site_name" content="Madagascar Green Tours" />
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />
    <meta property="og:url" content="{{ $_ENV['APP_URL'] }}/seo-test" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ assets($metaImage) }}" />
    <meta property="og:image:alt" content="{{ $metaTitle }}" />
    <meta property="og:locale" content="{{ $language == 'es' ? 'es_ES' : 'en_US' }}" />
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ assets($metaImage) }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ assets('img/logo/favicon.png') }}">
    
    <!-- Preload Critical Assets -->
    <link rel="preload" href="{{ assets('img/images/lemur.webp') }}" as="image">
    
    <!-- Structured Data for SEO (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ $metaTitle }}",
      "description": "{{ $metaDescription }}",
      "url": "{{ $_ENV['APP_URL'] }}/seo-test",
      "datePublished": "2025-05-11T17:14:42+03:00",
      "dateModified": "{{ $lastUpdated }}",
      "publisher": {
        "@type": "Organization",
        "name": "Madagascar Green Tours",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ assets('img/logo/logo_new_updated.png') }}"
        }
      },
      "image": {
        "@type": "ImageObject",
        "url": "{{ assets($metaImage) }}"
      }
    }
    </script>
    
    <!-- Critical CSS Inlined for Performance -->
    <style>
        /* Reset and Base Styles */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 0.5em;
            font-weight: 600;
            line-height: 1.2;
            color: #212529;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        h2 {
            font-size: 2rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        h3 {
            font-size: 1.5rem;
            margin-top: 1.5rem;
        }
        
        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }
        
        /* Layout */
        .container {
            width: 100%;
            max-width: 1200px;
            margin-right: auto;
            margin-left: auto;
            padding-right: 15px;
            padding-left: 15px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        
        .col-md-4, .col-md-6, .col-lg-4, .col-lg-8 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        
        @media (min-width: 768px) {
            .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
            
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        
        @media (min-width: 992px) {
            .col-lg-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
            
            .col-lg-8 {
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }
        }
        
        /* Header Styles */
        .site-header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .site-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .site-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .site-logo img {
            height: 40px;
            width: auto;
        }
        
        .site-nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .site-nav li {
            margin-left: 1.5rem;
        }
        
        .site-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .site-nav a:hover {
            color: #0066cc;
        }
        
        /* Hero Section */
        .hero-section {
            background-color: #f8f9fa;
            padding: 4rem 0;
            text-align: center;
        }
        
        .hero-section h1 {
            margin-bottom: 1rem;
        }
        
        .hero-section .lead {
            font-size: 1.25rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        
        /* Breadcrumbs */
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            padding: 0.75rem 0;
            margin-bottom: 1rem;
            list-style: none;
            background-color: transparent;
        }
        
        .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.5rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            display: inline-block;
            padding-right: 0.5rem;
            color: #6c757d;
            content: "/";
        }
        
        .breadcrumb-item a {
            color: #0066cc;
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #6c757d;
        }
        
        /* Content Sections */
        .content-section {
            padding: 4rem 0;
        }
        
        .content-section header {
            margin-bottom: 2rem;
        }
        
        /* Cards */
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,0.125);
            border-radius: 0.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        
        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0,0,0,0.03);
            border-bottom: 1px solid rgba(0,0,0,0.125);
        }
        
        .card-body {
            flex: 1 1 auto;
            padding: 1.25rem;
        }
        
        .card-title {
            margin-bottom: 0.75rem;
        }
        
        .card-text:last-child {
            margin-bottom: 0;
        }
        
        /* Tables */
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
        }
        
        .table caption {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            color: #6c757d;
            text-align: left;
            caption-side: bottom;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            text-decoration: none;
        }
        
        .btn-primary {
            color: #fff;
            background-color: #0066cc;
            border-color: #0066cc;
        }
        
        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #004fa3;
        }
        
        .btn-outline-primary {
            color: #0066cc;
            border-color: #0066cc;
        }
        
        .btn-outline-primary:hover {
            color: #fff;
            background-color: #0066cc;
            border-color: #0066cc;
        }
        
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0.3rem;
        }
        
        /* Utilities */
        .text-center {
            text-align: center;
        }
        
        /* Footer */
        .site-footer {
            background-color: #212529;
            color: #f8f9fa;
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #f8f9fa;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .footer-bottom {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
    </style>
    
    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"></noscript>
</head>
<body class="seo-test-page">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-link visually-hidden">Skip to main content</a>
    
    <!-- Header with optimized navigation -->
    <header class="site-header">
        <div class="container">
            <a href="{{ $_ENV['APP_URL'] }}" class="site-logo">
                <img src="{{ assets('img/logo/logo_new_updated.png') }}" alt="Madagascar Green Tours Logo" width="180" height="40">
            </a>
            
            <nav class="site-nav" aria-label="Main Navigation">
                <ul>
                    <li><a href="{{ $_ENV['APP_URL'] }}" {{ $currentPage == 'home' ? 'aria-current="page"' : '' }}>Home</a></li>
                    <li><a href="{{ $_ENV['APP_URL'] }}/about" {{ $currentPage == 'about' ? 'aria-current="page"' : '' }}>About</a></li>
                    <li><a href="{{ $_ENV['APP_URL'] }}/seo-test" {{ $currentPage == 'seo-test' ? 'aria-current="page"' : '' }}>SEO Guide</a></li>
                    <li>
                        <a href="{{ $language == 'es' ? $_ENV['APP_URL'] : $_ENV['APP_URL'] . '/es' . ($_SERVER['REQUEST_URI'] != '/' ? $_SERVER['REQUEST_URI'] : '') }}">
                            <img src="{{ assets('img/flags/' . ($language == 'es' ? 'uk' : 'spain') . '.png') }}" alt="{{ $language == 'es' ? 'English' : 'Español' }}" width="20" height="14">
                            <span>{{ $language == 'es' ? 'EN' : 'ES' }}</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
        
    <!-- Main Content - Using semantic HTML structure -->
    <main id="main-content" role="main">
        <!-- Hero Section with optimized image -->
        <section class="hero-section">
            <div class="container">
                <h1>SEO Best Practices & Performance Optimization</h1>
                <p class="lead">A demonstration of content structure, semantic HTML, and performance techniques</p>
                
                <!-- Breadcrumbs for SEO -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="{{ $_ENV['APP_URL'] }}" itemprop="item"><span itemprop="name">Home</span></a>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span itemprop="name">SEO Test</span>
                            <meta itemprop="position" content="2" />
                        </li>
                    </ol>
                </nav>
            </div>
        </section>
            
            <!-- Content Section with proper heading hierarchy -->
            <section class="content-section">
                <div class="container">
                    <article>
                        <header>
                            <h2>Content Structure & Semantic HTML</h2>
                            <p>Last Updated: <time datetime="{{ $lastUpdated }}">{{ date('F j, Y', strtotime($lastUpdated)) }}</time></p>
                        </header>
                        
                        <div class="row">
                            <div class="col-lg-8">
                                <section>
                                    <h3>Why Semantic HTML Matters for SEO</h3>
                                    <p>Search engines rely on proper HTML structure to understand content hierarchy and relevance. Using semantic elements like <code>&lt;article&gt;</code>, <code>&lt;section&gt;</code>, and <code>&lt;nav&gt;</code> helps search engines interpret your content correctly.</p>
                                    
                                    <h4>Key Benefits:</h4>
                                    <ul>
                                        <li>Improved accessibility for screen readers and assistive technologies</li>
                                        <li>Better content interpretation by search engine crawlers</li>
                                        <li>Enhanced user experience through clear content structure</li>
                                        <li>Support for featured snippets and rich results in search</li>
                                    </ul>
                                    
                                    <blockquote cite="https://developers.google.com/search/docs/fundamentals/seo-starter-guide">
                                        <p>"Creating compelling and useful content will likely influence your website more than any other factors."</p>
                                        <footer>— <cite>Google SEO Starter Guide</cite></footer>
                                    </blockquote>
                                </section>
                                
                                <section>
                                    <h3>Content Optimization Techniques</h3>
                                    <p>High-quality, relevant content is the foundation of SEO success. Here are key techniques for optimizing your content:</p>
                                    
                                    <div class="table-responsive">
                                        <table class="table">
                                            <caption>Content Optimization Best Practices</caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Technique</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Impact</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Keyword Research</td>
                                                    <td>Identify terms your audience uses to find your content</td>
                                                    <td>High</td>
                                                </tr>
                                                <tr>
                                                    <td>Natural Language</td>
                                                    <td>Write for humans first, then optimize for search engines</td>
                                                    <td>High</td>
                                                </tr>
                                                <tr>
                                                    <td>Content Structure</td>
                                                    <td>Use proper headings, paragraphs, and lists</td>
                                                    <td>Medium</td>
                                                </tr>
                                                <tr>
                                                    <td>Internal Linking</td>
                                                    <td>Connect related content with descriptive anchor text</td>
                                                    <td>Medium</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                            
                            <aside class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Performance Metrics</h4>
                                    </div>
                                    <div class="card-body">
                                        <h5>Core Web Vitals</h5>
                                        <ul>
                                            <li><strong>LCP (Largest Contentful Paint):</strong> < 2.5s</li>
                                            <li><strong>FID (First Input Delay):</strong> < 100ms</li>
                                            <li><strong>CLS (Cumulative Layout Shift):</strong> < 0.1</li>
                                        </ul>
                                        
                                        <h5>Additional Metrics</h5>
                                        <ul>
                                            <li><strong>Page Size:</strong> < 500KB</li>
                                            <li><strong>HTTP Requests:</strong> < 50</li>
                                            <li><strong>Time to First Byte:</strong> < 200ms</li>
                                        </ul>
                                        
                                        <a href="#performance-section" class="btn btn-primary">Learn More</a>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </article>
                </div>
            </section>
            
            <!-- Performance Section with lazy-loaded images -->
            <section id="performance-section" class="performance-section">
                <div class="container">
                    <h2>Web Performance Optimization</h2>
                    <p>Performance is a critical factor for both user experience and search engine rankings.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Image Optimization</h3>
                            <p>Images often account for the largest portion of page weight. Proper optimization can dramatically improve loading times.</p>
                            
                            <div class="image-comparison">
                                <figure>
                                    <img src="{{ assets('img/images/lemur.webp') }}" alt="Optimized image of a lemur in Madagascar" width="400" height="300" loading="lazy">
                                    <figcaption>Optimized image: Lemur in Madagascar</figcaption>
                                </figure>
                            </div>
                            
                            <h4>Image Best Practices:</h4>
                            <ul>
                                <li>Use modern formats like WebP or AVIF</li>
                                <li>Specify width and height attributes</li>
                                <li>Implement lazy loading for below-fold images</li>
                                <li>Provide responsive images with srcset</li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h3>Code Optimization</h3>
                            <p>Clean, efficient code reduces page weight and improves rendering performance.</p>
                            
                            <h4>JavaScript Best Practices:</h4>
                            <ul>
                                <li>Defer non-critical JavaScript</li>
                                <li>Minimize DOM manipulations</li>
                                <li>Use code splitting for large applications</li>
                                <li>Implement proper event delegation</li>
                            </ul>
                            
                            <h4>CSS Best Practices:</h4>
                            <ul>
                                <li>Minimize unused CSS</li>
                                <li>Use CSS variables for maintainability</li>
                                <li>Implement critical CSS inline</li>
                                <li>Avoid render-blocking resources</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- User Experience Section -->
            <section class="ux-section">
                <div class="container">
                    <h2>User Experience & Accessibility</h2>
                    <p>User experience signals are increasingly important for SEO rankings.</p>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-card">
                                <h3>Mobile Optimization</h3>
                                <p>Mobile-first design ensures your content works well on all devices.</p>
                                <ul>
                                    <li>Responsive layouts</li>
                                    <li>Touch-friendly navigation</li>
                                    <li>Readable font sizes</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="feature-card">
                                <h3>Accessibility</h3>
                                <p>Making your content accessible improves usability for all users.</p>
                                <ul>
                                    <li>Proper heading structure</li>
                                    <li>Descriptive alt text</li>
                                    <li>Keyboard navigation</li>
                                    <li>Sufficient color contrast</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="feature-card">
                                <h3>Page Speed</h3>
                                <p>Fast-loading pages reduce bounce rates and improve conversions.</p>
                                <ul>
                                    <li>Server optimization</li>
                                    <li>Asset minification</li>
                                    <li>Efficient caching</li>
                                    <li>Content delivery networks</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Call to Action Section -->
            <section class="cta-section">
                <div class="container">
                    <h2>Ready to Optimize Your Website?</h2>
                    <p>Implement these best practices to improve your search visibility and user experience.</p>
                    <a href="/contact" class="btn btn-primary btn-lg">Contact Us</a>
                </div>
            </section>
            
            <!-- FAQ Section with structured data support -->
            <section class="faq-section">
                <div class="container">
                    <h2>Frequently Asked Questions</h2>
                    
                    <div class="faq-container" itemscope itemtype="https://schema.org/FAQPage">
                        <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <h3 itemprop="name">What is the most important SEO factor?</h3>
                            <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text">
                                    <p>While there are many factors that influence SEO, high-quality, relevant content remains the most important. Search engines aim to provide the best answers to user queries, so creating valuable content that addresses user needs is paramount.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <h3 itemprop="name">How do Core Web Vitals affect SEO?</h3>
                            <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text">
                                    <p>Core Web Vitals are a set of specific factors that Google considers important for user experience. They include Largest Contentful Paint (loading), First Input Delay (interactivity), and Cumulative Layout Shift (visual stability). These metrics are now ranking factors in Google's algorithm, meaning sites with better performance in these areas may receive a ranking boost.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <h3 itemprop="name">Why is mobile optimization important for SEO?</h3>
                            <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                <div itemprop="text">
                                    <p>Google uses mobile-first indexing, which means it primarily uses the mobile version of a site for indexing and ranking. With more than half of all web traffic coming from mobile devices, having a mobile-optimized site is essential for both user experience and search visibility.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        
        <!-- Related Content Section -->
        <section class="related-content">
            <div class="container">
                <h2>Related Resources</h2>
                <div class="row">
                    <div class="col-md-4">
                        <article class="card">
                            <img src="{{ assets('img/images/isalo.jpg') }}" class="card-img-top" alt="Beautiful landscape of Isalo National Park in Madagascar" width="350" height="200" loading="lazy">
                            <div class="card-body">
                                <h3 class="card-title">Content Optimization Guide</h3>
                                <p class="card-text">Learn advanced techniques for creating search-friendly content.</p>
                                <a href="#" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </article>
                    </div>
                    
                    <div class="col-md-4">
                        <article class="card">
                            <img src="{{ assets('img/images/tsingy.jpg') }}" class="card-img-top" alt="The remarkable Tsingy rock formations in Madagascar" width="350" height="200" loading="lazy">
                            <div class="card-body">
                                <h3 class="card-title">Performance Optimization</h3>
                                <p class="card-text">Discover how to improve your site's loading speed and performance.</p>
                                <a href="#" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </article>
                    </div>
                    
                    <div class="col-md-4">
                        <article class="card">
                            <img src="{{ assets('img/images/baobab1.jpg') }}" class="card-img-top" alt="Iconic baobab trees in Madagascar, a popular tourist attraction" width="350" height="200" loading="lazy">
                            <div class="card-body">
                                <h3 class="card-title">Mobile SEO Best Practices</h3>
                                <p class="card-text">Optimize your site for mobile users and improve rankings.</p>
                                <a href="#" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        
    </main>
    
    <!-- Footer with semantic structure and accessibility features -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h2>Madagascar Green Tours</h2>
                    <p>Eco-friendly travel experiences in Madagascar with sustainable tourism practices.</p>
                    <p>
                        <a href="{{ $_ENV['APP_URL'] }}" aria-label="Home page">
                            <img src="{{ assets('img/logo/logo_new_updated.png') }}" alt="Madagascar Green Tours Logo" width="180" height="40">
                        </a>
                    </p>
                </div>
                
                <div class="col-md-4">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ $_ENV['APP_URL'] }}">Home</a></li>
                        <li><a href="{{ $_ENV['APP_URL'] }}/about">About Us</a></li>
                        <li><a href="{{ $_ENV['APP_URL'] }}/seo-test">SEO Guide</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h3>Contact Us</h3>
                    <address>
                        <p><i class="fas fa-map-marker-alt"></i> Antananarivo, Madagascar</p>
                        <p><i class="fas fa-phone"></i> <a href="tel:+261340522823">+261 34 05 228 23</a></p>
                        <p><i class="fas fa-envelope"></i> <a href="mailto:info@madagascar-green-tours.com">info@madagascar-green-tours.com</a></p>
                    </address>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Madagascar Green Tours. All rights reserved.</p>
                <p>
                    <small>Last updated: <time datetime="{{ $lastUpdated }}">{{ date('F j, Y', strtotime($lastUpdated)) }}</time></small>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Deferred JavaScript for performance -->
    <script defer>
        // Initialize performance monitoring
        if ('performance' in window && 'getEntriesByType' in performance) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    const pageLoadTime = perfData.loadEventEnd - perfData.startTime;
                    console.log(`Page fully loaded in ${pageLoadTime.toFixed(0)}ms`);
                }, 0);
            });
        }
        
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    
    <!-- Inline critical CSS for performance -->
    <style>
        /* Critical CSS for above-the-fold content */
        .hero-section {
            padding: 4rem 0;
            background-color: #f8f9fa;
            text-align: center;
        }
        
        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #212529;
        }
        
        .hero-section .lead {
            font-size: 1.25rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0.75rem 0;
        }
        
        .content-section {
            padding: 4rem 0;
        }
        
        /* Ensure content is readable on all devices */
        p, li {
            font-size: 1rem;
            line-height: 1.6;
            color: #212529;
        }
        
        /* Improve heading hierarchy visibility */
        h2 {
            margin-top: 2rem;
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        
        h3 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #495057;
        }
    </style>
    
    <!-- Deferred non-critical JavaScript -->
    <script defer>
        // Example of deferred JavaScript for performance
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize FAQ accordion functionality
            const faqItems = document.querySelectorAll('.faq-item h3');
            
            faqItems.forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.toggle('active');
                    const answer = this.nextElementSibling;
                    answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
                });
            });
            
            // Track page performance metrics
            if ('performance' in window && 'getEntriesByType' in performance) {
                window.addEventListener('load', () => {
                    // Get navigation timing data
                    const navData = performance.getEntriesByType('navigation')[0];
                    
                    // Calculate and log key metrics
                    const pageLoadTime = navData.loadEventEnd - navData.startTime;
                    const domContentLoaded = navData.domContentLoadedEventEnd - navData.startTime;
                    
                    console.log(`Page Load Time: ${pageLoadTime.toFixed(2)}ms`);
                    console.log(`DOM Content Loaded: ${domContentLoaded.toFixed(2)}ms`);
                    
                    // Report to analytics (example)
                    if (typeof gtag === 'function') {
                        gtag('event', 'timing_complete', {
                            'name': 'page_load',
                            'value': pageLoadTime,
                            'event_category': 'Performance'
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>

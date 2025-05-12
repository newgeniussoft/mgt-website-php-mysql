@import('app/utils/helpers/helper.php')
<!DOCTYPE html>
<html lang="{{ $language ?? 'en' }}">
<head>
  <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <!-- Page Title -->
    <title>{{ isset($metaTitle) ? $metaTitle : 'Madagascar Green Tours - Eco-Friendly Travel Experience' }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ isset($metaDescription) ? $metaDescription : 'Madagascar Green Tours offers eco-friendly travel experiences in Madagascar with sustainable tourism practices. Explore unique wildlife, breathtaking landscapes, and authentic cultural experiences.' }}">
    <meta name="keywords" content="{{ isset($metaKeywords) ? $metaKeywords : 'madagascar tours, eco tourism, green travel, wildlife tours, sustainable tourism, madagascar travel, lemur tours' }}">
    <meta name="author" content="Madagascar Green Tours">
    
    <!-- Robots Control -->
    <meta name="robots" content="{{ isset($metaRobots) ? $metaRobots : 'index,follow' }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $_ENV['APP_URL'] . $_SERVER['REQUEST_URI'] }}">

    <!-- Language Alternates for SEO -->
    <link rel="alternate" hreflang="en" href="{{ switchTo('en') }}">
    <link rel="alternate" hreflang="es" href="{{ switchTo('es') }}">
    <link rel="alternate" hreflang="x-default" href="{{ switchTo('en') }}">
    
    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:site_name" content="Madagascar Green Tours" />
    <meta property="og:title" content="{{ isset($metaTitle) ? $metaTitle : 'Madagascar Green Tours - Eco-Friendly Travel Experience' }}" />
    <meta property="og:description" content="{{ isset($metaDescription) ? $metaDescription : 'Madagascar Green Tours offers eco-friendly travel experiences in Madagascar with sustainable tourism practices. Explore unique wildlife, breathtaking landscapes, and authentic cultural experiences.' }}" />
    <meta property="og:url" content="{{ $_ENV['APP_URL'] . $_SERVER['REQUEST_URI'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ isset($metaImage) ? $metaImage : assets('img/logo/logo_new_updated.png') }}" />
    <meta property="og:image:alt" content="{{ isset($metaTitle) ? $metaTitle : 'Madagascar Green Tours' }}" />
    <meta property="og:locale" content="{{ $language == 'es' ? 'es_ES' : 'en_US' }}" />
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($metaTitle) ? $metaTitle : 'Madagascar Green Tours - Eco-Friendly Travel Experience' }}">
    <meta name="twitter:description" content="{{ isset($metaDescription) ? $metaDescription : 'Madagascar Green Tours offers eco-friendly travel experiences in Madagascar with sustainable tourism practices.' }}">
    <meta name="twitter:image" content="{{ isset($metaImage) ? $metaImage : assets('img/logo/logo_new_updated.png') }}">
    
    <!-- Mobile App Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Madagascar Green Tours">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ assets('img/logo/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ assets('img/logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ assets('img/logo/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" href="{{ assets('img/logo/favicon.png') }}">
    <link rel="manifest" href="{{ assets('site.webmanifest') }}">
    
    <!-- Preconnect to External Domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Structured Data for SEO (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TravelAgency",
      "name": "Madagascar Green Tours",
      "url": "{{ $_ENV['APP_URL'] }}",
      "logo": "{{ assets('img/logo/logo_new_updated.png') }}",
      "image": "{{ isset($metaImage) ? $metaImage : assets('img/logo/logo_new_updated.png') }}",
      "description": "{{ isset($metaDescription) ? $metaDescription : 'Madagascar Green Tours offers eco-friendly travel experiences in Madagascar with sustainable tourism practices.' }}",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "Madagascar",
        "addressLocality": "Antananarivo"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-18.8792",
        "longitude": "47.5079"
      },
      "sameAs": [
        "https://www.facebook.com/madagascartours",
        "https://www.instagram.com/madagascartours",
        "https://www.tripadvisor.com/madagascartours"
      ],
      "priceRange": "$$$",
      "openingHours": "Mo-Fr 08:00-17:00",
      "telephone": "+261 34 05 228 23",
      "email": "info@madagascar-green-tours.com"
    }
    </script>

     <!-- Page-specific Structured Data -->
    @if($currentPage == 'home')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ isset($metaTitle) ? $metaTitle : 'Madagascar Green Tours' }}",
      "description": "{{ isset($metaDescription) ? $metaDescription : 'Madagascar Green Tours offers eco-friendly travel experiences in Madagascar.' }}",
      "url": "{{ $_ENV['APP_URL'] . $_SERVER['REQUEST_URI'] }}",
      "isPartOf": {
        "@type": "WebSite",
        "name": "Madagascar Green Tours",
        "url": "{{ $_ENV['APP_URL'] }}"
      }
    }
    </script>
    @endif
    
    
		<link rel="stylesheet" href="{{ assets('css/timeline.css') }}">
		<link rel="stylesheet" href="{{ assets('plugins/gallery/css/app.css') }}">
		<link rel="stylesheet" href="{{ assets('plugins/gallery/css/theme.css') }}">
		<link rel="stylesheet" href="{{ assets('css/amination.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/style.min.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/font-awesome.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/lineicons.css') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/bootstrap.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/flaticonb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/owl.carousel.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/owl.theme.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/owl.transitionsb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/perfect-scrollbar.minb6a4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/style.min.6.4.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('css/style.css') }}" media="screen" type="text/css">
		<link rel="stylesheet" href="{{ assets('css/vc-customize.min.css?ver=6.6.1') }}" media="all">
		<link rel="stylesheet" href="{{ assets('plugins/light-gallery/css/lightgallery.css') }}">
		<link rel="stylesheet" href="{{ assets('css/material.textfield.css') }}">
		<link rel="stylesheet" href="{{ assets('css/iview.css') }}">
		<link rel="stylesheet" href="{{ assets('css/style.mgt.css') }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ assets('img/logo/favicon.png') }}">
	

</head>
<body>
    

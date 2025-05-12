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
    <link rel="canonical" href="{{ $_ENV['APP_URL'] }}">
    
    <!-- Language Alternates for SEO -->
    <link rel="alternate" hreflang="en" href="{{ $_ENV['APP_URL'] }}">
    <link rel="alternate" hreflang="es" href="{{ $_ENV['APP_URL'] }}/es/">
    <link rel="alternate" hreflang="x-default" href="{{ $_ENV['APP_URL'] }}">
    
    <!-- Open Graph Meta Tags for Social Media -->
    <meta property="og:site_name" content="Madagascar Green Tours" />
    <meta property="og:title" content="{{ $metaTitle }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />
    <meta property="og:url" content="{{ $_ENV['APP_URL'] }}/" />
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

    <!-- Critical CSS -->
    <link rel="stylesheet" href="{{ assets('css/style.css') }}">
   
    <!-- Structured Data for SEO (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "{{ $metaTitle }}",
      "description": "{{ $metaDescription }}",
      "url": "{{ $_ENV['APP_URL'] }}/",
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
    
    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"></noscript>
</head>
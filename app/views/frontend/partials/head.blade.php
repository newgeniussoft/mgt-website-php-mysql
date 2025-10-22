<meta charset="UTF-8"> {{ $language }}
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<title>{{ $title }}</title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Madagascar Green Tours">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

<!-- Performance optimization meta tags -->
<meta http-equiv="Cache-Control" content="max-age=86400">
<meta name="theme-color" content="#4CAF50">
<link rel="canonical" href="{{ url() }}">

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Favicon and App Icons -->
<link rel="apple-touch-icon" sizes="180x180" href="@asset('img/logos/apple-touch-icon.png')">
<link rel="icon" type="image/png" sizes="32x32" href="@asset('img/logos/favicon-32x32.png')">
<link rel="icon" type="image/png" sizes="16x16" href="@asset('img/logos/favicon-16x16.png')">
<link rel="manifest" href="@asset('site.webmanifest')">

<!-- Open Graph / Facebook Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="en_US"><!-- Don't check here if you have internet -->
<meta property="og:site_name" content="Madagascar Green Tours">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@MGTours">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="">

<!-- Alternate Language Links -->
<link rel="alternate" hreflang="en" href="{{ set_language('en') }}">
<link rel="alternate" hreflang="es" href="{{ set_language('es') }}">
<link rel="alternate" hreflang="x-default" href="{{ set_language('en') }}">

<!-- Resource hints for faster loading --   >
<!-- Preload critical CSS -->
<link rel="preload" href="@asset('css/bootstrap.min.css')" as="style" onload="this.rel='stylesheet'" crossorigin>
<link rel="stylesheet" href="http://localhost/mgt-latest/assets/css/lineicons.css" media="all">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css" />
<noscript><link rel="stylesheet" href="@asset('css/bootstrap.min.css')"></noscript>
<link rel="preload" href="@asset('css/styles.css')" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="@asset('css/styles.css')"></noscript>

<!-- Preload critical images (logo and hero images) -->
<link rel="preload" href="" as="image" imagesrcset="" imagesizes="(max-width: 768px) 120px, 220px">
<link rel="preconnect" href="https://maxcdn.bootstrapcdn.com" crossorigin>
<link rel="dns-prefetch" href="https://maxcdn.bootstrapcdn.com">
<link rel="preconnect" href="https://code.jquery.com" crossorigin>
<link rel="dns-prefetch" href="https://code.jquery.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

<!-- Deferred non-critical CSS -->
<link rel="stylesheet" href="@asset('css/bootstrap.min.css')"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="@asset('css/all.css')" media="print" onload="this.media='all'">
<link rel="stylesheet" href="@asset('css/styles.css')" media="print" onload="this.media='all'">
<link rel="stylesheet" href="@asset('css/timeline.css')" media="print" onload="this.media='all'">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<noscript><link rel="stylesheet" href="@asset('css/styles.css')"></noscript>

<!-- JSON-LD Structured Data for Travel Agency -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "Madagascar Green Tours",
        "url": "{{ $_ENV['APP_URL'] }}",
        "logo": "@asset('img/logos/logo_colored.png')",
        "description": "{{ $description }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ 'address' }}",
            "addressLocality": "Antananarivo",
            "addressRegion": "Vakinankaratra",
            "postalCode": "110",
            "addressCountry": "Madagascar"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "-18.8792",
            "longitude": "47.5079"
        },
        "telephone": "{{ $info->phone }}",
        "email": "{{ $info->email }}",
        "sameAs": [
            "https://www.facebook.com/Madagascar.Green.Tours",
            "https://www.instagram.com/madagascargreentours",
        ]
    }
    "@context": "https://schema.org",
    "@type": "TravelAgency",
    "name": "Madagascar Green Tours",
    "url": "{{ $_ENV['APP_URL'] }}",
    "logo": "@asset('img/logos/logo_220.png')",
    "description": "Eco-friendly travel experiences in Madagascar with sustainable tourism practices.",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ 'address' }}",
        "addressLocality": "Antananarivo",
        "addressRegion": "Vakinankaratra",
        "postalCode": "110",
        "addressCountry": "Madagascar"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-18.8792",
        "longitude": "47.5079"
    },
    "telephone": "{{ 'phone' }}",
    "email": "{{ 'email' }}",
    "sameAs": [
        "https://www.facebook.com/Madagascar.Green.Tours",
        "https://www.instagram.com/madagascargreentours",
    ],
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
            "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"
        ],
        "opens": "08:00",
        "closes": "17:00"
    }
}
</script>
<!-- Additional Structured Data: LocalBusiness -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Madagascar Green Tours",
    "image": "@asset('img/logos/logo_220.png')",
    "@id": "{{ $_ENV['APP_URL'] }}",
    "url": "{{ $_ENV['APP_URL'] }}",
    "telephone": "{{ 'phone' }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ 'address' }}",
        "addressLocality": "Antananarivo",
        "addressRegion": "Vakinankaratra",
        "postalCode": "110",
        "addressCountry": "MG"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": -18.8792,
        "longitude": 47.5079
    },
    "sameAs": [
        "https://www.facebook.com/Madagascar.Green.Tours",
        "https://www.instagram.com/madagascargreentours",
    ]
}
</script>
<!-- Additional Structured Data: Breadcrumb -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ $_ENV['APP_URL'] }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Our Tours",
      "item": "{{ url('tours') }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "Contact",
      "item": "{{ url('contact') }}"
    }
    
  ]
}
</script>
<!-- Accessibility/Contrast Note: All major text and background colors use #fff, #333, and #198754 (green), which have good contrast. Review section headings and buttons for contrast against backgrounds. -->
<!-- Mobile Performance Audit: Page uses responsive meta viewport, deferred scripts, lazy-loaded images, and CSS media queries for mobile. Test on real devices for touch target size and tap delay. -->
<style>
    /* Critical CSS: Navbar and Hero (mobile-first) */
    body{margin:0;padding:0}.navbar-brand img,.navbar-logo{width:120px;height:42px;max-width:100%}@media(min-width:769px){.navbar-brand img,.navbar-logo{width:220px;height:77px}}.navbar-toggler{border:1px solid #fff;margin-left:10px;padding:6px}#home{margin-top:0}@media(max-width:600px){.carousel-img{max-height:220px}}#iview-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);z-index:1}@media(max-width:425px){.float-image{width:100%}.flag{padding:.5rem .8rem!important}.card-styled{padding:2px;box-shadow:none}.card-styled-body h1{margin-top:10px}.card-styled-body{padding:8px}}@media(max-width:508px){.mobile-img{display:block}.desktop-img{display:none}.float-image{width:100%}}@media(max-width:575px){.title-slogan{font-size:1.5rem}#about{margin-top:8px!important;padding:0}}@media(max-width:766px) and (min-width:508px){.mobile-img{display:block}.desktop-img{display:none}.float-image{width:250px}}
</style>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16775408478"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-16775408478');
</script>
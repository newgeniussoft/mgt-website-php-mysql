<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title><!-- Deferred non-critical CSS -->
    @include('frontend.partials.head', ['title' => $title ?? 'Default Title'])
    @stack('styles')
</head>
<body>
    <main id="main-content">
    @include('frontend.partials.header')
    @yield('content')
    </main>
    @stack('scripts')
</body>
</html> 
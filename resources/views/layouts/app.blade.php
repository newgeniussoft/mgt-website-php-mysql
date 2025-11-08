<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>{{ $title ?? 'My App' }}</title>
</head>
<body>
    @include('components.header')
    
    <main>
        @yield('content')
    </main>
    
    <footer>
        <p>&copy; 2024 My App</p>
    </footer>
</body>
</html>
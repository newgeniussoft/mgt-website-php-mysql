@extends('layout')

@section('content')
    <h1>Welcome Page</h1>
@endsection

@push('styles')
    <link rel="stylesheet" href="page-specific.css">
@endpush

@push('scripts')
    <script src="jquery.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Page loaded');
        });
    </script>
@endpush
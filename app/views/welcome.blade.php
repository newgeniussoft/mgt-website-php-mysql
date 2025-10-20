@extends('frontend.layout')

@section('title', 'Welcome Page')
@push('styles')
    <link rel="stylesheet" href="page-specific.css">
@endpush
@section('content')
    <h1>{{ $title }} & {{ $language }}</h1>
    <h4>{{ trans('messages.success') }}</h4>
<a href="@route('tours/windsurf')">Home</a>
<img src="@assets('logo.png')" alt="Logo">
<a href="@url('contact')">Contact</a>

<a href="@set_language('es')">Español</a>
<a href="@set_language('en')">English</a>

    @forelse($users as $user)
        <div>{{ $user['name'] }} - {{ $user['email'] }}</div>
    @empty
        <p>No users found</p>
    @endforelse
@endsection


@push('scripts')
    <script>console.log('Hello');</script>
@endpush
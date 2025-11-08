@extends('layouts.client')

@section('content')
    <h1>{{ $title }}</h1>
    <h2>{{ $greeting }}</h2>
    
    @if(count($users) > 0)
        <ul>
            @foreach($users as $user)
                <li>{{ $user->name }} - {{ $user->email }}</li>
            @endforeach
        </ul>
    @else
        <p>No users found.</p>
    @endif
    
    @auth
        <p>Welcome back, {{ auth()->user()->name }}!</p>
    @endauth
    
    @guest
        <a href="/login">Login</a>
    @endguest
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JobBridge – @yield('title', 'Find Your Next Opportunity')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        @auth
        <a href="{{ route('home') }}" class="nav-logo">Job<span>Bridge</span></a>
        @else
        <a href="{{ route('login') }}" class="nav-logo">Job<span>Bridge</span></a>
        @endauth        
        <div class="nav-links">
            @auth
                <a href="{{ route('profile') }}" class="nav-link">My Profile</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-nav">Sign Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn-nav-solid">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container" style="margin-top:16px">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
</div>

@yield('content')

@stack('scripts')
</body>
</html>

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
                <div class="hamburger-menu" id="hamburgerMenu">
                    <button class="hamburger-btn" onclick="toggleMenu()" id="hamburgerBtn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="dropdown" id="dropdownMenu">
                        <a href="{{ route('profile') }}" class="dropdown-item">My Profile</a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-btn">Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn-nav-solid">Sign Up Free</a>
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

<script>
function toggleMenu() {
    const dropdown = document.getElementById('dropdownMenu');
    const btn      = document.getElementById('hamburgerBtn');
    dropdown.classList.toggle('open');
    btn.classList.toggle('active');
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('hamburgerMenu');
    if (menu && !menu.contains(e.target)) {
        document.getElementById('dropdownMenu').classList.remove('open');
        document.getElementById('hamburgerBtn').classList.remove('active');
    }
});
</script>
</body>
</html>

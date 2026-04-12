<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JobBridge – @yield('title', 'Find Your Next Opportunity')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
                    <a href="{{ route('chat.index') }}"
                        style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid #e5e7eb; background:#fff; margin-right:8px; text-decoration:none;">
                        <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                    </a>

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
            <div id="alert-success"
                style="background:#EAF3DE; border:1px solid #C0DD97; border-radius:8px; padding:10px 16px; font-size:13px; color:#3B6D11; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('alert-success').style.display='none';"
                    style="background:none; border:none; cursor:pointer; color:#3B6D11; font-size:18px; line-height:1; padding:0 0 0 12px;">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div id="alert-error"
                style="background:#FCEBEB; border:1px solid #F7C1C1; border-radius:8px; padding:10px 16px; font-size:13px; color:#A32D2D; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('alert-error').style.display='none';"
                    style="background:none; border:none; cursor:pointer; color:#A32D2D; font-size:18px; line-height:1; padding:0 0 0 12px;">&times;</button>
            </div>
        @endif
        @if(session('info'))
            <div id="alert-info"
                style="background:#E6F1FB; border:1px solid #B5D4F4; border-radius:8px; padding:10px 16px; font-size:13px; color:#185FA5; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
                <span>{{ session('info') }}</span>
                <button onclick="document.getElementById('alert-info').style.display='none';"
                    style="background:none; border:none; cursor:pointer; color:#185FA5; font-size:18px; line-height:1; padding:0 0 0 12px;">&times;</button>
            </div>
        @endif
    </div>

    @yield('content')

    @stack('scripts')

    <script>
        function toggleMenu() {
            const dropdown = document.getElementById('dropdownMenu');
            const btn = document.getElementById('hamburgerBtn');
            dropdown.classList.toggle('open');
            btn.classList.toggle('active');
        }

        document.addEventListener('click', function (e) {
            const menu = document.getElementById('hamburgerMenu');
            if (menu && !menu.contains(e.target)) {
                document.getElementById('dropdownMenu').classList.remove('open');
                document.getElementById('hamburgerBtn').classList.remove('active');
            }
        });
    </script>
</body>

</html>
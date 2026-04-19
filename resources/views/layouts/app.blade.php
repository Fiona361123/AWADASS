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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}"> @stack('styles')
</head>

<body>

    <nav class="jb-navbar">
        <div class="jb-nav-inner">
            @auth
                <a href="{{ route('home') }}" class="jb-nav-logo">Job<span>Bridge</span></a>
            @else
                <a href="{{ route('landing') }}" class="jb-nav-logo">Job<span>Bridge</span></a>
            @endauth

            <div class="jb-nav-links">
                @auth
                
                {{-- Notification Bell (employers only) --}}
                @if(auth()->user()->isEmployer())
                    @php
                        $newApplications = \App\Models\Application::whereHas('jobPosting', function ($q) {
                            $q->where('employer_id', auth()->id());
                        })->where('status', 'pending')->count();

                        $recentApplications = \App\Models\Application::with(['jobPosting', 'user'])
                            ->whereHas('jobPosting', function ($q) {
                                $q->where('employer_id', auth()->id());
                            })
                            ->where('status', 'pending')
                            ->latest('applied_at')
                            ->take(5)
                            ->get();
                    @endphp

                    <div style="position:relative; display:inline-flex; margin-right:8px;" id="notifWrap">
                        <button id="notifBtn"
                            style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid #e5e7eb; background:#fff; cursor:pointer; position:relative;">
                            <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                            @if($newApplications > 0)
                                <span
                                    style="position:absolute; top:-4px; right:-4px; background:#A32D2D; color:#fff; font-size:10px; font-weight:700; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    {{ $newApplications > 9 ? '9+' : $newApplications }}
                                </span>
                            @endif
                        </button>

                        <div id="notifDropdown"
                            style="display:none; position:absolute; right:0; top:42px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 4px 16px rgba(0,0,0,0.08); min-width:280px; z-index:999; overflow:hidden;">
                            <div
                                style="padding:12px 16px; border-bottom:1px solid #f3f4f6; font-size:13px; font-weight:600; color:#1a1a2e;">
                                New Applications
                                @if($newApplications > 0)
                                    <span
                                        style="background:#A32D2D; color:#fff; font-size:11px; padding:1px 7px; border-radius:99px; margin-left:6px;">{{ $newApplications }}</span>
                                @endif
                            </div>
                            @forelse($recentApplications as $app)
                                <a href="{{ route('jobs.applications', $app->jobPosting) }}"
                                    style="display:flex; align-items:flex-start; gap:10px; padding:12px 16px; text-decoration:none; border-bottom:1px solid #f3f4f6;">
                                    <div
                                        style="width:32px; height:32px; border-radius:50%; background:#E6F1FB; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#3B4FD8; flex-shrink:0;">
                                        {{ strtoupper(substr($app->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:13px; font-weight:500; color:#1a1a2e;">
                                            {{ $app->user->name ?? 'Someone' }}</div>
                                        <div style="font-size:12px; color:#6b7280; margin-top:2px;">Applied for
                                            <strong>{{ $app->jobPosting->title }}</strong></div>
                                        <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                                            {{ \Carbon\Carbon::parse($app->applied_at)->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @empty
                                <div style="padding:24px 16px; text-align:center; font-size:13px; color:#6b7280;">
                                    No new applications
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

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
                            @if(auth()->user()->role == 'seeker')
                                <a href="{{ route('jobs.index') }}" class="dropdown-item">View Job Openings</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('applications.index') }}" class="dropdown-item">Application History</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item 
                                    dropdown-item-btn">Sign Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="jb-nav-link">Login</a>
                    <a href="{{ route('register') }}" class="jb-btn-nav-solid">Sign Up Free</a>
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

    <script src="{{ mix('js/app.js') }}" defer></script>

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

    {{-- Notification bell script --}}
    <script>
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifBtn) {
            notifBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notifDropdown.style.display = notifDropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                const notifWrap = document.getElementById('notifWrap');
                if (notifWrap && !notifWrap.contains(e.target)) {
                    notifDropdown.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
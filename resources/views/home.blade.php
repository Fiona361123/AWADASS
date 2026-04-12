@extends('layouts.app')
@section('title', 'Welcome to JobBridge')

@section('content')

{{-- Hero --}}
<div style="background: #3B4FD8; padding: 64px 24px 56px; text-align: center;">
    <div style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.15); color:#fff; font-size:12px; font-weight:500; padding:4px 12px; border-radius:99px; margin-bottom:20px;">
        <span style="width:6px;height:6px;border-radius:50%;background:#4ade80;display:inline-block;"></span>
        Now hiring across Malaysia
    </div>
    <h1 style="font-size:36px; font-weight:700; color:#fff; line-height:1.2; margin-bottom:12px;">
        Find your next <span style="color:#93c5fd;">dream job</span><br>or hire great talent
    </h1>
    <p style="font-size:15px; color:rgba(255,255,255,0.75); margin-bottom:32px; max-width:480px; margin-left:auto; margin-right:auto;">
        JobBridge connects job seekers with top employers across Malaysia. Sign up free and get started today.
    </p>
    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
        <a href="{{ route('register') }}"
            style="background:#fff; color:#3B4FD8; padding:11px 24px; border-radius:8px; font-size:14px; font-weight:600; text-decoration:none;">
            Get started free
        </a>
        <a href="{{ route('login') }}"
            style="background:transparent; color:#fff; padding:11px 24px; border-radius:8px; font-size:14px; font-weight:600; text-decoration:none; border:1.5px solid rgba(255,255,255,0.5);">
            Sign in
        </a>
    </div>
    <div style="display:flex; justify-content:center; gap:40px; margin-top:40px; flex-wrap:wrap;">
        <div><div style="font-size:22px; font-weight:700; color:#fff;">1,200+</div><div style="font-size:12px; color:rgba(255,255,255,0.6); margin-top:2px;">Jobs posted</div></div>
        <div><div style="font-size:22px; font-weight:700; color:#fff;">850+</div><div style="font-size:12px; color:rgba(255,255,255,0.6); margin-top:2px;">Companies</div></div>
        <div><div style="font-size:22px; font-weight:700; color:#fff;">5,000+</div><div style="font-size:12px; color:rgba(255,255,255,0.6); margin-top:2px;">Job seekers</div></div>
    </div>
</div>

{{-- Featured Jobs --}}
<div style="background:#f0f2fa; padding:48px 24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; max-width:900px; margin-left:auto; margin-right:auto;">
        <span style="font-size:18px; font-weight:700; color:#1a1a2e;">Featured job openings</span>
        <a href="{{ route('register') }}" style="font-size:13px; color:#3B4FD8; font-weight:500; text-decoration:none;">Sign up to see all →</a>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:16px; max-width:900px; margin:0 auto;">
        @forelse($featuredJobs as $job)
        <div style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div style="width:40px; height:40px; border-radius:10px; background:#E6F1FB; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700; color:#3B4FD8; flex-shrink:0;">
                    {{ strtoupper(substr($job->employer->name ?? 'J', 0, 1)) }}
                </div>
                <span style="font-size:11px; font-weight:600; padding:3px 8px; border-radius:99px; background:#EAF3DE; color:#3B6D11;">Open</span>
            </div>
            <div style="font-size:15px; font-weight:600; color:#1a1a2e; margin-bottom:4px;">{{ $job->title }}</div>
            <div style="font-size:13px; color:#6b7280; margin-bottom:12px;">{{ $job->location ?? 'Malaysia' }}</div>
            <div style="margin-top:16px; padding-top:14px; border-top:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:13px; font-weight:600; color:#1a1a2e;">
                    @if($job->salary_min || $job->salary_max)
                        RM {{ number_format($job->salary_min) }} – {{ number_format($job->salary_max) }}
                    @else
                        Negotiable
                    @endif
                </span>
                {{-- Blur the apply button to tease login --}}
                <a href="{{ route('login') }}"
                    style="font-size:12px; font-weight:600; color:#3B4FD8; background:#E6F1FB; padding:5px 12px; border-radius:6px; text-decoration:none;">
                    Sign in to apply
                </a>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1; text-align:center; padding:48px; color:#6b7280; font-size:14px;">
            No jobs posted yet — check back soon!
        </div>
        @endforelse
    </div>

    {{-- Teaser CTA --}}
    <div style="text-align:center; margin-top:32px;">
        <p style="font-size:14px; color:#6b7280; margin-bottom:12px;">Want to see more jobs or apply?</p>
        <a href="{{ route('register') }}"
            style="background:#3B4FD8; color:#fff; padding:11px 28px; border-radius:8px; font-size:14px; font-weight:600; text-decoration:none;">
            Create a free account
        </a>
    </div>
</div>

{{-- For who --}}
<div style="background:#fff; padding:48px 24px;">
    <div style="text-align:center; margin-bottom:28px;">
        <span style="font-size:18px; font-weight:700; color:#1a1a2e;">Who is JobBridge for?</span>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; max-width:900px; margin:0 auto;">
        <div style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:24px;">
            <div style="width:44px; height:44px; border-radius:10px; background:#E6F1FB; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
                <svg width="22" height="22" fill="none" stroke="#185FA5" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </div>
            <div style="font-size:16px; font-weight:700; color:#1a1a2e; margin-bottom:8px;">For job seekers</div>
            <div style="font-size:13px; color:#6b7280; line-height:1.6; margin-bottom:16px;">Browse hundreds of open roles, filter by location or salary, and apply directly to top employers across Malaysia.</div>
            <a href="{{ route('register') }}" style="display:inline-block; font-size:13px; font-weight:600; padding:9px 18px; border-radius:8px; text-decoration:none; background:#E6F1FB; color:#185FA5;">
                Sign up as job seeker →
            </a>
        </div>
        <div style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; padding:24px;">
            <div style="width:44px; height:44px; border-radius:10px; background:#EAF3DE; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
                <svg width="22" height="22" fill="none" stroke="#3B6D11" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
            </div>
            <div style="font-size:16px; font-weight:700; color:#1a1a2e; margin-bottom:8px;">For employers</div>
            <div style="font-size:13px; color:#6b7280; line-height:1.6; margin-bottom:16px;">Post your job openings, manage applications, and find the right candidate faster with our simple employer dashboard.</div>
            <a href="{{ route('register') }}" style="display:inline-block; font-size:13px; font-weight:600; padding:9px 18px; border-radius:8px; text-decoration:none; background:#EAF3DE; color:#3B6D11;">
                Sign up as employer →
            </a>
        </div>
    </div>
</div>

{{-- Footer --}}
<div style="background:#1a1a2e; padding:32px 24px; text-align:center;">
    <div style="font-size:18px; font-weight:700; color:#fff; margin-bottom:8px;">Job<span style="color:#93c5fd;">Bridge</span></div>
    <div style="font-size:13px; color:rgba(255,255,255,0.4);">© {{ date('Y') }} JobBridge. Connecting talent with opportunity.</div>
</div>

@endsection
@extends('layouts.app')

@section('title', 'Available Jobs')

@section('content')

<div style="background:#f0f2fa; padding:48px 24px; min-height:100vh;">

    {{-- HEADER --}}
    <div style="max-width:900px; margin:0 auto 24px;">
        <h1 style="font-size:22px; font-weight:700; color:#1a1a2e;">
            Available Jobs
        </h1>
        <p style="font-size:13px; color:#6b7280;">
            Browse and apply to open positions
        </p>
    </div>

    <form action="{{ route('jobs.index') }}" method="GET"
        style="max-width:900px; margin:0 auto 20px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <div style="flex:1; min-width:240px; position:relative;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search jobs by title, location, or keyword"
                style="width:100%; padding:12px 14px; border-radius:10px; border:1px solid #d1d5db; background:#fff; font-size:13px; color:#1f2937; outline:none;"
            />
        </div>

        <button
            type="submit"
            style="background:#3B4FD8; color:#fff; padding:12px 16px; border-radius:10px; border:none; font-size:13px; font-weight:600; cursor:pointer;"
        >
            Search
        </button>

        @if(request()->filled('search'))
            <a href="{{ route('jobs.index') }}"
                style="background:#fff; color:#374151; padding:12px 16px; border-radius:10px; border:1px solid #d1d5db; font-size:13px; font-weight:600; text-decoration:none;">
                Clear
            </a>
        @endif
    </form>

    {{-- JOB LIST --}}
    <div style="max-width:900px; margin:0 auto; display:flex; flex-direction:column; gap:12px;">

        @forelse($jobs as $job)

        <a href="{{ route('jobs.show', $job->id) }}"
            style="text-decoration:none; color:inherit; display:block;">

            <div style="background:#fff; border-radius:10px; border:1px solid #e5e7eb; padding:16px;
                display:flex; justify-content:space-between; align-items:center;
                transition:0.2s; cursor:pointer;">

                {{-- LEFT SIDE --}}
                <div>
                    <div style="font-size:15px; font-weight:600; color:#1a1a2e;">
                        {{ $job->title }}
                    </div>

                    <div style="font-size:13px; color:#6b7280;">
                        {{ $job->location ?? 'Malaysia' }}
                    </div>

                    <div style="font-size:13px; font-weight:600;">
                        RM {{ $job->salary_min }} - {{ $job->salary_max }}
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                @if($job->status === 'open')
                    <span style="font-size:11px; font-weight:600; padding:4px 10px; border-radius:999px; background:#EAF3DE; color:#3B6D11;">
                        Open
                    </span>
                @else
                    <span style="font-size:11px; font-weight:600; padding:4px 10px; border-radius:999px; background:#FDE2E2; color:#B91C1C;">
                        Closed
                    </span>
                @endif

            </div>

        </a>

        @empty
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:18px; color:#6b7280; font-size:14px;">
            {{ request()->filled('search') ? 'No jobs matched your search.' : 'No jobs available' }}
        </div>
        @endforelse
    </div>

</div>

@endsection
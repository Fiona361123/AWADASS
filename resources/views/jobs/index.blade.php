@extends('layouts.app')

@section('title', 'Available Jobs')

@section('content')

<div style="background:#f0f2fa; padding:48px 24px; min-height:100vh;">

    {{-- HEADER --}}
    <div style="max-width:900px; margin:0 auto; margin-bottom:24px;">
        <h1 style="font-size:22px; font-weight:700; color:#1a1a2e;">
            Available Jobs
        </h1>
        <p style="font-size:13px; color:#6b7280;">
            Browse and apply to open positions
        </p>
    </div>

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
                <span style="font-size:11px; font-weight:600; padding:4px 10px; border-radius:999px;
            background:{{ $job->status === 'open' ? '#EAF3DE' : '#FDE2E2' }};
            color:{{ $job->status === 'open' ? '#3B6D11' : '#B91C1C' }};">
                    {{ ucfirst($job->status) }}
                </span>

            </div>

        </a>

        @empty
        <div>No jobs available</div>
        @endforelse
    </div>

</div>

@endsection
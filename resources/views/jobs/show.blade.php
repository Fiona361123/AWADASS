@extends('layouts.app')

@section('title', $job->title)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/base.css') }}">
@endpush

@section('content')


<div style="background:#f0f2fa; min-height:100vh; padding:48px 24px;">

    <div style="max-width:800px; margin:0 auto;">

        {{-- BACK --}}
        <a href="{{ route('jobs.index') }}"
            style="display:inline-block; margin-bottom:16px; color:#3B4FD8;">
            ← Back
        </a>

        <div style="background:#fff; padding:24px; border-radius:12px; border:1px solid #e5e7eb;">
            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:18px;">

                {{-- Company Name --}}
                <h2 style="font-size:18px; font-weight:700; color:#1a1a2e; margin:0 0 6px;">
                    {{ $job->employer->employerProfile->company_name ?? 'No Company' }}
                </h2>

                {{-- Industry + Size --}}
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
                    <span style="background:#e6f1fb; color:#3B4FD8; padding:4px 10px; border-radius:999px; font-size:13px; font-weight:600;">
                        {{ $job->employer->employerProfile->industry ?? '-' }}
                    </span>
                    <span style="background:#f3f4f6; color:#6b7280; padding:4px 10px; border-radius:999px; font-size:13px;">
                        <span style="display:inline-flex; align-items:center; gap:6px;">
                            <svg
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <path d="M20 8v6" />
                                <path d="M23 11h-6" />
                            </svg>
                            <span>{{ $job->employer->employerProfile->company_size ?? '-' }}</span>
                        </span>
                    </span>
                </div>

                {{-- Website + Chat Button (same row) --}}
                <div style="border-top:1px solid #e5e7eb; padding-top:12px; display:flex; align-items:center; justify-content:space-between;">

                    @if($job->employer->employerProfile->website)
                    <a href="{{ $job->employer->employerProfile->website }}"
                        target="_blank"
                        style="font-size:13px; font-weight:600; color:#3B4FD8; text-decoration:none;">
                        Visit Company Website →
                    </a>
                    @else
                    <span></span>
                    @endif

                    @auth
                    <form method="POST" action="{{ route('chat.start') }}">
                        @csrf
                        <input type="hidden" name="employer_id" value="{{ $job->employer_id }}">
                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                        <button type="submit"
                            style="background:#10b981; color:#fff; padding:8px 14px; border-radius:8px; border:none; font-size:13px; font-weight:600; cursor:pointer;">
                            💬 Chat with Employer
                        </button>
                    </form>
                    @endauth

                </div>

            </div>

            <div style="padding: 1em;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">

                    <h1 style="font-size:22px; font-weight:700; margin:0; color:#1a1a2e;">
                        {{ $job->title }}
                    </h1>

                    <span style="
                    font-size:11px;
                    font-weight:600;
                    padding:4px 10px;
                    border-radius:999px; 
                    background:{{ $job->status === 'open' ? '#EAF3DE' : '#FDE2E2' }};
                    color:{{ $job->status === 'open' ? '#3B6D11' : '#B91C1C' }};
                    white-space:nowrap;">
                        {{ ucfirst($job->status) }}
                    </span>

                </div>

                {{-- LOCATION + STATUS --}}
                <div style="display:flex; gap:10px; align-items:center; margin-bottom:16px;">
                    <span style="font-size:13px; color:#6b7280;">
                        Location: {{ $job->location ?? 'Malaysia' }}
                    </span>
                </div>

                {{-- SALARY --}}
                <div style="font-size:14px; font-weight:600; margin-bottom:20px;">
                    @if($job->salary_min || $job->salary_max)
                    Salary: RM {{ number_format($job->salary_min) }} – {{ number_format($job->salary_max) }}
                    @else
                    Salary: Negotiable
                    @endif
                </div>

                {{-- DESCRIPTION --}}
                <div style="margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:700;">Job Description</h3>
                    <p style="font-size:13px; color:#4b5563; line-height:1.6;">
                        {{ $job->description }}
                    </p>
                </div>

                {{-- REQUIREMENTS --}}
                <div style="margin-bottom:24px;">
                    <h3 style="font-size:15px; font-weight:700;">Requirements</h3>
                    <p style="font-size:13px; color:#4b5563; white-space:pre-line;">
                        {{ $job->requirements }}
                    </p>
                </div>

                {{-- REACT DATA --}}
                <script>
                    window.jobData = @json($job);
                    window.isApplied = @json($isApplied);
                    window.defaultResume = @json(optional(auth()->user()->seekerProfile)->resume_path);
                </script>

                {{-- APPLY BUTTON (REACT) --}}
                <div id="job-apply"></div>
            </div>
        </div>

    </div>

</div>

@endsection
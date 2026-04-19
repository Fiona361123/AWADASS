@extends('layouts.app')

@section('content')
    <div style="background: #f0f2fa; min-height: 100vh; padding: 32px 24px; display: flex; justify-content: center;">
        <div style="width: 100%; max-width: 640px; height: fit-content;">

            {{-- Success Alert --}}
            @if (session('success'))
                <div
                    style="background: #EAF3DE; border: 1px solid #C0DD97; border-radius: 8px; padding: 10px 16px; font-size: 13px; color: #3B6D11; margin-bottom: 16px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Main Card --}}
            <div style="background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden;">

                {{-- Card Header --}}
                <div style="padding: 24px 28px; border-bottom: 1px solid #f3f4f6;">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                        <h2 style="font-size: 20px; font-weight: 600; color: #1a1a2e;">{{ $job->title }}</h2>

                        @if($job->status === 'open')
                            <span
                                style="display:inline-flex; align-items:center; gap:5px; background:#EAF3DE; color:#3B6D11; padding:4px 12px; border-radius:99px; font-size:12px; font-weight:500;">
                                <span
                                    style="width:6px; height:6px; border-radius:50%; background:#639922; display:inline-block;"></span>
                                Open
                            </span>
                        @else
                            <span
                                style="display:inline-flex; align-items:center; gap:5px; background:#f3f4f6; color:#6b7280; padding:4px 12px; border-radius:99px; font-size:12px; font-weight:500;">
                                <span
                                    style="width:6px; height:6px; border-radius:50%; background:#9ca3af; display:inline-block;"></span>
                                Closed
                            </span>
                        @endif
                    </div>

                    {{-- Meta row --}}
                    <div style="display: flex; gap: 20px; margin-top: 12px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span
                                style="font-size:13px; color:#6b7280;">{{ $job->location ?? 'Remote / Unspecified' }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                            <span style="font-size:13px; color:#6b7280;">
                                @if($job->salary_min || $job->salary_max)
                                    RM {{ number_format($job->salary_min) }} – RM {{ number_format($job->salary_max) }}
                                @else
                                    Negotiable
                                @endif
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            <span style="font-size:13px; color:#6b7280;">Posted
                                {{ $job->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div style="padding: 24px 28px;">

                    {{-- Job Description --}}
                    <div style="margin-bottom: 24px;">
                        <h4
                            style="font-size: 14px; font-weight: 600; color: #1a1a2e; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Job Description</h4>
                        <div
                            style="background: #f8f9fc; border-radius: 10px; padding: 16px; font-size: 14px; color: #374151; line-height: 1.7; border: 1px solid #f3f4f6;">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    {{-- Requirements --}}
                    <div>
                        <h4
                            style="font-size: 14px; font-weight: 600; color: #1a1a2e; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Requirements</h4>
                        <div
                            style="background: #f8f9fc; border-radius: 10px; padding: 16px; font-size: 14px; color: #374151; line-height: 1.7; border: 1px solid #f3f4f6;">
                            {!! nl2br(e($job->requirements ?? 'None listed.')) !!}
                        </div>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div style="padding: 16px 28px; border-top: 1px solid #f3f4f6; display: flex; gap: 10px;">
                    <a href="{{ route('dashboard') }}"
                        style="padding: 9px 18px; border-radius: 8px; font-size: 14px; font-weight: 500; background: #E6F1FB; color: #185FA5; border: 1px solid #B5D4F4; text-decoration: none;">
                        ← Back to Dashboard
                    </a>
                    <a href="{{ route('jobposting.edit', $job) }}"
                        style="padding: 9px 18px; border-radius: 8px; font-size: 14px; font-weight: 500; background: #FAEEDA; color: #854F0B; border: 1px solid #FAC775; text-decoration: none;">
                        Edit Job
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
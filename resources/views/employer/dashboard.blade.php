@extends('layouts.app')

@section('content')
    <div style="background: #f0f2fa; min-height: 100vh; padding: 32px 24px;">

        {{-- Page Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 600; color: #1a1a2e;">My Job Postings</h1>
            <a href="{{ route('jobs.create') }}"
                style="background: #3B4FD8; color: #fff; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none;">
                + Post New Job
            </a>
        </div>

        {{-- Table Card --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: visible;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: #f8f9fc; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Job Title</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Created On</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr style="border-bottom: 1px solid #f3f4f6;">

                            {{-- Title --}}
                            <td style="padding: 14px 16px; font-weight: 500; color: #1a1a2e;">
                                {{ $job->title }}
                            </td>

                            {{-- Status Badge --}}
                            <td style="padding: 14px 16px;">
                                @if($job->status === 'open')
                                    <span style="display:inline-flex; align-items:center; gap:5px; background:#EAF3DE; color:#3B6D11; padding:3px 10px; border-radius:99px; font-size:12px; font-weight:500;">
                                        <span style="width:6px; height:6px; border-radius:50%; background:#639922; display:inline-block;"></span>
                                        Open
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:5px; background:#f3f4f6; color:#6b7280; padding:3px 10px; border-radius:99px; font-size:12px; font-weight:500;">
                                        <span style="width:6px; height:6px; border-radius:50%; background:#9ca3af; display:inline-block;"></span>
                                        Closed
                                    </span>
                                @endif
                            </td>

                            {{-- Created On --}}
                            <td style="padding: 14px 16px; color: #6b7280; font-size: 13px;">
                                {{ $job->created_at->format('Y-m-d') }}
                            </td>

                            {{-- Actions --}}
                            <td style="padding: 14px 16px; text-align: right;">
                                <div style="display:inline-flex; align-items:center; gap:8px; justify-content:flex-end;">

                                    {{-- Toggle Status --}}
                                    <form action="{{ route('jobs.toggleStatus', $job) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <label style="position:relative; display:inline-block; width:40px; height:22px; flex-shrink:0; cursor:pointer;" title="{{ $job->status === 'open' ? 'Click to close' : 'Click to open' }}">
                                            <input type="checkbox" {{ $job->status === 'open' ? 'checked' : '' }}
                                                onchange="this.closest('form').submit();"
                                                style="opacity:0; width:0; height:0;">
                                            <span style="position:absolute; inset:0; background:{{ $job->status === 'open' ? '#3B4FD8' : '#d1d5db' }}; border-radius:99px; transition:0.2s; display:block;">
                                                <span style="position:absolute; width:16px; height:16px; background:#fff; border-radius:50%; left:3px; top:3px; transition:0.2s; transform:{{ $job->status === 'open' ? 'translateX(18px)' : 'translateX(0)' }}; display:block;"></span>
                                            </span>
                                        </label>
                                    </form>

                                    {{-- Dots Dropdown --}}
                                    <div style="position:relative;">
                                        <button onclick="let d=this.nextElementSibling; document.querySelectorAll('.jb-dropdown').forEach(x=>x.style.display='none'); d.style.display=d.style.display==='block'?'none':'block';"
                                            style="width:32px; height:32px; border-radius:8px; border:1px solid #e5e7eb; background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:2px;">
                                            <span style="width:4px;height:4px;border-radius:50%;background:#6b7280;display:block;"></span>
                                            <span style="width:4px;height:4px;border-radius:50%;background:#6b7280;display:block;"></span>
                                            <span style="width:4px;height:4px;border-radius:50%;background:#6b7280;display:block;"></span>
                                        </button>
                                        <div class="jb-dropdown" style="display:none; position:absolute; right:0; top:38px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 4px 16px rgba(0,0,0,0.08); min-width:150px; z-index:99; overflow:hidden;">

                                            {{-- View --}}
                                            <a href="{{ route('jobs.show', $job) }}"
                                                style="display:flex; align-items:center; gap:8px; padding:10px 14px; font-size:13px; font-weight:500; text-decoration:none; color:#185FA5;">
                                                <svg width="14" height="14" fill="none" stroke="#185FA5" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                View
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('jobs.edit', $job) }}"
                                                style="display:flex; align-items:center; gap:8px; padding:10px 14px; font-size:13px; font-weight:500; text-decoration:none; color:#854F0B;">
                                                <svg width="14" height="14" fill="none" stroke="#854F0B" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                Edit
                                            </a>

                                            <div style="height:1px; background:#f3f4f6; margin:2px 0;"></div>

                                            {{-- Delete --}}
                                            <form action="{{ route('jobs.destroy', $job) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this job posting?')"
                                                    style="display:flex; align-items:center; gap:8px; width:100%; padding:10px 14px; font-size:13px; font-weight:500; background:none; border:none; cursor:pointer; color:#A32D2D; text-align:left;">
                                                    <svg width="14" height="14" fill="none" stroke="#A32D2D" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 48px 16px; text-align: center; color: #6b7280; font-size: 14px;">
                                You haven't posted any jobs yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 16px;">
            {{ $jobs->links() }}
        </div>

    </div>

    {{-- Dropdown close on outside click --}}
    <script>
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.jb-dropdown') && !e.target.closest('button')) {
                document.querySelectorAll('.jb-dropdown').forEach(d => d.style.display = 'none');
            }
        });
    </script>

@endsection
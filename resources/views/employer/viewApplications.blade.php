@extends('layouts.app')

@section('content')
    <div style="background: #f0f2fa; min-height: 100vh; padding: 32px 24px;">

        {{-- Header --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h1 style="font-size:22px; font-weight:600; color:#1a1a2e;">Applicants</h1>
                <p style="font-size:13px; color:#6b7280; margin-top:4px;">{{ $job->title }}</p>
            </div>
            <a href="{{ route('dashboard') }}"
                style="padding:9px 18px; border-radius:8px; font-size:14px; font-weight:500; background:#E6F1FB; color:#185FA5; border:1px solid #B5D4F4; text-decoration:none;">
                ← Back
            </a>
        </div>

        <div style="background:#fff; border-radius:12px; border:1px solid #e5e7eb; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f8f9fc; border-bottom:1px solid #e5e7eb;">
                        <th
                            style="padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">
                            Applicant</th>
                        <th
                            style="padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">
                            Email</th>
                        <th
                            style="padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">
                            Applied On</th>
                        <th
                            style="padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">
                            Status</th>
                        <th
                            style="padding:12px 16px; text-align:right; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">
                            Action</th>
                        <th
                            style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                            Documents</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <td style="padding:14px 16px; font-weight:500; color:#1a1a2e;">
                                {{ $application->user->name }}
                            </td>
                            <td style="padding:14px 16px; color:#6b7280; font-size:13px;">
                                {{ $application->user->email }}
                            </td>
                            <td style="padding:14px 16px; color:#6b7280; font-size:13px;">
                                {{ \Carbon\Carbon::parse($application->applied_at)->format('Y-m-d') }}
                            </td>
                            <td style="padding:14px 16px;">
                                @php $s = $application->status; @endphp
                                <span
                                    style="display:inline-flex; align-items:center; padding:3px 10px; border-radius:99px; font-size:12px; font-weight:500;
                                            background:{{ $s === 'accepted' ? '#EAF3DE' : ($s === 'rejected' ? '#FCEBEB' : ($s === 'reviewed' ? '#E6F1FB' : '#f3f4f6')) }};
                                            color:{{ $s === 'accepted' ? '#3B6D11' : ($s === 'rejected' ? '#A32D2D' : ($s === 'reviewed' ? '#185FA5' : '#6b7280')) }};">
                                    {{ ucfirst($s) }}
                                </span>
                            </td>
                            <td style="padding:14px 16px; text-align:right;">
                                <form action="{{ route('applications.updateStatus', $application) }}" method="POST"
                                    style="display:inline-flex; gap:6px;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                        style="padding:5px 8px; border-radius:6px; border:1px solid #d1d5db; font-size:12px; color:#1a1a2e; cursor:pointer;">
                                        <option value="pending" {{ $s === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="reviewed" {{ $s === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                        <option value="accepted" {{ $s === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="rejected" {{ $s === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <button type="submit"
                                        style="padding:5px 12px; border-radius:6px; font-size:12px; font-weight:500; background:#3B4FD8; color:#fff; border:none; cursor:pointer;">
                                        Update
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($application->documents->count() > 0)
                                    <div style="display:flex; flex-direction:column; gap:4px;">
                                        @foreach($application->documents as $doc)
                                            <a href="{{ $doc->url }}" target="_blank"
                                                style="display:inline-flex; align-items:center; gap:5px; font-size:12px; color:#185FA5; text-decoration:none;">
                                                <svg width="12" height="12" fill="none" stroke="#185FA5" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <polyline points="14 2 14 8 20 8" />
                                                </svg>
                                                Document {{ $loop->iteration }} .{{ pathinfo($doc->file_path, PATHINFO_EXTENSION) }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="font-size:12px; color:#9ca3af;">No documents</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:48px 16px; text-align:center; color:#6b7280; font-size:14px;">
                                No applicants yet for this job.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
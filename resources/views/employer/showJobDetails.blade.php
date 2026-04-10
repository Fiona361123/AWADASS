@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h2>{{ $job->title }}</h2>
            <span class="badge {{ $job->status === 'open' ? 'badge-success' : 'badge-secondary' }} align-self-center">
                {{ ucfirst($job->status) }}
            </span>
        </div>
        <div class="card-body">
            <p><strong>Location:</strong> {{ $job->location ?? 'Remote / Unspecified' }}</p>
            <p><strong>Salary:</strong> {{ $job->salary ?? 'Negotiable' }}</p>

            <h4 class="mt-4">Job Description</h4>
            <div class="bg-light p-3 rounded">
                {!! nl2br(e($job->description)) !!}
            </div>

            <h4 class="mt-4">Requirements</h4>
            <div class="bg-light p-3 rounded">
                {!! nl2br(e($job->requirements ?? 'None listed.')) !!}
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-warning">Edit Job</a>
        </div>
    </div>
</div>
@endsection

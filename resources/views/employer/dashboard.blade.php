@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Job Postings</h1>
        <a href="{{ route('jobs.create') }}" class="btn btn-primary">Post New Job</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>
                        <span class="badge {{ $job->status === 'open' ? 'badge-success' : 'badge-secondary' }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td>{{ $job->created_at->format('Y-m-d') }}</td>
                    <td class="text-right">
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-warning btn-sm">Edit</a>
                        
                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this job posting?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">You haven't posted any jobs yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $jobs->links() }}
    </div>
</div>
@endsection

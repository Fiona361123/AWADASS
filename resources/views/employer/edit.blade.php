@extends('layouts.app')

@section('content')
<div style="background: #f0f2fa; min-height: 100vh; padding: 32px 24px; display: flex; justify-content: center;">
  <div style="background: #fff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 32px; width: 100%; max-width: 640px; height: fit-content;">

    <div style="margin-bottom: 28px; padding-bottom: 20px; border-bottom: 1px solid #f3f4f6;">
      <h2 style="font-size: 20px; font-weight: 600; color: #1a1a2e;">Edit Job Posting</h2>
      <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Update the details for <strong>{{ $job->title }}</strong>.</p>
    </div>

    <form action="{{ route('jobs.update', $job) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- Job Title + Location --}}
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
        <div>
          <label for="title" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">
            Job Title <span style="color:#A32D2D;">*</span>
          </label>
          <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}" required
            style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid {{ $errors->has('title') ? '#A32D2D' : '#d1d5db' }}; font-size:14px; outline:none;">
          @error('title')
            <p style="font-size:12px; color:#A32D2D; margin-top:4px;">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="location" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">Location</label>
          <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}"
            style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #d1d5db; font-size:14px; outline:none;">
        </div>
      </div>

      {{-- Salary Min + Max --}}
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
        <div>
          <label for="salary_min" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">Salary Min (RM)</label>
          <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min', $job->salary_min) }}"
            placeholder="e.g. 3000"
            style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #d1d5db; font-size:14px; outline:none;">
        </div>
        <div>
          <label for="salary_max" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">Salary Max (RM)</label>
          <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max', $job->salary_max) }}"
            placeholder="e.g. 6000"
            style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #d1d5db; font-size:14px; outline:none;">
          <p style="font-size:12px; color:#6b7280; margin-top:4px;">Leave blank if not disclosed.</p>
        </div>
      </div>

      {{-- Job Description --}}
      <div style="margin-bottom: 18px;">
        <label for="description" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">
          Job Description <span style="color:#A32D2D;">*</span>
        </label>
        <textarea name="description" id="description" rows="5" required
          style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid {{ $errors->has('description') ? '#A32D2D' : '#d1d5db' }}; font-size:14px; font-family:inherit; resize:vertical; outline:none;">{{ old('description', $job->description) }}</textarea>
        @error('description')
          <p style="font-size:12px; color:#A32D2D; margin-top:4px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Requirements --}}
      <div style="margin-bottom: 18px;">
        <label for="requirements" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">Requirements</label>
        <textarea name="requirements" id="requirements" rows="4"
          style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #d1d5db; font-size:14px; font-family:inherit; resize:vertical; outline:none;">{{ old('requirements', $job->requirements) }}</textarea>
      </div>

      {{-- Status --}}
      <div style="margin-bottom: 24px;">
        <label for="status" style="display:block; font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:6px;">
          Status <span style="color:#A32D2D;">*</span>
        </label>
        <select name="status" id="status"
          style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid #d1d5db; font-size:14px; font-family:inherit; background:#fff; color:#1a1a2e; outline:none; cursor:pointer;">
          <option value="open"   {{ old('status', $job->status) === 'open'   ? 'selected' : '' }}>Open — visible to applicants</option>
          <option value="closed" {{ old('status', $job->status) === 'closed' ? 'selected' : '' }}>Closed — hidden from listings</option>
        </select>
      </div>

      {{-- Actions --}}
      <div style="display:flex; gap:10px;">
        <a href="{{ route('dashboard') }}"
          style="padding:11px 20px; border-radius:8px; font-size:14px; font-weight:500; background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; text-decoration:none; text-align:center;">
          Cancel
        </a>
        <button type="submit"
          style="flex:1; background:#3B4FD8; color:#fff; border:none; padding:11px 20px; border-radius:8px; font-size:14px; font-weight:500; cursor:pointer;">
          Update Job
        </button>
      </div>

    </form>
  </div>
</div>
@endsection
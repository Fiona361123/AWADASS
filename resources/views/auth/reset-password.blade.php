@extends('layouts.app')
@section('title', 'Reset Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
@endpush

@section('content')
<div class="auth-center">
    <div class="card">
        <div class="card-head">
            <h2>Set new password</h2>
            <p>Almost done — create a strong new password</p>
        </div>

        <div class="step-indicator">
            <div class="step-dot done"></div>
            <div class="step-dot done"></div>
            <div class="step-dot active"></div>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="form-body">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="you@example.com" required>
            </div>
            <div class="field">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Minimum 8 characters" required>
            </div>
            <div class="field">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat your new password" required>
            </div>

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>

        <div class="divider">or</div>

        <p class="text-center">
            <a href="{{ route('login') }}" class="link-text">← Back to Sign In</a>
        </p>
    </div>
</div>
@endsection

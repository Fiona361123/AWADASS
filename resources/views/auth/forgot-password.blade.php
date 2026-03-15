@extends('layouts.app')
@section('title', 'Forgot Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endpush

@section('content')
<div class="auth-center">
    <div class="card">
        <div class="card-head">
            <h2>Reset your password</h2>
            <p>Enter your email and we'll send you a reset link</p>
        </div>

        <div class="step-indicator">
            <div class="step-dot active"></div>
            <div class="step-dot"></div>
            <div class="step-dot"></div>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <p class="text-muted mb-20">Enter the email address linked to your account. We will send you a password reset link.</p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>

        <div class="divider">or</div>

        <p class="text-center">
            Remember your password?
            <a href="{{ route('login') }}" class="link-text">Back to Sign In</a>
        </p>
    </div>
</div>
@endsection

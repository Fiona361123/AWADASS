@extends('layouts.app')
@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="hero-section">
    <div class="hero-grid">

        <div class="hero-copy">
            <h1>Find your next <em>opportunity</em> or top talent</h1>
            <p>JobBridge connects ambitious professionals with companies that value their skills. Start your journey today.</p>
            <div class="hero-stats">
                <div class="stat">
                    <span class="stat-num">12K+</span>
                    <span class="stat-label">Open Roles</span>
                </div>
                <div class="stat">
                    <span class="stat-num">4.8K</span>
                    <span class="stat-label">Companies</span>
                </div>
                <div class="stat">
                    <span class="stat-num">98%</span>
                    <span class="stat-label">Match Rate</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Welcome back</h2>
                <p>Sign in to your account to continue</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="form-body">
                @csrf

                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-footer">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="link-text">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <div class="divider">or</div>

            <p class="text-center">
                Don't have an account?
                <a href="{{ route('register') }}" class="link-text">Sign up</a>
            </p>
        </div>

    </div>
</div>
@endsection

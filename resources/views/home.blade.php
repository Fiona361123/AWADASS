@extends('layouts.app')
@section('title', 'Home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="home-wrap">

    <div class="home-topbar">
        <a href="{{ route('profile') }}" class="profile-icon-btn" title="My Profile"></a>
        <a href="{{ route('chat.index') }}" class="chat-icon-btn" title="Messages">Chat</a>
    </div>

        <p>
            Home page
        </p>

</div>
@endsection

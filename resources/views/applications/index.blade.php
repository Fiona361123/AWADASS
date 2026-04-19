@extends('layouts.app')

@section('title', 'My Applications')

@section('content')

<div style="padding:40px; max-width:900px; margin:auto;">

    <h2 style="font-size:22px; font-weight:700; color:#1a1a2e; padding:10px 0;">
        My Applications
    </h2>

        <script>
            window.applicationsData = @json($applications);
        </script>

        <div id="applications-root"></div>

</div>

@endsection
@extends('layouts.app')
@section('title', 'My Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile-wrap">

    <div class="profile-banner"></div>

    <div class="profile-card">

        {{-- Avatar / Logo --}}
        @if($user->role === 'employer' && ($profile->logo_path ?? false))
            <div class="avatar-ring employer logo-ring">
                <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Logo">
            </div>
        @else
            <div class="avatar-ring {{ $user->role === 'employer' ? 'employer' : '' }}">
                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' ') ?: ' ', 1, 1)) }}
            </div>
        @endif

        {{-- Header row --}}
        <div class="profile-header-row">
            <div>
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-role {{ $user->role }}">
                    {{ $user->role === 'seeker' ? 'Job Seeker' : 'Employer' }}
                </div>
                @if($user->role === 'seeker' && ($profile->job_title ?? false))
                    <div class="profile-subtitle">{{ $profile->job_title }}</div>
                @elseif($user->role === 'employer' && ($profile->company_name ?? false))
                    <div class="profile-subtitle">{{ $profile->company_name }}</div>
                @endif
                <div class="member-since">Member since {{ $user->created_at->format('F Y') }}</div>
            </div>
            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline" style="width:auto;padding:8px 22px">Edit Profile</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-top:20px">{{ session('success') }}</div>
        @endif

        {{-- Completion bar --}}
        @php $completion = $profile ? $profile->completionPercentage() : 0; @endphp
        <div class="completion-wrap">
            <div class="completion-label">
                <span>Profile Completion</span>
                <span class="completion-pct">{{ $completion }}%</span>
            </div>
            <div class="completion-bar">
                <div class="completion-fill" style="width: {{ $completion }}%"></div>
            </div>
            @if($completion < 100)
                <div class="completion-hint">Complete your profile to stand out!</div>
            @endif
        </div>

        <div class="profile-sections">

            {{-- Contact --}}
            <div class="section-block">
                <h3>Contact Information</h3>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span>{{ $user->email }}</span>
                </div>
                @if($profile->phone ?? false)
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span>{{ $profile->phone }}</span>
                    </div>
                @endif
                @if($profile->location ?? false)
                    <div class="info-row">
                        <span class="info-label">Location</span>
                        <span>{{ $profile->location }}</span>
                    </div>
                @endif
                @if($user->role === 'employer' && ($profile->address ?? false))
                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <span>{{ $profile->address }}</span>
                    </div>
                @endif
                @if($user->role === 'employer' && ($profile->website ?? false))
                    <div class="info-row">
                        <span class="info-label">Website</span>
                        <a href="{{ $profile->website }}" target="_blank" class="link-text">{{ $profile->website }}</a>
                    </div>
                @endif
            </div>

            @if($user->role === 'seeker')

                {{-- Professional Info --}}
                <div class="section-block">
                    <h3>Professional Info</h3>
                    @if($profile->job_title ?? false)
                        <div class="info-row">
                            <span class="info-label">Job Title</span>
                            <span>{{ $profile->job_title }}</span>
                        </div>
                    @endif
                    @if($profile->expected_salary ?? false)
                        <div class="info-row">
                            <span class="info-label">Expected Salary</span>
                            <span>{{ $profile->expected_salary }}</span>
                        </div>
                    @endif
                    @if($profile->bio ?? false)
                        <div class="info-row">
                            <span class="info-label">Bio</span>
                            <span>{{ $profile->bio }}</span>
                        </div>
                    @endif
                    @if($profile->resume_path ?? false)
                        <div class="info-row">
                            <span class="info-label">Resume</span>
                            <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="link-text">📄 View Resume</a>
                        </div>
                    @endif
                </div>

                {{-- Education --}}
                @if(($profile->education_university ?? false) || ($profile->education_degree ?? false))
                    <div class="section-block">
                        <h3>Education</h3>
                        @if($profile->education_university ?? false)
                            <div class="info-row">
                                <span class="info-label">University</span>
                                <span>{{ $profile->education_university }}</span>
                            </div>
                        @endif
                        @if($profile->education_degree ?? false)
                            <div class="info-row">
                                <span class="info-label">Degree</span>
                                <span>{{ $profile->education_degree }}</span>
                            </div>
                        @endif
                        @if($profile->education_year ?? false)
                            <div class="info-row">
                                <span class="info-label">Graduation Year</span>
                                <span>{{ $profile->education_year }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Work Experience --}}
                @if(($profile->work_company ?? false) || ($profile->work_position ?? false))
                    <div class="section-block">
                        <h3>Work Experience</h3>
                        @if($profile->work_company ?? false)
                            <div class="info-row">
                                <span class="info-label">Company</span>
                                <span>{{ $profile->work_company }}</span>
                            </div>
                        @endif
                        @if($profile->work_position ?? false)
                            <div class="info-row">
                                <span class="info-label">Position</span>
                                <span>{{ $profile->work_position }}</span>
                            </div>
                        @endif
                        @if($profile->work_years ?? false)
                            <div class="info-row">
                                <span class="info-label">Years</span>
                                <span>{{ $profile->work_years }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Skills --}}
                @if($profile->skills ?? false)
                    <div class="section-block full-width">
                        <h3>Skills</h3>
                        <div class="skills-list">
                            @foreach(explode(',', $profile->skills) as $skill)
                                <span class="skill-tag">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Languages --}}
                @if($profile->languages ?? false)
                    <div class="section-block full-width">
                        <h3>Languages</h3>
                        <div class="skills-list">
                            @foreach(explode(',', $profile->languages) as $lang)
                                <span class="skill-tag">{{ trim($lang) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

            @else

                {{-- Company Info --}}
                <div class="section-block">
                    <h3>Company Info</h3>
                    @if($profile->company_name ?? false)
                        <div class="info-row">
                            <span class="info-label">Company</span>
                            <span>{{ $profile->company_name }}</span>
                        </div>
                    @endif
                    @if($profile->industry ?? false)
                        <div class="info-row">
                            <span class="info-label">Industry</span>
                            <span>{{ ucfirst($profile->industry) }}</span>
                        </div>
                    @endif
                    @if($profile->company_size ?? false)
                        <div class="info-row">
                            <span class="info-label">Company Size</span>
                            <span>{{ $profile->company_size }} employees</span>
                        </div>
                    @endif
                    @if($profile->year_established ?? false)
                        <div class="info-row">
                            <span class="info-label">Founded</span>
                            <span>{{ $profile->year_established }}</span>
                        </div>
                    @endif
                    @if($profile->description ?? false)
                        <div class="info-row">
                            <span class="info-label">About</span>
                            <span>{{ $profile->description }}</span>
                        </div>
                    @endif
                </div>

                {{-- Social media --}}
                @if(($profile->linkedin ?? false) || ($profile->facebook ?? false) || ($profile->twitter ?? false))
                    <div class="section-block full-width">
                        <h3>Social Media</h3>
                        <div class="social-links">
                            @if($profile->linkedin ?? false)
                                <a href="{{ $profile->linkedin }}" target="_blank" class="social-btn linkedin">LinkedIn</a>
                            @endif
                            @if($profile->facebook ?? false)
                                <a href="{{ $profile->facebook }}" target="_blank" class="social-btn facebook">Facebook</a>
                            @endif
                            @if($profile->twitter ?? false)
                                <a href="{{ $profile->twitter }}" target="_blank" class="social-btn twitter">Twitter</a>
                            @endif
                        </div>
                    </div>
                @endif

            @endif

        </div>
    </div>
</div>
@endsection

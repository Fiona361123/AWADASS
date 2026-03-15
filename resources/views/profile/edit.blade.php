@extends('layouts.app')
@section('title', 'Edit Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile-wrap">

    <div class="profile-banner"></div>

    <div class="profile-card">

        <div class="avatar-ring {{ $user->role === 'employer' ? 'employer' : '' }}">
            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' ') ?: ' ', 1, 1)) }}
        </div>

        <div class="profile-header-row">
            <div>
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-role {{ $user->role }}">Editing Profile</div>
            </div>
            <a href="{{ route('profile') }}" class="btn btn-outline" style="width:auto;padding:8px 22px">← Cancel</a>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="margin-top:20px">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="margin-top:28px">
            @csrf
            @method('PUT')

            {{-- Account Details --}}
            <div class="section-block" style="margin-bottom:20px">
                <h3>Account Details</h3>
                <div class="field-row">
                    <div class="field">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label>Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+60 12-345 6789">
                    </div>
                    <div class="field">
                        <label>Location</label>
                        <input type="text" name="location" value="{{ old('location', $profile->location ?? '') }}" placeholder="Kuala Lumpur, MY">
                    </div>
                </div>
            </div>

            @if($user->role === 'seeker')

                {{-- Professional Details --}}
                <div class="section-block" style="margin-bottom:20px">
                    <h3>Professional Details</h3>
                    <div class="field-row">
                        <div class="field">
                            <label>Job Title</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $profile->job_title ?? '') }}" placeholder="e.g. Software Engineer">
                        </div>
                        <div class="field">
                            <label>Expected Salary</label>
                            <input type="text" name="expected_salary" value="{{ old('expected_salary', $profile->expected_salary ?? '') }}" placeholder="e.g. RM 3,000 - RM 5,000">
                        </div>
                    </div>
                    <div class="field">
                        <label>Bio</label>
                        <textarea name="bio" rows="3" placeholder="Tell employers about yourself...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Skills <small>(comma-separated)</small></label>
                            <input type="text" name="skills" value="{{ old('skills', $profile->skills ?? '') }}" placeholder="Laravel, React, MySQL">
                        </div>
                        <div class="field">
                            <label>Languages <small>(comma-separated)</small></label>
                            <input type="text" name="languages" value="{{ old('languages', $profile->languages ?? '') }}" placeholder="English, Bahasa Malaysia">
                        </div>
                    </div>
                    <div class="field">
                        <label>Resume / CV <small>(PDF only, max 2MB)</small></label>
                        <input type="file" name="resume" accept=".pdf">
                        @if($profile->resume_path ?? false)
                            <small>Current: <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="link-text">View Resume</a></small>
                        @endif
                    </div>
                </div>

                {{-- Education --}}
                <div class="section-block" style="margin-bottom:20px">
                    <h3>Education</h3>
                    <div class="field-row">
                        <div class="field">
                            <label>University</label>
                            <input type="text" name="education_university" value="{{ old('education_university', $profile->education_university ?? '') }}" placeholder="e.g. UTAR">
                        </div>
                        <div class="field">
                            <label>Degree</label>
                            <input type="text" name="education_degree" value="{{ old('education_degree', $profile->education_degree ?? '') }}" placeholder="e.g. Bachelor of Computer Science">
                        </div>
                    </div>
                    <div class="field">
                        <label>Graduation Year</label>
                        <input type="text" name="education_year" value="{{ old('education_year', $profile->education_year ?? '') }}" placeholder="e.g. 2025" style="max-width:200px">
                    </div>
                </div>

                {{-- Work Experience --}}
                <div class="section-block" style="margin-bottom:20px">
                    <h3>Work Experience</h3>
                    <div class="field-row">
                        <div class="field">
                            <label>Company</label>
                            <input type="text" name="work_company" value="{{ old('work_company', $profile->work_company ?? '') }}" placeholder="e.g. Google">
                        </div>
                        <div class="field">
                            <label>Position</label>
                            <input type="text" name="work_position" value="{{ old('work_position', $profile->work_position ?? '') }}" placeholder="e.g. Software Engineer">
                        </div>
                    </div>
                    <div class="field">
                        <label>Years</label>
                        <input type="text" name="work_years" value="{{ old('work_years', $profile->work_years ?? '') }}" placeholder="e.g. 2022 - 2024" style="max-width:200px">
                    </div>
                </div>

            @else

                {{-- Company Details --}}
                <div class="section-block" style="margin-bottom:20px">
                    <h3>Company Details</h3>
                    <div class="field-row">
                        <div class="field">
                            <label>Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name ?? '') }}">
                        </div>
                        <div class="field">
                            <label>Industry</label>
                            <select name="industry">
                                @foreach(['technology','finance','healthcare','education','retail','manufacturing','media','other'] as $ind)
                                    <option value="{{ $ind }}" {{ old('industry', $profile->industry ?? '') == $ind ? 'selected' : '' }}>{{ ucfirst($ind) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Company Size</label>
                            <select name="company_size">
                                @foreach(['1-10','11-50','51-200','201-500','500+'] as $size)
                                    <option value="{{ $size }}" {{ old('company_size', $profile->company_size ?? '') == $size ? 'selected' : '' }}>{{ $size }} employees</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Year Established</label>
                            <input type="text" name="year_established" value="{{ old('year_established', $profile->year_established ?? '') }}" placeholder="e.g. 2010">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Website</label>
                            <input type="url" name="website" value="{{ old('website', $profile->website ?? '') }}" placeholder="https://company.com">
                        </div>
                        <div class="field">
                            <label>Address</label>
                            <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" placeholder="123 Jalan Example, KL">
                        </div>
                    </div>
                    <div class="field">
                        <label>Company Description</label>
                        <textarea name="description" rows="3" placeholder="What does your company do?">{{ old('description', $profile->description ?? '') }}</textarea>
                    </div>
                    <div class="field">
                        <label>Company Logo <small>(JPG/PNG, max 2MB)</small></label>
                        <input type="file" name="logo" accept=".jpg,.jpeg,.png">
                        @if($profile->logo_path ?? false)
                            <small>Current: <img src="{{ asset('storage/' . $profile->logo_path) }}" style="height:30px;vertical-align:middle;margin-left:6px"></small>
                        @endif
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="section-block" style="margin-bottom:20px">
                    <h3>Social Media</h3>
                    <div class="field">
                        <label>LinkedIn</label>
                        <input type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}" placeholder="https://linkedin.com/company/...">
                    </div>
                    <div class="field">
                        <label>Facebook</label>
                        <input type="url" name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="field">
                        <label>Twitter</label>
                        <input type="url" name="twitter" value="{{ old('twitter', $profile->twitter ?? '') }}" placeholder="https://twitter.com/...">
                    </div>
                </div>

            @endif

            {{-- Change Password --}}
            <div class="section-block" style="margin-bottom:28px">
                <h3>Change Password <small style="font-weight:400;color:var(--muted)">(leave blank to keep current)</small></h3>
                <div class="field-row">
                    <div class="field">
                        <label>New Password</label>
                        <input type="password" name="password" placeholder="Minimum 8 characters">
                    </div>
                    <div class="field">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="max-width:220px">Save Changes</button>
        </form>

    </div>
</div>
@endsection

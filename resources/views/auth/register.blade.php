@extends('layouts.app')
@section('title', 'Create Account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="hero-section">
    <div class="hero-grid">

        <div class="hero-copy">
            <h1 id="heroCopy">Land your <em>dream job</em> faster</h1>
            <p id="heroSub">Create your profile once and let opportunities find you. Employers actively search for candidates like you.</p>
            <div class="checklist">
                <div class="check-item">Free to join — always</div>
                <div class="check-item">Verified employers only</div>
                <div class="check-item">Smart job matching</div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Create account</h2>
                <p>Step <span id="stepNum">1</span> of 2 — <span id="stepLabel">Basic information</span></p>
            </div>

            <div class="step-indicator">
                <div class="step-dot active" id="dot1"></div>
                <div class="step-dot" id="dot2"></div>
            </div>

            <div class="role-strip">
                <button type="button" class="role-btn active" id="seekerBtn" onclick="switchRole('seeker')">Job Seeker</button>
                <button type="button" class="role-btn" id="employerBtn" onclick="switchRole('employer')">Employer</button>
            </div>

            @if($errors->any())
                <div class="alert alert-error" style="margin-top:16px">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" id="regForm" class="form-body">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'seeker') }}">

                {{-- Step 1 --}}
                <div id="step1">
                    <div class="field">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Jane Smith" required>
                    </div>
                    <div class="field">
                        <label>Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="jane@example.com" required>
                    </div>
                    <div class="field">
                        <label>Password *</label>
                        <input type="password" name="password" placeholder="Minimum 8 characters" required>
                    </div>
                    <div class="field">
                        <label>Confirm Password *</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat your password" required>
                    </div>
                    <div class="field">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+60 12-345 6789">
                    </div>
                    <div id="companyField" class="field" style="display:none">
                        <label>Company Name *</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Acme Corp">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="goStep2()">Continue</button>
                </div>

                {{-- Step 2 --}}
                <div id="step2" style="display:none">

                    <div id="seekerStep2">
                        <div class="field">
                            <label>Current / Desired Job Title *</label>
                            <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="e.g. Software Engineer" data-required="true">
                        </div>
                        <div class="field">
                            <label>Location *</label>
                            <input type="text" name="location" value="{{ old('location') }}" placeholder="Kuala Lumpur, MY" data-required="true">
                        </div>
                        <div class="field">
                            <label>Bio</label>
                            <textarea name="bio" rows="3" placeholder="Tell employers about yourself...">{{ old('bio') }}</textarea>
                        </div>
                        <div class="field">
                            <label>Skills <small>(comma-separated)</small></label>
                            <input type="text" name="skills" value="{{ old('skills') }}" placeholder="Laravel, React, MySQL">
                        </div>
                    </div>

                    <div id="employerStep2" style="display:none">
                        <div class="field">
                            <label>Industry *</label>
                            <select name="industry" data-required="true">
                                <option value="">Select industry...</option>
                                @foreach(['Technology','Finance','Healthcare','Education','Retail','Manufacturing','Media','Other'] as $ind)
                                    <option value="{{ strtolower($ind) }}" {{ old('industry') == strtolower($ind) ? 'selected' : '' }}>{{ $ind }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Company Size</label>
                            <select name="company_size">
                                @foreach(['1-10','11-50','51-200','201-500','500+'] as $size)
                                    <option value="{{ $size }}" {{ old('company_size') == $size ? 'selected' : '' }}>{{ $size }} employees</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Website</label>
                            <input type="url" name="website" value="{{ old('website') }}" placeholder="https://company.com">
                        </div>
                        <div class="field">
                            <label>Company Description</label>
                            <textarea name="description" rows="3" placeholder="What does your company do?">{{ old('description') }}</textarea>
                        </div>
                        <div class="field">
                            <label>Location *</label>
                            <input type="text" name="emp_location" value="{{ old('emp_location') }}" placeholder="Kuala Lumpur, MY" data-required="true">
                        </div>
                    </div>

                    <div class="field-row">
                        <button type="button" class="btn btn-outline" onclick="goStep1()">Back</button>
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </div>

            </form>

            <div class="divider">or</div>
            <p class="text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="link-text">Sign in</a>
            </p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
let currentRole = '{{ old("role", "seeker") }}';

function switchRole(role) {
    currentRole = role;
    document.getElementById('roleInput').value = role;
    document.getElementById('seekerBtn').classList.toggle('active', role === 'seeker');
    document.getElementById('employerBtn').classList.toggle('active', role === 'employer');
    document.getElementById('companyField').style.display = role === 'employer' ? 'flex' : 'none';
    if (role === 'employer') {
        document.getElementById('heroCopy').innerHTML = 'Find the <em>best talent</em> for your team';
        document.getElementById('heroSub').textContent = 'Post jobs, manage applications, and build your dream team — all in one place.';
    } else {
        document.getElementById('heroCopy').innerHTML = 'Land your <em>dream job</em> faster';
        document.getElementById('heroSub').textContent = 'Create your profile once and let opportunities find you. Employers actively search for candidates like you.';
    }
    document.getElementById('seekerStep2').style.display   = role === 'seeker'   ? 'block' : 'none';
    document.getElementById('employerStep2').style.display = role === 'employer' ? 'block' : 'none';
}

function goStep2() {
    const name    = document.querySelector('input[name="name"]');
    const email   = document.querySelector('input[name="email"]');
    const pass    = document.querySelector('input[name="password"]');
    const confirm = document.querySelector('input[name="password_confirmation"]');
    const company = document.querySelector('input[name="company_name"]');

    if (!name.value.trim()) {
        name.focus();
        name.setCustomValidity('Please enter your full name.');
        name.reportValidity();
        name.setCustomValidity('');
        return;
    }
    if (!email.value.trim()) {
        email.focus();
        email.setCustomValidity('Please enter your email address.');
        email.reportValidity();
        email.setCustomValidity('');
        return;
    }
    if (!pass.value.trim()) {
        pass.focus();
        pass.setCustomValidity('Please enter a password.');
        pass.reportValidity();
        pass.setCustomValidity('');
        return;
    }
    if (pass.value.length < 8) {
        pass.focus();
        pass.setCustomValidity('Password must be at least 8 characters.');
        pass.reportValidity();
        pass.setCustomValidity('');
        return;
    }
    if (pass.value !== confirm.value) {
        confirm.focus();
        confirm.setCustomValidity('Passwords do not match.');
        confirm.reportValidity();
        confirm.setCustomValidity('');
        return;
    }
    if (currentRole === 'employer' && !company.value.trim()) {
        company.focus();
        company.setCustomValidity('Please enter your company name.');
        company.reportValidity();
        company.setCustomValidity('');
        return;
    }

    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.getElementById('stepNum').textContent   = '2';
    document.getElementById('stepLabel').textContent = 'Profile details';
    document.getElementById('dot1').classList.replace('active', 'done');
    document.getElementById('dot2').classList.add('active');
    document.getElementById('seekerStep2').style.display   = currentRole === 'seeker'   ? 'block' : 'none';
    document.getElementById('employerStep2').style.display = currentRole === 'employer' ? 'block' : 'none';
}

function goStep1() {
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step1').style.display = 'block';
    document.getElementById('stepNum').textContent   = '1';
    document.getElementById('stepLabel').textContent = 'Basic information';
    document.getElementById('dot2').classList.remove('active');
    document.getElementById('dot1').classList.replace('done', 'active');
}

document.getElementById('regForm').addEventListener('submit', function(e) {
    const visibleStep2 = currentRole === 'seeker'
        ? document.getElementById('seekerStep2')
        : document.getElementById('employerStep2');

    const requiredFields = visibleStep2.querySelectorAll('[data-required="true"]');

    for (let field of requiredFields) {
        if (!field.value.trim()) {
            e.preventDefault();
            field.focus();
            field.setCustomValidity('This field is required.');
            field.reportValidity();
            field.setCustomValidity('');
            return;
        }
    }
});
</script>
@endpush

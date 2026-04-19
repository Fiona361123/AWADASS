<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user    = Auth::user();
        $profile = $user->role === 'seeker'
            ? $user->seekerProfile
            : $user->employerProfile;

        return view('profile.show', compact('user', 'profile'));
    }

    public function edit()
    {
        $user    = Auth::user();
        $this->authorize('update', $user);
        $profile = $user->role === 'seeker'
            ? $user->seekerProfile
            : $user->employerProfile;

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name  = $request->name;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->role === 'seeker') {
            $request->validate([
                'job_title'            => ['nullable', 'string', 'max:100'],
                'bio'                  => ['nullable', 'string', 'max:1000'],
                'skills'               => ['nullable', 'string', 'max:500'],
                'expected_salary'      => ['nullable', 'string', 'max:100'],
                'education_university' => ['nullable', 'string', 'max:200'],
                'education_degree'     => ['nullable', 'string', 'max:200'],
                'education_year'       => ['nullable', 'string', 'max:10'],
                'work_company'         => ['nullable', 'string', 'max:200'],
                'work_position'        => ['nullable', 'string', 'max:200'],
                'work_years'           => ['nullable', 'string', 'max:50'],
                'languages'            => ['nullable', 'string', 'max:300'],
                'resume' => ['nullable', 'max:2048'],
                ]);

            $data = [
                'phone'                => $request->phone,
                'location'             => $request->location,
                'job_title'            => $request->job_title,
                'bio'                  => $request->bio,
                'skills'               => $request->skills,
                'expected_salary'      => $request->expected_salary,
                'education_university' => $request->education_university,
                'education_degree'     => $request->education_degree,
                'education_year'       => $request->education_year,
                'work_company'         => $request->work_company,
                'work_position'        => $request->work_position,
                'work_years'           => $request->work_years,
                'languages'            => $request->languages,
            ];

            if ($request->hasFile('resume')) {
                $path = $request->file('resume')->store('resumes', 'public');
                $data['resume_path'] = $path;
            }

            $user->seekerProfile()->update($data);


        } else {
            $request->validate([
                'company_name'     => ['nullable', 'string', 'max:200'],
                'industry'         => ['nullable', 'string', 'max:100'],
                'company_size'     => ['nullable', 'string', 'max:20'],
                'website'          => ['nullable', 'url', 'max:255'],
                'description'      => ['nullable', 'string', 'max:1000'],
                'address'          => ['nullable', 'string', 'max:300'],
                'year_established' => ['nullable', 'string', 'max:10'],
                'linkedin'         => ['nullable', 'url', 'max:255'],
                'facebook'         => ['nullable', 'url', 'max:255'],
                'twitter'          => ['nullable', 'url', 'max:255'],
                'logo'             => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            ]);

            $data = [
                'phone'            => $request->phone,
                'location'         => $request->location,
                'company_name'     => $request->company_name,
                'industry'         => $request->industry,
                'company_size'     => $request->company_size,
                'website'          => $request->website,
                'description'      => $request->description,
                'address'          => $request->address,
                'year_established' => $request->year_established,
                'linkedin'         => $request->linkedin,
                'facebook'         => $request->facebook,
                'twitter'          => $request->twitter,
            ];

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $data['logo_path'] = $path;
            }

            $user->employerProfile()->update($data);

        }

        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully!');
    }
}

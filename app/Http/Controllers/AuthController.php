<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SeekerProfile;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            session(['user_role' => Auth::user()->role]);

            return redirect()->route('home')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()
            ->withInput($request->only('email', 'role'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'role'         => ['required', 'in:seeker,employer'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'job_title'    => ['nullable', 'string', 'max:100'],
            'location'     => ['nullable', 'string', 'max:100'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'skills'       => ['nullable', 'string', 'max:500'],
            'company_name' => ['nullable', 'string', 'max:200'],
            'industry'     => ['nullable', 'string', 'max:100'],
            'company_size' => ['nullable', 'string', 'max:20'],
            'website'      => ['nullable', 'url', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($request->role === 'seeker') {
            SeekerProfile::create([
                'user_id'   => $user->id,
                'phone'     => $request->phone,
                'job_title' => $request->job_title,
                'location'  => $request->location,
                'bio'       => $request->bio,
                'skills'    => $request->skills,
            ]);
        } else {
            EmployerProfile::create([
                'user_id'      => $user->id,
                'phone'        => $request->phone,
                'company_name' => $request->company_name,
                'industry'     => $request->industry,
                'company_size' => $request->company_size,
                'website'      => $request->website,
                'description'  => $request->description,
                'location'     => $request->location,
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        session(['user_role' => $user->role]);

        return redirect()->route('home')
            ->with('success', 'Account created successfully! Welcome, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been signed out.');
    }
}

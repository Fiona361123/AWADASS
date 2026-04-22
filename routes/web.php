<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;

/* Public routes */

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isEmployer()
            ? redirect()->route('dashboard')
            : redirect()->route('home');
    }

    $featuredJobs = \App\Models\JobPosting::where('status', 'open')
        ->inRandomOrder()
        ->take(6)
        ->get();

    return view('home', compact('featuredJobs'));
})->name('landing');

/* Login */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

/* Register */
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

/* Logout */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* Forgot password */
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => bcrypt($password)])->save();
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password reset! Please sign in.')
        : back()->withErrors(['email' => __($status)]);
})->name('password.update');

/* Authenticated routes */
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        if (auth()->user()->isEmployer()) {
            return redirect()->route('dashboard');
        }

        $featuredJobs = \App\Models\JobPosting::where('status', 'open')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('home', compact('featuredJobs'));
    })->name('home');

    // Employer dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start', [ChatController::class, 'startOrGet'])->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/message', [ChatController::class, 'store'])->name('chat.send');

    // Job Posting CRUD routes
    Route::resource('jobposting', JobPostingController::class)
        ->except(['index'])
        ->names('jobposting')
        ->parameters(['jobposting' => 'job']);
    Route::patch('/jobposting/{job}/toggle-status', [JobPostingController::class, 'toggleStatus'])->name('jobposting.toggleStatus');
});

/* Job Browsing for Seeker */
Route::middleware('auth')->group(function () {

    Route::get('/jobs', [JobController::class, 'index'])
        ->name('jobs.index');

    Route::get('/jobs/{job}', [JobController::class, 'show'])
        ->name('jobs.show');

    /* Job Application for Seeker */
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store']);

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');

    Route::post('/applications/{id}/update-files', [ApplicationController::class, 'updateFiles']);
    Route::delete('/applications/{id}/documents/{documentId}', [ApplicationController::class, 'deleteDocument']);
    Route::get('/jobs/{job}/applications', [ApplicationController::class, 'viewApplicants'])->name('jobs.applications');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
});


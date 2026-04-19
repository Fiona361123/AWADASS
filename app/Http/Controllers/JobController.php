<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\EmployerProfile;
use App\Models\Application;

class JobController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $jobs = collect();


        if ($user->isSeeker()) {
            // seeker sees ALL jobs
            $jobs = JobPosting::where('status', 'open')->get();
        } elseif ($user->isEmployer()) {
            // employer sees ONLY their own jobs
            $jobs = JobPosting::where('employer_id', $user->id)->get();
        }

        return view('jobs.index', compact('jobs'));
    }

    public function show(JobPosting $job)
    {
        $job->load('employer.employerProfile');
        $isApplied = Application::where('user_id', auth()->id())
            ->where('job_posting_id', $job->id)
            ->exists();
        return view('jobs.show', compact('job', 'isApplied'));    
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\EmployerProfile;
use App\Models\Application;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = trim((string) $request->query('search', ''));

        $jobsQuery = JobPosting::query();


        if ($user->role === 'seeker') {
            $jobsQuery->where('status', 'open');
        } elseif ($user->role === 'employer') {
            $jobsQuery->where('employer_id', $user->id);
        } else {
            $jobsQuery->whereRaw('1 = 0');
        }

        if ($search !== '') {
            $jobsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('requirements', 'like', '%' . $search . '%');
            });
        }

        $jobs = $jobsQuery->latest()->get();

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

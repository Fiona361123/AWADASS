<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function create()
    {
        $this->authorizeEmployer();
        return view('employer.jobs.create');
    }

    public function store(Request $request)
    {
        $this->authorizeEmployer();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary_min' => 'nullable|string|max:100',
            'salary_max' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed'
        ]);

        auth()->user()->jobPostings()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Job posted successfully.');
    }

    public function show(JobPosting $job)
    {
        $this->authorizeOwnership($job);
        return view('employer.jobs.show', compact('job'));
    }

    public function edit(JobPosting $job)
    {
        $this->authorizeOwnership($job);
        return view('employer.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $this->authorizeOwnership($job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary_min' => 'nullable|string|max:100',
            'salary_max' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed'
        ]);

        $job->update($validated);

        return redirect()->route('jobs.show', $job)->with('success', 'Job updated successfully.');
    }

    public function destroy(JobPosting $job)
    {
        $this->authorizeOwnership($job);
        $job->delete();

        return redirect()->route('dashboard')->with('success', 'Job deleted successfully.');
    }

    private function authorizeEmployer()
    {
        if (!auth()->user()->isEmployer()) abort(403, 'Only employers can perform this action.');
    }

    private function authorizeOwnership(JobPosting $job)
    {
        $this->authorizeEmployer();
        if ($job->employer_id !== auth()->id()) abort(403, 'You do not own this job posting.');
    }
}
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
        return view('employer.create');
    }

    public function store(Request $request)
    {
        $this->authorizeEmployer();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed',
        ]);

        auth()->user()->jobPostings()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Job posted successfully.');
    }

    public function show(JobPosting $job)
    {
        $this->authorizeOwnership($job);
        return view('employer.showJobDetails', compact('job'));
    }

    public function edit(JobPosting $job)
    {
        $this->authorizeOwnership($job);
        return view('employer.edit', compact('job'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $this->authorizeOwnership($job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
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
        if (!auth()->user()->isEmployer())
            abort(403, 'Only employers can perform this action.');
    }

    private function authorizeOwnership(JobPosting $job)
    {
        $this->authorizeEmployer();
        if ($job->employer_id !== auth()->id())
            abort(403, 'You do not own this job posting.');
    }

    public function toggleStatus(JobPosting $job)
    {
        $this->authorizeOwnership($job);
        $job->update([
            'status' => $job->status === 'open' ? 'closed' : 'open'
        ]);
        return back()->with('success', 'Job status updated.');
    }

    public function employer()
    {
        return $this->belongsTo(ProfileController::class, 'employer_id', 'user_id');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\JobPosting;
use App\Models\SeekerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function store(Request $request, JobPosting $job)
    {
        $includeSavedResume = $request->boolean('use_default_resume');
        $savedResumePath = $includeSavedResume
            ? optional(auth()->user()->seekerProfile)->resume_path
            : null;

        $newDocuments = $request->hasFile('documents')
            ? Arr::wrap($request->file('documents'))
            : [];

        $documentCount = count($newDocuments) + ($savedResumePath ? 1 : 0);

        if ($documentCount > 3) {
            return response()->json([
                'message' => 'Maximum 3 files allowed'
            ], 422);
        }

        $alreadyApplied = Application::where('user_id', auth()->id())
            ->where('job_posting_id', $job->id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json([
                'message' => 'You already applied for this job.'
            ], 422);
        }

        $application = Application::create([
            'user_id' => auth()->id(),
            'job_posting_id' => $job->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        if ($savedResumePath) {
            $application->documents()->create([
                'file_path' => $savedResumePath
            ]);
        }

        if (!empty($newDocuments)) {

            foreach ($newDocuments as $file) {

                $storedPath = $file->store('applications', 'public');

                $application->documents()->create([
                    'file_path' => $storedPath
                ]);
            }
        }

        return response()->json([
            'message' => 'Applied successfully!'
        ]);
    }

    public function index()
    {
        $applications = Application::with(['jobPosting.employer.employerProfile', 'documents'])
            ->where('user_id', auth()->id())
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function updateFiles(Request $request, $id)
    {
        $application = Application::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($application->status !== 'pending') {
            return response()->json(['error' => 'Not allowed'], 403);
        }

        $newDocuments = $request->hasFile('documents')
            ? Arr::wrap($request->file('documents'))
            : [];

        foreach ($newDocuments as $file) {

            $storedPath = $file->store('applications', 'public');

            $application->documents()->create([
                'file_path' => $storedPath
            ]);
        }

        $application->load('documents');

        return response()->json([
            'success' => true,
            'documents' => $application->documents
        ]);
    }

    public function deleteDocument($id, $documentId)
    {
        $application = Application::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($application->status !== 'pending') {
            return response()->json(['error' => 'Not allowed'], 403);
        }

        if ($application->documents()->count() <= 1) {
            return response()->json([
                'error' => 'At least one document is required'
            ], 422);
        }

        $doc = ApplicationDocument::where('id', $documentId)
            ->where('application_id', $application->id)
            ->firstOrFail();

        $shouldDeleteFile = str_starts_with($doc->file_path, 'applications/');

        if (!$shouldDeleteFile) {
            $isSavedResume = SeekerProfile::where('resume_path', $doc->file_path)->exists();
            $shouldDeleteFile = !$isSavedResume;
        }

        if ($shouldDeleteFile) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        $application->load('documents');

        return response()->json([
            'success' => true,
            'documents' => $application->documents
        ]);
    }

    public function viewApplicants(JobPosting $job)
    {
        if ($job->employer_id !== auth()->id()) {
            abort(403);
        }

        $applications = $job->applications()->with('user')->latest('applied_at')->get();

        return view('employer.viewApplications', compact('job', 'applications'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        if ($application->jobPosting->employer_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated.');
    }
}
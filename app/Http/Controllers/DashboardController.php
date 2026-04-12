<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ensure only employers can access the dashboard
        if (!auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized access. Only employers can view this dashboard.');
        }

        // Fetch jobs created by the logged-in employer
        $jobs = JobPosting::where('employer_id', auth()->id())->latest()->paginate(10);

        // Render the dashboard view
        return view('employer.dashboard', compact('jobs'));
    }
}

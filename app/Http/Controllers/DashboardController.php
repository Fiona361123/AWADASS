<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        if (!auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized access. Only employers can view this dashboard.');
        }

        $jobs = JobPosting::where('employer_id', auth()->id())->latest()->paginate(10);

        return view('employer.dashboard', compact('jobs'));
    }
}

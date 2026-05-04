<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;

class PublicJobController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::where('status', 'Open')->latest()->get();

        return view('careers.index', compact('jobs'));
    }

    public function show(JobPosting $job)
    {
        if ($job->status !== 'Open') {
            abort(404);
        }

        return view('careers.show', compact('job'));
    }
}

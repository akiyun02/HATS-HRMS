<?php

namespace App\Http\Controllers;

use App\Models\JobApplicant;
use App\Models\JobPosting;
use App\Models\User;
use App\Notifications\NewApplicantNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function submit(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string',
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        $applicant = $job->applicants()->create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'resume_path' => $resumePath,
            'cover_letter' => $validated['cover_letter'],
            'status' => 'Applied',
        ]);

        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'HR']);
        })->get();

        Notification::send($admins, new NewApplicantNotification($applicant));

        return back()->with('success', 'Application submitted successfully.');
    }

    public function updateStatus(Request $request, JobApplicant $applicant)
    {
        $validated = $request->validate([
            'status' => 'required|in:Applied,Screening,Interview,Offer,Hired,Rejected'
        ]);

        $applicant->update(['status' => $validated['status']]);

        // If hired, an event should be fired to onboard them. (To be added later via Events)

        return back()->with('success', "Applicant status updated to {$validated['status']}");
    }
}

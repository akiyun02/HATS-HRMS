<?php

namespace App\Http\Controllers;

use App\Models\JobApplicant;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecruitmentController extends Controller
{
    public function exportCSV()
    {
        $jobs = JobPosting::latest()->get();

        $response = new StreamedResponse(function () use ($jobs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Title', 'Description', 'Status', 'Date Posted']);

            foreach ($jobs as $job) {
                fputcsv($handle, [
                    $job->title,
                    $job->description,
                    $job->status,
                    $job->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="recruitment-report.csv"');

        return $response;
    }

    public function index()
    {
        $jobs = JobPosting::withCount('applicants')->latest()->get();

        // Eager load applicants for the active pipeline view
        $applicants = JobApplicant::with('jobPosting')->orderBy('created_at', 'desc')->get();

        $pipeline = [
            'Applied' => $applicants->where('status', 'Applied'),
            'Screening' => $applicants->where('status', 'Screening'),
            'Interview' => $applicants->where('status', 'Interview'),
            'Offer' => $applicants->where('status', 'Offer'),
            'Hired' => $applicants->where('status', 'Hired'),
            'Rejected' => $applicants->where('status', 'Rejected'),
        ];

        return view('recruitment.index', compact('jobs', 'pipeline'));
    }

    public function create()
    {
        return view('recruitment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        JobPosting::create($validated);

        return redirect()->route('recruitment.index')->with('success', 'Job posting created.');
    }

    public function toggleStatus(JobPosting $job)
    {
        $newStatus = $job->status === 'Open' ? 'Closed' : 'Open';
        $job->update(['status' => $newStatus]);

        return back()->with('success', "Job posting is now {$newStatus}.");
    }

    public function destroy(JobPosting $job)
    {
        // Manually delete resumes for all applicants of this job posting
        foreach ($job->applicants as $applicant) {
            if ($applicant->resume_path) {
                Storage::disk('public')->delete($applicant->resume_path);
            }
        }

        $job->delete();

        return redirect()->route('recruitment.index')->with('success', 'Job posting and associated data removed successfully.');
    }
}

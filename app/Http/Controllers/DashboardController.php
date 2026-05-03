<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasAnyRole(['Admin', 'HR'])) {
            return $this->adminDashboard();
        }

        return $this->employeeDashboard($user);
    }

    protected function adminDashboard()
    {
        $stats = [
            'total_employees' => User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))->count(),
            'pending_leaves' => LeaveRequest::where('status', 'Pending')->count(),
            'present_today' => Attendance::where('date', now()->toDateString())->count(),
        ];

        $pendingApplications = LeaveRequest::with('user', 'leaveType')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentDecisions = LeaveRequest::with(['user', 'leaveType', 'approver'])
            ->whereIn('status', ['Approved', 'Rejected'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $activeJobs = \App\Models\JobPosting::withCount('applicants')
            ->where('status', 'Active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.admin', compact('stats', 'pendingApplications', 'recentDecisions', 'activeJobs'));
    }

    protected function employeeDashboard(User $user)
    {
        $todayAttendance = $user->attendances()->where('date', now()->toDateString())->first();

        $stats = [
            'leaves_taken' => $user->leaveRequests()->where('status', 'Approved')->count(),
            'attendance_count' => $user->attendances()->whereMonth('date', now()->month)->count(),
            'latest_rating' => $user->performanceReviews()->latest('review_date')->first()?->rating ?? 'N/A',
        ];

        $upcomingLeaves = $user->leaveRequests()
            ->with('leaveType')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(3)
            ->get();

        return view('dashboards.employee', compact('stats', 'todayAttendance', 'upcomingLeaves'));
    }
}

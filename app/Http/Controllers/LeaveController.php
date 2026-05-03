<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\LeaveRequestedNotification;
use App\Notifications\LeaveStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $leaveRequests = $user->leaveRequests()->with('leaveType')->orderBy('created_at', 'desc')->get();

        return view('leaves.index', compact('leaveRequests'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Only show leave categories that have been assigned (accrued) to this specific employee for the current year
        $leaveTypes = LeaveType::whereHas('leaveBalances', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('year', now()->year);
        })->get();

        return view('leaves.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_half_day' => 'boolean',
            'reason' => 'nullable|string',
        ]);

        $user = auth()->user();
        $leaveType = LeaveType::find($validated['leave_type_id']);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
        $isHalfDay = $request->boolean('is_half_day');
        $requestedDays = $isHalfDay ? 0.5 : ($startDate->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $endDate) + 1);

        // Check accrued balance instead of hardcoded max_days
        $balanceRecord = $user->leaveBalances()
            ->where('leave_type_id', $leaveType->id)
            ->where('year', now()->year)
            ->first();

        $availableDays = $balanceRecord ? $balanceRecord->available_days : 0;

        if ($requestedDays > $availableDays) {
            return back()->withErrors(['leave_type_id' => "Insufficient balance. You have {$availableDays} days remaining."]);
        }

        $leave = $user->leaveRequests()->create(array_merge($validated, ['is_half_day' => $isHalfDay]));

        // Notify HR/Admins
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin', 'HR']);
        })->get();

        Notification::send($admins, new LeaveRequestedNotification($leave));

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function adminIndex()
    {
        $leaveRequests = LeaveRequest::with(['user', 'leaveType'])->where('status', 'Pending')->get();
        $history = LeaveRequest::with(['user', 'leaveType', 'approver'])->where('status', '!=', 'Pending')->orderBy('updated_at', 'desc')->limit(20)->get();

        return view('leaves.admin-index', compact('leaveRequests', 'history'));
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:leave_requests,id',
            'action' => 'required|in:approve,reject',
            'approver_note' => 'nullable|string|max:1000',
        ]);

        $status = $validated['action'] === 'approve' ? 'Approved' : 'Rejected';

        $leaves = LeaveRequest::whereIn('id', $validated['ids'])->get();

        foreach ($leaves as $leave) {
            if ($status === 'Approved' && $leave->status !== 'Approved') {
                $this->deductBalance($leave);
            }

            $leave->update([
                'status' => $status,
                'approver_note' => $validated['approver_note'],
                'approved_by' => auth()->id(),
            ]);

            $leave->user->notify(new LeaveStatusNotification($leave));
        }

        return back()->with('success', count($validated['ids']).' requests have been '.strtolower($status).'.');
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'Approved') {
            $this->deductBalance($leaveRequest);
        }

        $leaveRequest->update([
            'status' => 'Approved',
            'approver_note' => $request->input('approver_note'),
            'approved_by' => auth()->id(),
        ]);

        $leaveRequest->user->notify(new LeaveStatusNotification($leaveRequest));

        return back()->with('success', 'Leave request approved.');
    }

    protected function deductBalance(LeaveRequest $leave)
    {
        $days = $leave->is_half_day ? 0.5 : ($leave->start_date->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $leave->end_date) + 1);

        $balance = $leave->user->leaveBalances()
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', $leave->start_date->year)
            ->first();
            
        if ($balance) {
            $balance->increment('used_days', $days);
        }
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'Rejected',
            'approver_note' => $request->input('approver_note'),
            'approved_by' => auth()->id(),
        ]);

        $leaveRequest->user->notify(new LeaveStatusNotification($leaveRequest));

        return back()->with('success', 'Leave request rejected.');
    }

    public function adminEdit(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['user', 'leaveType']);
        $leaveTypes = LeaveType::all();

        return view('leaves.admin-edit', compact('leaveRequest', 'leaveTypes'));
    }

    public function adminUpdate(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_half_day' => 'boolean',
            'reason' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $leaveRequest->update(array_merge($validated, [
            'is_half_day' => $request->boolean('is_half_day'),
        ]));

        return redirect()->route('leaves.admin')->with('success', 'Leave request updated successfully.');
    }
}

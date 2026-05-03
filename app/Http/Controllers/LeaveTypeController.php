<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:leave_types,name',
            'max_days' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        LeaveType::create($validated);

        return back()->with('success', 'Leave type created successfully.');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:leave_types,name,'.$leaveType->id,
            'max_days' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $leaveType->update($validated);

        return back()->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return back()->with('success', 'Leave type removed.');
    }
}

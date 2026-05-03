<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function update(Request $request, User $employee, \App\Services\Leave\LeaveEntitlementService $leaveService)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'accrued_days' => 'required|numeric',
            'reason' => 'required|string|max:255',
        ]);

        $leaveService->adjustBalance($employee, $validated['leave_type_id'], $validated['accrued_days'], $validated['reason']);

        return back()->with('success', "Leave balance for {$employee->name} has been adjusted and logged.");
    }

    public function destroy(User $employee, LeaveBalance $balance)
    {
        $balance->delete();
        return back()->with('success', 'Leave category removed from employee entitlement.');
    }
}

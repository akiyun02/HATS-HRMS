<?php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeavePolicyController extends Controller
{
    public function index()
    {
        $policies = LeavePolicy::with('leaveTypes')->get();
        return view('leave-policies.index', compact('policies'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::all();
        return view('leave-policies.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_probationary' => 'boolean',
            'is_default' => 'boolean',
            'leave_types' => 'required|array',
            'leave_types.*.id' => 'required|exists:leave_types,id',
            'leave_types.*.annual_days' => 'required|numeric|min:0',
            'leave_types.*.accrual_type' => 'required|in:fixed,prorated,monthly',
            'leave_types.*.carry_over_limit' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            if ($validated['is_default'] ?? false) {
                LeavePolicy::where('is_default', true)->update(['is_default' => false]);
            }

            $policy = LeavePolicy::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'is_probationary' => $validated['is_probationary'] ?? false,
                'is_default' => $validated['is_default'] ?? false,
            ]);

            foreach ($validated['leave_types'] as $typeData) {
                $policy->leaveTypes()->attach($typeData['id'], [
                    'annual_days' => $typeData['annual_days'],
                    'accrual_type' => $typeData['accrual_type'],
                    'carry_over_limit' => $typeData['carry_over_limit'],
                ]);
            }
        });

        return redirect()->route('leave-policies.index')->with('success', 'Leave policy created successfully.');
    }

    public function show(LeavePolicy $leavePolicy)
    {
        return view('leave-policies.show', compact('leavePolicy'));
    }

    public function edit(LeavePolicy $leavePolicy)
    {
        $leaveTypes = LeaveType::all();
        $leavePolicy->load('leaveTypes');
        return view('leave-policies.edit', compact('leavePolicy', 'leaveTypes'));
    }

    public function update(Request $request, LeavePolicy $leavePolicy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_probationary' => 'boolean',
            'is_default' => 'boolean',
            'leave_types' => 'required|array',
            'leave_types.*.id' => 'required|exists:leave_types,id',
            'leave_types.*.annual_days' => 'required|numeric|min:0',
            'leave_types.*.accrual_type' => 'required|in:fixed,prorated,monthly',
            'leave_types.*.carry_over_limit' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $leavePolicy) {
            if ($validated['is_default'] ?? false) {
                LeavePolicy::where('id', '!=', $leavePolicy->id)->where('is_default', true)->update(['is_default' => false]);
            }

            $leavePolicy->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'is_probationary' => $validated['is_probationary'] ?? false,
                'is_default' => $validated['is_default'] ?? false,
            ]);

            $syncData = [];
            foreach ($validated['leave_types'] as $typeData) {
                $syncData[$typeData['id']] = [
                    'annual_days' => $typeData['annual_days'],
                    'accrual_type' => $typeData['accrual_type'],
                    'carry_over_limit' => $typeData['carry_over_limit'],
                ];
            }
            $leavePolicy->leaveTypes()->sync($syncData);
        });

        return redirect()->route('leave-policies.index')->with('success', 'Leave policy updated successfully.');
    }

    public function destroy(LeavePolicy $leavePolicy)
    {
        $leavePolicy->delete();
        return redirect()->route('leave-policies.index')->with('success', 'Leave policy deleted successfully.');
    }
}

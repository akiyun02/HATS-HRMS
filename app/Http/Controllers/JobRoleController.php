<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\JobRole;
use Illuminate\Http\Request;

class JobRoleController extends Controller
{
    public function index(Request $request)
    {
        $query = JobRole::with('department');

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        } elseif ($request->has('department')) {
            // Support both 'department' and 'department_id' for flexibility
            $query->where('department_id', $request->department);
        }

        $jobRoles = $query->get();
        $selectedDepartment = null;
        if ($request->has('department_id') || $request->has('department')) {
            $selectedDepartment = Department::find($request->department_id ?? $request->department);
        }

        return view('job-roles.index', compact('jobRoles', 'selectedDepartment'));
    }

    public function create(Request $request)
    {
        $departments = Department::all();
        $selectedDepartmentId = $request->input('department_id') ?? $request->input('department');

        return view('job-roles.create', compact('departments', 'selectedDepartmentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        JobRole::create($validated);

        return redirect()->route('job-roles.index')->with('success', 'Job Role created successfully.');
    }

    public function edit(JobRole $jobRole)
    {
        $departments = Department::all();

        return view('job-roles.edit', compact('jobRole', 'departments'));
    }

    public function update(Request $request, JobRole $jobRole)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $jobRole->update($validated);

        return redirect()->route('job-roles.index')->with('success', 'Job Role updated successfully.');
    }

    public function destroy(JobRole $jobRole)
    {
        $jobRole->delete();

        return redirect()->route('job-roles.index')->with('success', 'Job Role deleted successfully.');
    }
}

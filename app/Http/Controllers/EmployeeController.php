<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\LeavePolicy;
use App\Http\Requests\StoreOnboardEmployeeRequest;
use App\Services\Employee\OnboardEmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    public function exportCSV()
    {
        $employees = User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))->with('employeeProfile.jobRole.department')->get();

        $response = new StreamedResponse(function () use ($employees) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Employee ID', 'Department', 'Job Role', 'Joined']);

            foreach ($employees as $emp) {
                fputcsv($handle, [
                    $emp->name,
                    $emp->email,
                    $emp->employeeProfile?->employee_id ?? 'N/A',
                    $emp->employeeProfile?->jobRole?->department?->name ?? 'N/A',
                    $emp->employeeProfile?->jobRole?->name ?? 'N/A',
                    $emp->employeeProfile?->joining_date?->format('Y-m-d') ?? 'N/A',
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="employee-directory.csv"');

        return $response;
    }

    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($query) {
            $query->where('name', 'Employee');
        })->with(['employeeProfile.jobRole.department']);

        // Search by Name or Email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        // Filter by Department
        if ($request->filled('department')) {
            $query->whereHas('employeeProfile.jobRole', function ($q) use ($request) {
                $q->where('department_id', $request->input('department'));
            });
        }

        $employees = $query->paginate(20)->withQueryString();
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::with('jobRoles')->get();
        $roles = Role::all();
        $leavePolicies = LeavePolicy::all();

        return view('employees.create', compact('departments', 'roles', 'leavePolicies'));
    }

    public function store(StoreOnboardEmployeeRequest $request, OnboardEmployeeService $onboardService)
    {
        $result = $onboardService->onboard($request->validated());

        $message = 'Employee onboarded successfully with leave entitlements.';
        
        if ($result['was_auto_generated']) {
            $message .= ' Their auto-generated password is: ' . $result['plain_password'];
        }

        return redirect()->route('employees.index')->with('success', $message);
    }

    public function show(User $employee)
    {
        $employee->load(['employeeProfile.jobRole.department', 'attendances', 'leaveRequests.leaveType', 'leaveBalances.leaveType']);

        return view('employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        $employee->load('employeeProfile.jobRole');
        $departments = Department::with('jobRoles')->get();

        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$employee->id,
            'job_role_id' => 'required|exists:job_roles,id',
            'base_salary' => 'nullable|numeric|min:0',
            'employee_id' => 'nullable|string|unique:employee_profiles,employee_id,'.$employee->employeeProfile->id,
            'joining_date' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $employee) {
            $employee->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $employee->employeeProfile()->update([
                'job_role_id' => $validated['job_role_id'],
                'base_salary' => $validated['base_salary'] ?? 0,
                'employee_id' => $validated['employee_id'],
                'joining_date' => $validated['joining_date'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}

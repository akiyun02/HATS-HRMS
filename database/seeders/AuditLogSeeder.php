<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $employees = User::where('email', '!=', 'admin@example.com')->get();

        if (! $admin || $employees->isEmpty()) {
            return;
        }

        $actions = [
            [
                'user' => $admin,
                'action' => 'Login',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['browser' => 'Chrome', 'os' => 'Windows 11'],
                'ip_address' => '192.168.1.1',
                'days_ago' => 0,
            ],
            [
                'user' => $admin,
                'action' => 'Update Settings',
                'model_type' => 'App\Models\Setting',
                'model_id' => 1,
                'old_values' => ['company_name' => 'HATS'],
                'new_values' => ['company_name' => 'HATS HR Portal'],
                'ip_address' => '192.168.1.1',
                'days_ago' => 5,
            ],
        ];

        // Add some employee logins
        foreach ($employees as $emp) {
            $actions[] = [
                'user' => $emp,
                'action' => 'Login',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['browser' => 'Safari', 'os' => 'iOS'],
                'ip_address' => '120.28.'.rand(1, 255).'.'.rand(1, 255),
                'days_ago' => rand(0, 2),
            ];
        }

        // Add Leave Approvals
        $approvedLeaves = LeaveRequest::where('status', 'Approved')->take(5)->get();
        foreach ($approvedLeaves as $leave) {
            $actions[] = [
                'user' => $admin,
                'action' => 'Approved Leave',
                'model_type' => 'App\Models\LeaveRequest',
                'model_id' => $leave->id,
                'old_values' => ['status' => 'Pending'],
                'new_values' => ['status' => 'Approved'],
                'ip_address' => '192.168.1.1',
                'days_ago' => rand(3, 10),
            ];
        }

        // Add Payroll Finalizations
        $payrollRecords = PayrollRecord::where('status', 'Paid')->take(5)->get();
        foreach ($payrollRecords as $payroll) {
            $actions[] = [
                'user' => $admin,
                'action' => 'Finalized Payroll',
                'model_type' => 'App\Models\PayrollRecord',
                'model_id' => $payroll->id,
                'old_values' => ['status' => 'Draft'],
                'new_values' => ['status' => 'Paid'],
                'ip_address' => '192.168.1.1',
                'days_ago' => rand(15, 30),
            ];
        }

        // Add Profile Updates
        foreach ($employees->take(3) as $emp) {
            $actions[] = [
                'user' => $admin,
                'action' => 'Update Profile',
                'model_type' => 'App\Models\EmployeeProfile',
                'model_id' => $emp->employeeProfile->id,
                'old_values' => ['base_salary' => $emp->employeeProfile->base_salary - 5000],
                'new_values' => ['base_salary' => $emp->employeeProfile->base_salary],
                'ip_address' => '192.168.1.1',
                'days_ago' => rand(40, 60),
            ];
        }

        foreach ($actions as $data) {
            AuditLog::create([
                'user_id' => $data['user']->id,
                'action' => $data['action'],
                'model_type' => $data['model_type'],
                'model_id' => $data['model_id'],
                'old_values' => $data['old_values'],
                'new_values' => $data['new_values'],
                'ip_address' => $data['ip_address'],
                'created_at' => Carbon::now()->subDays($data['days_ago'])->subHours(rand(1, 12)),
            ]);
        }
    }
}

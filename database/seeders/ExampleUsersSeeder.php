<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExampleUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $employeeRole = Role::where('name', 'Employee')->first();

        // 1. Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin.example@example.com'],
            [
                'name' => 'Admin User (Example)',
                'password' => Hash::make('password'),
            ]
        );
        if ($adminRole && ! $admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole);
        }

        // 2. Create Employee
        $employee = User::updateOrCreate(
            ['email' => 'employee.example@example.com'],
            [
                'name' => 'John Doe (Example)',
                'password' => Hash::make('password'),
            ]
        );
        if ($employeeRole && ! $employee->roles->contains($employeeRole->id)) {
            $employee->roles()->attach($employeeRole);
        }

        // 3. Create Employee Profile
        $employee->employeeProfile()->updateOrCreate(
            ['user_id' => $employee->id],
            [
                'employee_id' => 'EMP-EX-001',
                'joining_date' => now()->subYear(),
                'phone' => '555-0199',
                'address' => '456 Example Ave, Tech City',
                'gender' => 'Male',
                'birthday' => '1995-05-20',
                'marital_status' => 'Single',
                'base_salary' => 45000,
            ]
        );

        // 4. Generate Sample Data for the Employee
        $startDate = Carbon::now()->subMonths(2)->startOfMonth();
        $endDate = Carbon::now();

        // Attendance
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            if (! $currentDate->isWeekend()) {
                Attendance::updateOrCreate(
                    ['user_id' => $employee->id, 'date' => $currentDate->toDateString()],
                    [
                        'clock_in' => '08:00:00',
                        'clock_out' => '17:00:00',
                        'status' => 'Present',
                    ]
                );
            }
            $currentDate->addDay();
        }

        // Payroll
        for ($i = 0; $i < 2; $i++) {
            $monthDate = Carbon::now()->subMonths($i);
            PayrollRecord::updateOrCreate(
                [
                    'user_id' => $employee->id,
                    'month' => $monthDate->format('F'),
                    'year' => $monthDate->year,
                ],
                [
                    'gross_pay' => 45000,
                    'net_pay' => 40500,
                    'deductions' => 4500,
                    'status' => 'Paid',
                ]
            );
        }

        // Performance Review
        PerformanceReview::updateOrCreate(
            ['user_id' => $employee->id, 'reviewer_id' => $admin->id],
            [
                'review_date' => Carbon::now()->subMonth()->toDateString(),
                'rating' => 5,
                'feedback' => 'Excellent work on the biometric integration project.',
            ]
        );

        // Leave Request
        $leaveType = LeaveType::where('name', 'Vacation Leave')->first();
        if ($leaveType) {
            LeaveRequest::updateOrCreate(
                [
                    'user_id' => $employee->id,
                    'start_date' => Carbon::now()->subDays(20)->toDateString(),
                ],
                [
                    'leave_type_id' => $leaveType->id,
                    'end_date' => Carbon::now()->subDays(18)->toDateString(),
                    'reason' => 'Annual family vacation.',
                    'status' => 'Approved',
                    'approved_by' => $admin->id,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\LeavePolicy;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealisticDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Core Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $hrRole = Role::firstOrCreate(['name' => 'HR']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );
        if (! $admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole);
        }

        // 2. Departments & Job Roles
        $deptData = [
            'Engineering' => ['Software Engineer', 'Senior Software Engineer', 'QA Engineer', 'DevOps Specialist'],
            'Human Resources' => ['HR Manager', 'Talent Acquisition', 'Payroll Specialist'],
            'Sales' => ['Account Executive', 'Sales Lead', 'Customer Success'],
            'Operations' => ['Operations Manager', 'Facilities Coordinator'],
        ];

        $jobRoles = [];
        foreach ($deptData as $deptName => $roles) {
            $dept = Department::updateOrCreate(['name' => $deptName]);
            foreach ($roles as $roleName) {
                $jobRoles[] = JobRole::updateOrCreate(
                    ['name' => $roleName, 'department_id' => $dept->id]
                );
            }
        }

        // 3. Leave Policy
        $defaultPolicy = LeavePolicy::updateOrCreate(
            ['name' => 'Standard Corporate Policy'],
            [
                'is_default' => true,
                'description' => 'Standard leave entitlements for all regular employees.',
            ]
        );

        // 4. Employees
        $employeeData = [
            ['name' => 'Alice Rivera', 'email' => 'alice.rivera@example.com', 'gender' => 'Female', 'role_idx' => 0],
            ['name' => 'Robert Santos', 'email' => 'robert.santos@example.com', 'gender' => 'Male', 'role_idx' => 1],
            ['name' => 'Maria Clara', 'email' => 'maria.clara@example.com', 'gender' => 'Female', 'role_idx' => 4],
            ['name' => 'Juan Dela Cruz', 'email' => 'juan.delacruz@example.com', 'gender' => 'Male', 'role_idx' => 7],
            ['name' => 'Liza Soberano', 'email' => 'liza.s@example.com', 'gender' => 'Female', 'role_idx' => 2],
            ['name' => 'David Wilson', 'email' => 'david.w@example.com', 'gender' => 'Male', 'role_idx' => 10],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.j@example.com', 'gender' => 'Female', 'role_idx' => 3],
        ];

        $leaveTypes = LeaveType::all();

        foreach ($employeeData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            if (! $user->roles->contains($employeeRole->id)) {
                $user->roles()->attach($employeeRole);
            }

            $user->employeeProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => 'EMP-'.str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'job_role_id' => $jobRoles[$data['role_idx']]->id,
                    'leave_policy_id' => $defaultPolicy->id,
                    'base_salary' => rand(35000, 95000),
                    'joining_date' => Carbon::now()->subMonths(rand(12, 36)),
                    'phone' => '0917'.rand(1000000, 9999999),
                    'address' => rand(1, 999).' Emerald Ave, Pasig City',
                    'gender' => $data['gender'],
                    'birthday' => Carbon::now()->subYears(rand(24, 45))->subDays(rand(1, 365)),
                    'marital_status' => rand(0, 1) ? 'Single' : 'Married',
                ]
            );

            // 5. Attendance (Last 60 Days)
            $date = Carbon::now()->subDays(60);
            while ($date <= Carbon::now()) {
                if (! $date->isWeekend()) {
                    // 10% chance of being absent, 20% chance of being late, 70% present
                    $dice = rand(1, 100);

                    if ($dice <= 10) {
                        // Absent - either record with status Absent or no record (we'll record it as Absent for visibility)
                        Attendance::updateOrCreate(
                            ['user_id' => $user->id, 'date' => $date->toDateString()],
                            [
                                'clock_in' => null,
                                'clock_out' => null,
                                'status' => 'Absent',
                                'notes' => 'No show',
                            ]
                        );
                    } elseif ($dice <= 30) {
                        // Late (Clock in after 8:30)
                        Attendance::updateOrCreate(
                            ['user_id' => $user->id, 'date' => $date->toDateString()],
                            [
                                'clock_in' => $date->copy()->setTime(8, rand(31, 59)),
                                'clock_out' => $date->copy()->setTime(17, rand(0, 30)),
                                'status' => 'Late',
                            ]
                        );
                    } else {
                        // Present (Clock in 7:45 - 8:15)
                        Attendance::updateOrCreate(
                            ['user_id' => $user->id, 'date' => $date->toDateString()],
                            [
                                'clock_in' => $date->copy()->setTime(7, rand(45, 59)),
                                'clock_out' => $date->copy()->setTime(17, rand(0, 60)),
                                'status' => 'Present',
                            ]
                        );
                    }
                }
                $date->addDay();
            }

            // 6. Leaves (Past and Future)
            $leaveTypes = LeaveType::all();

            // Past Leaves
            for ($i = 0; $i < 2; $i++) {
                $startDate = Carbon::now()->subMonths(rand(1, 10))->addDays(rand(1, 20));
                $endDate = $startDate->copy()->addDays(rand(1, 3));

                LeaveRequest::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveTypes->random()->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'reason' => 'Personal matters and family errands.',
                    'status' => 'Approved',
                    'approved_by' => $admin->id,
                ]);
            }

            // Future Leaves (For "Upcoming Time Off")
            for ($i = 0; $i < 2; $i++) {
                $startDate = Carbon::now()->addMonths(rand(1, 6))->addDays(rand(1, 20));
                $endDate = $startDate->copy()->addDays(rand(1, 3));

                LeaveRequest::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveTypes->random()->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'reason' => 'Planned vacation and rest.',
                    'status' => rand(1, 10) > 3 ? 'Approved' : 'Pending',
                    'approved_by' => rand(1, 10) > 3 ? $admin->id : null,
                ]);
            }
            // 7. Performance Reviews
            PerformanceReview::create([
                'user_id' => $user->id,
                'reviewer_id' => $admin->id,
                'review_date' => Carbon::now()->subMonths(rand(1, 6)),
                'rating' => rand(3, 5),
                'feedback' => 'Consistently meeting expectations and showing great initiative in team projects.',
            ]);

            // 8. Payroll (Last 6 Months)
            for ($i = 1; $i <= 6; $i++) {
                $payrollDate = Carbon::now()->subMonths($i);
                $gross = $user->employeeProfile->base_salary;
                $deductions = $gross * 0.12; // 12% total deductions

                PayrollRecord::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'month' => $payrollDate->format('F'),
                        'year' => $payrollDate->year,
                    ],
                    [
                        'gross_pay' => $gross,
                        'deductions' => $deductions,
                        'net_pay' => $gross - $deductions,
                        'status' => 'Paid',
                    ]
                );
            }
        }
    }
}

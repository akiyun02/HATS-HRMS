<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();
        $startDate = Carbon::now()->startOfMonth()->subMonths(2);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            // 1. Generate Attendance (Last 60 days)
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                if (!$currentDate->isWeekend()) {
                    Attendance::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $currentDate->toDateString()],
                        [
                            'clock_in' => '08:00:00',
                            'clock_out' => '17:00:00',
                            'status' => 'Present',
                            'notes' => 'Automatic seed data',
                        ]
                    );
                }
                $currentDate->addDay();
            }

            // 2. Generate Leave Requests
            if ($user->email !== 'admin@example.com') {
                $leaveType = $leaveTypes->random();
                LeaveRequest::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'start_date' => Carbon::now()->subDays(10)->toDateString(),
                    'end_date' => Carbon::now()->subDays(8)->toDateString(),
                    'reason' => 'Vacation leave for testing',
                    'status' => 'Approved',
                    'approved_by' => User::where('email', 'admin@example.com')->first()->id,
                ]);
            }

            // 3. Generate Payroll Records (Last 2 months)
            for ($i = 0; $i < 2; $i++) {
                $month = Carbon::now()->subMonths($i);
                PayrollRecord::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'month' => $month->format('F'),
                        'year' => $month->year,
                    ],
                    [
                        'gross_pay' => 50000,
                        'net_pay' => 45000,
                        'deductions' => 5000,
                        'status' => 'Paid',
                    ]
                );
            }

            // 4. Generate Performance Review
            if ($user->email !== 'admin@example.com') {
                PerformanceReview::create([
                    'user_id' => $user->id,
                    'reviewer_id' => User::where('email', 'admin@example.com')->first()->id,
                    'review_date' => Carbon::now()->toDateString(),
                    'rating' => rand(3, 5),
                    'feedback' => 'Excellent performance during the test cycle.',
                ]);
            }
        }
    }
}

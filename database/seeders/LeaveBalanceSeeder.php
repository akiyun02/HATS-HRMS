<?php

namespace Database\Seeders;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();
        $year = now()->year;

        foreach ($users as $user) {
            $gender = $user->employeeProfile?->gender;

            foreach ($leaveTypes as $type) {
                // Skip gender-incompatible leaves
                if ($gender === 'Male' && str_contains($type->name, 'Maternity')) continue;
                if ($gender === 'Female' && str_contains($type->name, 'Paternity')) continue;

                LeaveBalance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'leave_type_id' => $type->id,
                        'year' => $year,
                    ],
                    [
                        'accrued_days' => $type->max_days,
                        'used_days' => 0,
                        'forfeited_days' => 0,
                    ]
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\EmployeeProfile;
use App\Models\LeavePolicy;
use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilippineLeavePolicySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Main Policy
        $policy = LeavePolicy::updateOrCreate(
            ['name' => 'Philippine Standard Labor Policy'],
            [
                'description' => 'Mandatory leave entitlements as per the Philippine Labor Code and relevant Republic Acts (RA 11210, RA 8187, RA 8972, etc.).',
                'is_probationary' => false,
                'is_default' => true,
            ]
        );

        // 2. Define Entitlements
        $entitlements = [
            'Service Incentive Leave (SIL)' => [
                'annual_days' => 5,
                'accrual_type' => 'yearly',
                'carry_over_limit' => 5,
            ],
            'Vacation Leave' => [
                'annual_days' => 15,
                'accrual_type' => 'yearly',
                'carry_over_limit' => 15,
            ],
            'Sick Leave' => [
                'annual_days' => 15,
                'accrual_type' => 'yearly',
                'carry_over_limit' => 15,
            ],
            'Maternity Leave' => [
                'annual_days' => 105,
                'accrual_type' => 'per_event',
                'carry_over_limit' => 0,
            ],
            'Paternity Leave' => [
                'annual_days' => 7,
                'accrual_type' => 'per_event',
                'carry_over_limit' => 0,
            ],
            'Solo Parent Leave' => [
                'annual_days' => 7,
                'accrual_type' => 'yearly',
                'carry_over_limit' => 0,
            ],
            'VAWC Leave' => [
                'annual_days' => 10,
                'accrual_type' => 'per_event',
                'carry_over_limit' => 0,
            ],
            'Magna Carta for Women' => [
                'annual_days' => 60,
                'accrual_type' => 'per_event',
                'carry_over_limit' => 0,
            ],
            'Bereavement Leave' => [
                'annual_days' => 3,
                'accrual_type' => 'per_event',
                'carry_over_limit' => 0,
            ],
        ];

        foreach ($entitlements as $typeName => $data) {
            $type = LeaveType::where('name', $typeName)->first();

            if ($type) {
                DB::table('leave_policy_leave_type')->updateOrInsert(
                    [
                        'leave_policy_id' => $policy->id,
                        'leave_type_id' => $type->id,
                    ],
                    [
                        'annual_days' => $data['annual_days'],
                        'accrual_type' => $data['accrual_type'],
                        'carry_over_limit' => $data['carry_over_limit'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        // 3. Re-assign this policy to all employees who might have lost it
        EmployeeProfile::query()->update(['leave_policy_id' => $policy->id]);
    }
}

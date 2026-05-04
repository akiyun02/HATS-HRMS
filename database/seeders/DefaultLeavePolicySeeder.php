<?php

namespace Database\Seeders;

use App\Models\LeavePolicy;
use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultLeavePolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Ensure core Leave Types exist based on PH Law and Industry Standards
            $types = [
                ['name' => 'Service Incentive Leave (SIL)', 'max_days' => 5, 'description' => 'Mandatory 5 days leave for employees with at least 1 year of service (PH Labor Code Art. 95).'],
                ['name' => 'Vacation Leave', 'max_days' => 15, 'description' => 'Standard company vacation leave.'],
                ['name' => 'Sick Leave', 'max_days' => 15, 'description' => 'Standard company sick leave.'],
                ['name' => 'Maternity Leave', 'max_days' => 105, 'description' => '105 days for female employees (RA 11210).'],
                ['name' => 'Paternity Leave', 'max_days' => 7, 'description' => '7 days for married male employees (RA 8187).'],
                ['name' => 'Solo Parent Leave', 'max_days' => 7, 'description' => '7 days for solo parents (RA 8972).'],
                ['name' => 'VAWC Leave', 'max_days' => 10, 'description' => '10 days for victims of violence against women and children (RA 9262).'],
                ['name' => 'Emergency Leave', 'max_days' => 5, 'description' => 'Leave for urgent personal matters.'],
            ];

            foreach ($types as $type) {
                LeaveType::updateOrCreate(['name' => $type['name']], $type);
            }

            // 2. Create the Philippine Standard Policy
            $policy = LeavePolicy::updateOrCreate(
                ['name' => 'Philippine Standard Policy'],
                [
                    'description' => 'Compliant with Philippine Labor Laws including mandatory SIL and common industry leave benefits.',
                    'is_default' => true,
                    'is_probationary' => false,
                ]
            );

            // 3. Attach Types to Policy with accrual rules
            $entitlements = [
                'Service Incentive Leave (SIL)' => ['annual_days' => 5, 'accrual_type' => 'fixed'],
                'Vacation Leave' => ['annual_days' => 15, 'accrual_type' => 'prorated'],
                'Sick Leave' => ['annual_days' => 15, 'accrual_type' => 'fixed'],
                'Maternity Leave' => ['annual_days' => 105, 'accrual_type' => 'fixed'],
                'Paternity Leave' => ['annual_days' => 7, 'accrual_type' => 'fixed'],
                'Solo Parent Leave' => ['annual_days' => 7, 'accrual_type' => 'fixed'],
                'VAWC Leave' => ['annual_days' => 10, 'accrual_type' => 'fixed'],
                'Emergency Leave' => ['annual_days' => 5, 'accrual_type' => 'fixed'],
            ];

            foreach ($entitlements as $typeName => $data) {
                $type = LeaveType::where('name', $typeName)->first();
                if ($type) {
                    $policy->leaveTypes()->syncWithoutDetaching([
                        $type->id => [
                            'annual_days' => $data['annual_days'],
                            'accrual_type' => $data['accrual_type'],
                            'carry_over_limit' => 5, // Common carry-over limit
                        ],
                    ]);
                }
            }

            // Ensure no other policy is default
            LeavePolicy::where('id', '!=', $policy->id)->update(['is_default' => false]);
        });
    }
}

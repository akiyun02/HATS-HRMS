<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Service Incentive Leave (SIL)',
                'max_days' => 5,
                'description' => 'Mandatory 5 days paid leave for employees with at least 1 year of service (Labor Code Art. 95).',
            ],
            [
                'name' => 'Vacation Leave',
                'max_days' => 15,
                'description' => 'Company-provided paid time off for leisure and rest.',
            ],
            [
                'name' => 'Sick Leave',
                'max_days' => 15,
                'description' => 'Company-provided paid leave for medical and health recovery.',
            ],
            [
                'name' => 'Maternity Leave',
                'max_days' => 105,
                'description' => '105 days fully paid leave for female employees (RA 11210).',
            ],
            [
                'name' => 'Paternity Leave',
                'max_days' => 7,
                'description' => '7 days fully paid leave for married male employees (RA 8187).',
            ],
            [
                'name' => 'Solo Parent Leave',
                'max_days' => 7,
                'description' => '7 working days paid leave for solo parent employees (RA 8972).',
            ],
            [
                'name' => 'VAWC Leave',
                'max_days' => 10,
                'description' => 'Up to 10 days paid leave for victims of violence against women and children (RA 9262).',
            ],
            [
                'name' => 'Magna Carta for Women',
                'max_days' => 60,
                'description' => 'Up to 2 months leave following gynecological surgery (RA 9710).',
            ],
            [
                'name' => 'Bereavement Leave',
                'max_days' => 3,
                'description' => 'Leave for the loss of an immediate family member.',
            ],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}

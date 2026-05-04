<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EmployeeProfile;
use App\Models\JobRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure we have some departments and job roles
        $departments = Department::factory()->count(3)->create();

        foreach ($departments as $dept) {
            JobRole::factory()->count(3)->create([
                'department_id' => $dept->id,
            ]);
        }

        $employeeRole = Role::where('name', 'Employee')->first();

        // 2. Create 10 Sample Employees
        EmployeeProfile::factory()->count(10)->create()->each(function ($profile) use ($employeeRole) {
            if ($employeeRole) {
                $profile->user->roles()->attach($employeeRole);
            }
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(LeaveTypeSeeder::class);

        // Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->roles()->attach(Role::where('name', 'Admin')->first());

        // Employee User
        $employee = User::factory()->create([
            'name' => 'John Employee',
            'email' => 'employee@example.com',
        ]);
        $employee->roles()->attach(Role::where('name', 'Employee')->first());

        $employee->employeeProfile()->create([
            'employee_id' => 'EMP-001',
            'joining_date' => now()->subYear(),
            'phone' => '123-456-7890',
            'address' => '123 Main St, Springfield',
            'gender' => 'Male',
            'birthday' => '2004-12-16',
            'marital_status' => 'Married',
        ]);

        $this->call(LeaveBalanceSeeder::class);
    }
}

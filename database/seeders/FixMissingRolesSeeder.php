<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class FixMissingRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'Employee')->first();

        if (! $role) {
            return;
        }

        User::whereDoesntHave('roles')
            ->whereHas('employeeProfile')
            ->get()
            ->each(function ($user) use ($role) {
                $user->roles()->attach($role);
            });
    }
}

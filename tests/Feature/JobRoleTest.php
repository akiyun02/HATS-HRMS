<?php

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminRole = Role::create(['name' => 'Admin']);
    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($this->adminRole);
    $this->department = Department::create(['name' => 'IT']);
});

test('it filters job roles by department', function () {
    $this->actingAs($this->admin)
        ->get(route('job-roles.index', ['department_id' => $this->department->id]))
        ->assertSuccessful()
        ->assertSee('IT Positions');
});

test('it pre-selects department on create page', function () {
    $this->actingAs($this->admin)
        ->get(route('job-roles.create', ['department_id' => $this->department->id]))
        ->assertSuccessful()
        ->assertSee('<option value="' . $this->department->id . '" selected>IT</option>', false);
});

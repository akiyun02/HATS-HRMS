<?php

use App\Models\Department;
use App\Models\JobRole;
use App\Models\LeavePolicy;
use App\Models\Role;
use App\Models\User;

// use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(); // Avoid 419 issues in tests

    $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    $this->artisan('db:seed', ['--class' => 'LeaveTypeSeeder']);

    $this->admin = User::factory()->create();
    $this->admin->roles()->attach(Role::where('name', 'Admin')->first());

    $this->department = Department::create(['name' => 'IT']);
    $this->jobRole = JobRole::create([
        'department_id' => $this->department->id,
        'name' => 'Developer',
        'description' => 'Software Developer',
    ]);

    $this->leavePolicy = LeavePolicy::create([
        'name' => 'Standard Policy',
        'is_default' => true,
    ]);
});

test('employee id is generated when left blank during onboarding', function () {
    $data = [
        'name' => 'Test Employee',
        'email' => 'test@example.com',
        'job_role_id' => $this->jobRole->id,
        'employee_id' => '', // Left blank
        'joining_date' => now()->format('Y-m-d'),
        'base_salary' => 50000,
        'leave_policy_id' => $this->leavePolicy->id,
        'send_invite_email' => 0,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('employees.store'), $data);

    $response->assertRedirect(route('employees.index'));

    $employee = User::where('email', 'test@example.com')->first();
    expect($employee)->not->toBeNull();
    expect($employee->employeeProfile->employee_id)->toBe('EMP-001');
});

test('employee id increments correctly', function () {
    // Create first employee
    $this->actingAs($this->admin)->post(route('employees.store'), [
        'name' => 'Emp 1',
        'email' => 'emp1@example.com',
        'job_role_id' => $this->jobRole->id,
        'employee_id' => '',
        'joining_date' => now()->format('Y-m-d'),
        'base_salary' => 50000,
        'leave_policy_id' => $this->leavePolicy->id,
        'send_invite_email' => 0,
    ]);

    // Create second employee
    $this->actingAs($this->admin)->post(route('employees.store'), [
        'name' => 'Emp 2',
        'email' => 'emp2@example.com',
        'job_role_id' => $this->jobRole->id,
        'employee_id' => '',
        'joining_date' => now()->format('Y-m-d'),
        'base_salary' => 50000,
        'leave_policy_id' => $this->leavePolicy->id,
        'send_invite_email' => 0,
    ]);

    $emp1 = User::where('email', 'emp1@example.com')->first();
    $emp2 = User::where('email', 'emp2@example.com')->first();

    expect($emp1)->not->toBeNull();
    expect($emp2)->not->toBeNull();
    expect($emp1->employeeProfile->employee_id)->toBe('EMP-001');
    expect($emp2->employeeProfile->employee_id)->toBe('EMP-002');
});

test('manual employee id is respected', function () {
    $this->actingAs($this->admin)->post(route('employees.store'), [
        'name' => 'Manual Emp',
        'email' => 'manual@example.com',
        'job_role_id' => $this->jobRole->id,
        'employee_id' => 'CUSTOM-999',
        'joining_date' => now()->format('Y-m-d'),
        'base_salary' => 50000,
        'leave_policy_id' => $this->leavePolicy->id,
        'send_invite_email' => 0,
    ]);

    $employee = User::where('email', 'manual@example.com')->first();
    expect($employee)->not->toBeNull();
    expect($employee->employeeProfile->employee_id)->toBe('CUSTOM-999');
});

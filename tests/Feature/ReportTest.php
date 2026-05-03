<?php

use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminRole = Role::create(['name' => 'Admin']);
    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($this->adminRole);
});

test('report index page is accessible', function () {
    $this->actingAs($this->admin)
        ->get(route('reports.index'))
        ->assertSuccessful()
        ->assertSee('Reports');
});

test('workforce data returns json', function () {
    Department::create(['name' => 'IT']);
    
    $this->actingAs($this->admin)
        ->get(route('reports.data.workforce'))
        ->assertSuccessful()
        ->assertJsonStructure(['labels', 'values', 'total']);
});

test('attendance data returns json', function () {
    $this->actingAs($this->admin)
        ->get(route('reports.data.attendance'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'summary' => ['labels', 'values'],
            'trends' => ['labels', 'values']
        ]);
});

test('report export as pdf', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('reports.export', ['type' => 'workforce', 'format' => 'pdf']));
        
    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/pdf');
});

test('report preview as pdf', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('reports.export', ['type' => 'workforce', 'format' => 'pdf', 'disposition' => 'inline']));
        
    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertHeader('Content-Disposition', 'inline');
});

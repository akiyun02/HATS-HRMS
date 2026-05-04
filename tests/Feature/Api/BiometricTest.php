<?php

use App\Models\Attendance;
use App\Models\BiometricDevice;
use App\Models\EmployeeBiometric;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->device = BiometricDevice::create([
        'device_id' => 'ESP32_TEST',
        'name' => 'Test Device',
        'api_token' => 'test-token',
        'is_active' => true,
    ]);

    $this->user = User::factory()->create(['name' => 'John Doe']);
    $this->biometric = EmployeeBiometric::create([
        'user_id' => $this->user->id,
        'rfid_uid' => 'ABCD1234',
        'fingerprint_id' => '1',
    ]);
});

test('it records time in for a valid rfid scan', function () {
    $response = $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'test-token',
        'employee_identifier' => 'ABCD1234',
        'auth_type' => 'rfid',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'message' => 'Time In Recorded',
            'user_name' => 'John Doe',
        ]);

    $this->assertDatabaseHas('attendances', [
        'user_id' => $this->user->id,
        'clock_out' => null,
    ]);
});

test('it handles missing timestamp correctly', function () {
    // This previously caused a 500 error due to "Undefined array key"
    $response = $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'test-token',
        'employee_identifier' => 'ABCD1234',
        'auth_type' => 'rfid',
        // timestamp is missing
    ]);

    $response->assertStatus(200);
});

test('it records time out for an existing attendance', function () {
    Attendance::create([
        'user_id' => $this->user->id,
        'date' => now()->startOfDay(),
        'clock_in' => now()->subHours(8),
        'status' => 'Present',
    ]);

    $response = $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'test-token',
        'employee_identifier' => 'ABCD1234',
        'auth_type' => 'rfid',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'message' => 'Time Out Recorded',
        ]);

    $attendance = Attendance::where('user_id', $this->user->id)->first();
    expect($attendance->clock_out)->not->toBeNull();
});

test('it rejects unauthorized devices', function () {
    $response = $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'wrong-token',
        'employee_identifier' => 'ABCD1234',
        'auth_type' => 'rfid',
    ]);

    $response->assertStatus(401);
});

test('it rejects unknown employees', function () {
    $response = $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'test-token',
        'employee_identifier' => 'UNKNOWN',
        'auth_type' => 'rfid',
    ]);

    $response->assertStatus(404);
});

test('it ignores duplicate scans within one minute', function () {
    // First scan
    $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'test-token',
        'employee_identifier' => 'ABCD1234',
        'auth_type' => 'rfid',
    ]);

    // Second scan immediately after
    $response = $this->postJson('/api/biometric/attendance', [
        'device_id' => 'ESP32_TEST',
        'api_token' => 'test-token',
        'employee_identifier' => 'ABCD1234',
        'auth_type' => 'rfid',
    ]);

    $response->assertStatus(429);
});

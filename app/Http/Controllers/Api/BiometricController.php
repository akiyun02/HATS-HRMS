<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BiometricDevice;
use App\Models\BiometricLog;
use App\Models\EmployeeBiometric;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BiometricController extends Controller
{
    public function store(Request $request)
    {
        // For hardware constraints, we might use a simple token in header or body
        $token = $request->header('Authorization') ?? $request->input('api_token');
        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $device = BiometricDevice::where('api_token', $token)
            ->where('device_id', $request->input('device_id'))
            ->where('is_active', true)
            ->first();

        if (! $device) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized device'], 401);
        }

        $validated = $request->validate([
            'employee_identifier' => 'required|string',
            'auth_type' => 'required|in:rfid,fingerprint',
            'timestamp' => 'nullable|date',
        ]);

        $identifier = $validated['employee_identifier'];
        $authType = $validated['auth_type'];
        $timestamp = isset($validated['timestamp']) ? Carbon::parse($validated['timestamp']) : now();

        // Find employee
        $biometric = EmployeeBiometric::where($authType === 'rfid' ? 'rfid_uid' : 'fingerprint_id', $identifier)->first();

        if (! $biometric) {
            BiometricLog::create([
                'biometric_device_id' => $device->id,
                'employee_identifier' => $identifier,
                'auth_type' => $authType,
                'status' => 'denied',
                'scanned_at' => $timestamp,
            ]);

            return response()->json(['status' => 'error', 'message' => 'Unknown employee'], 404);
        }

        $user = $biometric->user;

        // Prevent duplicate scans within 1 minute
        $recentLog = BiometricLog::where('user_id', $user->id)
            ->where('status', 'success')
            ->where('scanned_at', '>=', $timestamp->copy()->subMinutes(1))
            ->first();

        if ($recentLog) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate scan ignored'], 429);
        }

        // Log success
        BiometricLog::create([
            'biometric_device_id' => $device->id,
            'user_id' => $user->id,
            'employee_identifier' => $identifier,
            'auth_type' => $authType,
            'status' => 'success',
            'scanned_at' => $timestamp,
        ]);

        // Process Attendance
        $today = $timestamp->copy()->startOfDay();
        $attendance = Attendance::where('user_id', $user->id)->where('date', $today)->first();

        if (! $attendance) {
            // Time in
            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'clock_in' => $timestamp,
                'status' => 'Present', // Or compute based on schedule
            ]);
            $message = 'Time In Recorded';
        } else {
            // Time out
            $attendance->update([
                'clock_out' => $timestamp,
            ]);
            $message = 'Time Out Recorded';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'user_name' => $user->name,
        ]);
    }
}

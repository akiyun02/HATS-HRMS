<?php

namespace App\Http\Controllers;

use App\Models\BiometricDevice;
use App\Models\BiometricLog;
use App\Models\EmployeeBiometric;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BiometricController extends Controller
{
    public function index()
    {
        $devices = BiometricDevice::all();
        $logs = BiometricLog::with(['user', 'device'])->latest()->take(50)->get();

        return view('biometrics.index', compact('devices', 'logs'));
    }

    public function createDevice()
    {
        return view('biometrics.devices.create');
    }

    public function storeDevice(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|unique:biometric_devices,device_id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['api_token'] = Str::random(32);

        BiometricDevice::create($validated);

        return redirect()->route('biometrics.index')->with('success', 'Biometric device registered. API Token: '.$validated['api_token']);
    }

    public function editDevice(BiometricDevice $device)
    {
        return view('biometrics.devices.edit', compact('device'));
    }

    public function updateDevice(Request $request, BiometricDevice $device)
    {
        $validated = $request->validate([
            'device_id' => 'required|unique:biometric_devices,device_id,'.$device->id,
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $device->update($validated);

        return redirect()->route('biometrics.index')->with('success', 'Device updated successfully.');
    }

    public function destroyDevice(BiometricDevice $device)
    {
        $device->delete();

        return redirect()->route('biometrics.index')->with('success', 'Device deleted.');
    }

    public function mapping()
    {
        $employees = User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))
            ->with('employeeProfile')
            ->get();
        $mappings = EmployeeBiometric::with('user')->get();

        return view('biometrics.mapping', compact('employees', 'mappings'));
    }

    public function storeMapping(Request $request)
    {
        $mapping = EmployeeBiometric::where('user_id', $request->user_id)->first();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid_uid' => 'nullable|string|unique:employee_biometrics,rfid_uid,'.($mapping->id ?? 'NULL'),
            'fingerprint_id' => 'nullable|integer',
        ]);

        EmployeeBiometric::updateOrCreate(
            ['user_id' => $validated['user_id']],
            [
                'rfid_uid' => $validated['rfid_uid'],
                'fingerprint_id' => $validated['fingerprint_id'],
            ]
        );

        return redirect()->route('biometrics.mapping')->with('success', 'Employee biometrics updated.');
    }

    public function destroyMapping(EmployeeBiometric $mapping)
    {
        $mapping->delete();

        return redirect()->route('biometrics.mapping')->with('success', 'Mapping removed.');
    }
}

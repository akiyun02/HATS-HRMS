<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $leaveTypes = LeaveType::all();
        $roles = Role::all();

        return view('settings.index', compact('settings', 'leaveTypes', 'roles'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'attendance_start' => 'required|string',
            'attendance_end' => 'required|string',
            'sss_rate' => 'required|numeric|min:0',
            'sss_msc_cap' => 'required|numeric|min:0',
            'philhealth_rate' => 'required|numeric|min:0',
            'philhealth_msc_floor' => 'required|numeric|min:0',
            'philhealth_msc_ceiling' => 'required|numeric|min:0',
            'pagibig_amount' => 'required|numeric|min:0',
        ]);

        Setting::set('company.name', $validated['company_name']);
        Setting::set('attendance.start_time', $validated['attendance_start'], 'attendance');
        Setting::set('attendance.end_time', $validated['attendance_end'], 'attendance');
        
        Setting::set('statutory.sss_rate', $validated['sss_rate'], 'statutory');
        Setting::set('statutory.sss_msc_cap', $validated['sss_msc_cap'], 'statutory');
        Setting::set('statutory.philhealth_rate', $validated['philhealth_rate'], 'statutory');
        Setting::set('statutory.philhealth_msc_floor', $validated['philhealth_msc_floor'], 'statutory');
        Setting::set('statutory.philhealth_msc_ceiling', $validated['philhealth_msc_ceiling'], 'statutory');
        Setting::set('statutory.pagibig_amount', $validated['pagibig_amount'], 'statutory');

        return back()->with('success', 'Global configurations and statutory settings updated successfully.');
    }
}

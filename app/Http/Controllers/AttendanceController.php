<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $attendances = $user->attendances()->orderBy('date', 'desc')->paginate(15);
        $corrections = $user->attendanceCorrections()->latest()->get();

        $todayAttendance = $user->attendances()->where('date', Carbon::today())->first();

        return view('attendance.index', compact('attendances', 'todayAttendance', 'corrections'));
    }

    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        if ($user->attendances()->where('date', $today)->exists()) {
            return back()->with('error', 'You have already clocked in today.');
        }

        $startTime = Setting::get('attendance.start_time', '09:00');
        $currentTime = Carbon::now()->format('H:i');

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => Carbon::now(),
            'status' => $currentTime > $startTime ? 'Late' : 'Present',
        ]);

        return back()->with('success', 'Clocked in successfully.');
    }

    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        $attendance = $user->attendances()->where('date', $today)->first();

        if (! $attendance) {
            return back()->with('error', 'You have not clocked in today.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $attendance->update([
            'clock_out' => Carbon::now(),
        ]);

        return back()->with('success', 'Clocked out successfully.');
    }

    public function requestCorrection(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'requested_clock_in' => 'nullable|string',
            'requested_clock_out' => 'nullable|string',
            'reason' => 'required|string',
        ]);

        auth()->user()->attendanceCorrections()->create($validated);

        return back()->with('success', 'Correction request submitted for review.');
    }

    public function approveCorrection(AttendanceCorrection $correction)
    {
        Attendance::updateOrCreate(
            ['user_id' => $correction->user_id, 'date' => $correction->date],
            [
                'clock_in' => $correction->requested_clock_in,
                'clock_out' => $correction->requested_clock_out,
                'status' => 'Present',
            ]
        );

        $correction->update(['status' => 'Approved']);

        return back()->with('success', 'Attendance log updated.');
    }

    public function rejectCorrection(AttendanceCorrection $correction)
    {
        $correction->update(['status' => 'Rejected']);

        return back()->with('success', 'Correction request rejected.');
    }

    public function exportCSV()
    {
        $attendances = Attendance::with('user')->orderBy('date', 'desc')->get();

        $response = new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee', 'Date', 'Clock In', 'Clock Out', 'Status']);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->user->name,
                    $attendance->date->format('Y-m-d'),
                    $attendance->clock_in?->format('H:i') ?? 'N/A',
                    $attendance->clock_out?->format('H:i') ?? 'N/A',
                    $attendance->status,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="attendance-report.csv"');

        return $response;
    }

    public function adminIndex(Request $request)
    {
        $query = Attendance::with('user');
        $corrections = AttendanceCorrection::with('user')->where('status', 'Pending')->get();

        // Search by Employee Name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            });
        }

        // Filter by Date
        if ($request->filled('date')) {
            $query->where('date', $request->input('date'));
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(30)->withQueryString();

        return view('attendance.admin-index', compact('attendances', 'corrections'));
    }
}

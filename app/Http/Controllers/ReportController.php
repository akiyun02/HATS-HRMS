<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\EmployeeProfile;
use App\Models\LeaveRequest;
use App\Models\PayrollRecord;
use App\Models\PerformanceReview;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $employees = User::all();
        return view('reports.index', compact('departments', 'employees'));
    }

    public function workforceData(Request $request)
    {
        return response()->json($this->getWorkforceData($request));
    }

    protected function getWorkforceData(Request $request)
    {
        $query = Department::query();
        if ($request->department_id) $query->where('id', $request->department_id);
        $data = $query->withCount('employeeProfiles as employee_count')->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'values' => $data->pluck('employee_count')->toArray(),
            'total' => (int) $data->sum('employee_count')
        ];
    }

    public function attendanceData(Request $request)
    {
        return response()->json($this->getAttendanceData($request));
    }

    protected function getAttendanceData(Request $request)
    {
        $start = $request->start_date ?? now()->startOfMonth()->toDateString();
        $end = $request->end_date ?? now()->toDateString();
        $query = Attendance::whereBetween('date', [$start, $end]);

        if ($request->department_id) {
            $query->whereHas('user.employeeProfile.jobRole', fn($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->user_id) $query->where('user_id', $request->user_id);

        $stats = $query->clone()->select('status', DB::raw('count(*) as count'))->groupBy('status')->get();
        $trends = $query->clone()->select('date', DB::raw('count(*) as count'))->groupBy('date')->orderBy('date')->get();

        return [
            'summary' => [
                'labels' => $stats->pluck('status')->toArray(),
                'values' => $stats->pluck('count')->map(fn($v) => (int)$v)->toArray(),
            ],
            'trends' => [
                'labels' => $trends->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))->toArray(),
                'values' => $trends->pluck('count')->map(fn($v) => (int)$v)->toArray(),
            ]
        ];
    }

    public function leaveData(Request $request)
    {
        return response()->json($this->getLeaveData($request));
    }

    protected function getLeaveData(Request $request)
    {
        $query = LeaveRequest::where('status', 'Approved');
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }
        if ($request->department_id) {
            $query->whereHas('user.employeeProfile.jobRole', fn($q) => $q->where('department_id', $request->department_id));
        }

        $distribution = $query->whereHas('leaveType')->with('leaveType')->get()
            ->groupBy('leaveType.name')
            ->map(fn($g) => $g->count());

        return [
            'labels' => $distribution->keys()->toArray(),
            'values' => $distribution->values()->toArray(),
        ];
    }

    public function payrollData(Request $request)
    {
        return response()->json($this->getPayrollData($request));
    }

    protected function getPayrollData(Request $request)
    {
        $month = $request->month ?? now()->format('F');
        $year = $request->year ?? now()->year;

        $data = Department::all()->map(function ($dept) use ($month, $year) {
            $total = PayrollRecord::where('month', $month)->where('year', $year)
                ->whereHas('user.employeeProfile.jobRole', fn($q) => $q->where('department_id', $dept->id))
                ->sum('net_pay');
            return ['name' => $dept->name, 'total' => (float) $total];
        });

        return ['labels' => $data->pluck('name')->toArray(), 'values' => $data->pluck('total')->toArray()];
    }

    public function performanceData(Request $request)
    {
        return response()->json($this->getPerformanceData($request));
    }

    protected function getPerformanceData(Request $request)
    {
        $query = PerformanceReview::query();
        if ($request->department_id) {
            $query->whereHas('user.employeeProfile.jobRole', fn($q) => $q->where('department_id', $request->department_id));
        }

        $ratings = $query->select('rating', DB::raw('count(*) as count'))->groupBy('rating')->orderBy('rating', 'desc')->get();

        return [
            'labels' => $ratings->pluck('rating')->map(fn($r) => $r . ' Stars')->toArray(),
            'values' => $ratings->pluck('count')->map(fn($v) => (int)$v)->toArray(),
        ];
    }

    public function export(Request $request)
    {
        $type = $request->type;
        $format = $request->format;
        $disposition = $request->disposition ?? 'attachment';

        if ($format === 'csv') {
            return $this->exportCSV($type, $request);
        }

        $data = ($type === 'overview') ? [
            'workforce' => $this->getWorkforceData($request),
            'attendance' => $this->getAttendanceData($request),
            'leaves' => $this->getLeaveData($request),
            'payroll' => $this->getPayrollData($request),
            'performance' => $this->getPerformanceData($request),
        ] : match($type) {
            'workforce' => $this->getWorkforceData($request),
            'attendance' => $this->getAttendanceData($request),
            'leaves' => $this->getLeaveData($request),
            'payroll' => $this->getPayrollData($request),
            'performance' => $this->getPerformanceData($request),
            default => []
        };

        $pdf = Pdf::loadView('reports.pdf', compact('data', 'type', 'request'))
                  ->setPaper('a4')
                  ->setOption('isFontSubsettingEnabled', true)
                  ->setOption('isHtml5ParserEnabled', true);
        
        if ($disposition === 'inline') {
            return $pdf->setOption('isRemoteEnabled', true)->stream("HATS_Preview_" . strtoupper($type) . ".pdf", ['Attachment' => false]);
        }

        $fileName = "HATS_Audit_" . strtoupper($type) . "_" . now()->format('Ymd_Hi') . ".pdf";
        return $pdf->download($fileName);
    }

    protected function exportCSV($type, Request $request)
    {
        $fileName = "HATS_Export_" . strtoupper($type) . "_" . now()->format('Ymd_Hi') . ".csv";
        $headers = ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=$fileName"];

        $callback = function() use ($type, $request) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM

            $sections = ($type === 'overview') ? ['workforce', 'attendance', 'leaves', 'payroll', 'performance'] : [$type];

            foreach ($sections as $index => $section) {
                if ($index > 0) {
                    fputcsv($file, []);
                    fputcsv($file, []);
                    fputcsv($file, [strtoupper($section) . ' ANALYSIS']);
                    fputcsv($file, []);
                } elseif ($type === 'overview') {
                    fputcsv($file, ['HATS HRMS EXECUTIVE OVERVIEW']);
                    fputcsv($file, ['Generated: ' . now()->format('d M Y, h:i A')]);
                    fputcsv($file, []);
                    fputcsv($file, [strtoupper($section) . ' ANALYSIS']);
                    fputcsv($file, []);
                }

                $this->writeSectionToCsv($file, $section, $request);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    protected function writeSectionToCsv($file, $section, Request $request)
    {
        if ($section === 'workforce') {
            fputcsv($file, ['Employee ID', 'Name', 'Email', 'Department', 'Job Role', 'Joining Date']);
            EmployeeProfile::with(['user', 'jobRole.department'])->chunk(100, function($profiles) use ($file) {
                foreach ($profiles as $p) {
                    fputcsv($file, [$p->employee_id, $p->user->name, $p->user->email, $p->jobRole?->department?->name ?? 'N/A', $p->jobRole?->name ?? 'N/A', $p->joining_date?->toDateString()]);
                }
            });
        } elseif ($section === 'attendance') {
            fputcsv($file, ['Date', 'Employee', 'Clock In', 'Clock Out', 'Status', 'Notes']);
            $query = Attendance::with('user');
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }
            $query->chunk(100, function($rows) use ($file) {
                foreach ($rows as $r) {
                    fputcsv($file, [$r->date->toDateString(), $r->user->name, $r->clock_in?->format('h:i A'), $r->clock_out?->format('h:i A'), $r->status, $r->notes]);
                }
            });
        } elseif ($section === 'leaves') {
            fputcsv($file, ['Employee', 'Type', 'Start Date', 'End Date', 'Days', 'Status', 'Reason']);
            $query = LeaveRequest::with(['user', 'leaveType'])->where('status', 'Approved');
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
            }
            $query->chunk(100, function($rows) use ($file) {
                foreach ($rows as $r) {
                    $days = $r->start_date->diffInDays($r->end_date) + 1;
                    fputcsv($file, [$r->user->name, $r->leaveType->name, $r->start_date->toDateString(), $r->end_date->toDateString(), $days, $r->status, $r->reason]);
                }
            });
        } elseif ($section === 'payroll') {
            fputcsv($file, ['Employee', 'Month', 'Year', 'Gross Pay', 'Deductions', 'Net Pay', 'Status']);
            $month = $request->month ?? now()->format('F');
            $year = $request->year ?? now()->year;
            PayrollRecord::with('user')->where('month', $month)->where('year', $year)->chunk(100, function($records) use ($file) {
                foreach ($records as $r) {
                    fputcsv($file, [$r->user->name, $r->month, $r->year, $r->gross_pay, $r->deductions, $r->net_pay, $r->status]);
                }
            });
        } elseif ($section === 'performance') {
            fputcsv($file, ['Employee', 'Reviewer', 'Date', 'Rating', 'Feedback']);
            PerformanceReview::with(['user', 'reviewer'])->chunk(100, function($reviews) use ($file) {
                foreach ($reviews as $r) {
                    fputcsv($file, [$r->user->name, $r->reviewer->name, $r->review_date->toDateString(), $r->rating, $r->feedback]);
                }
            });
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PayrollController extends Controller
{
    public function exportCSV()
    {
        $records = PayrollRecord::with('user')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        $response = new StreamedResponse(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee', 'Month', 'Year', 'Gross', 'Bonus', 'Deductions', 'Net Pay', 'Status']);

            foreach ($records as $rec) {
                fputcsv($handle, [
                    $rec->user->name,
                    $rec->month,
                    $rec->year,
                    $rec->gross_pay,
                    $rec->bonus,
                    $rec->deductions,
                    $rec->net_pay,
                    $rec->status,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="payroll-registry.csv"');

        return $response;
    }

    public function autoProcess(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'year' => 'required|string',
        ]);

        $employees = User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))->with(['employeeProfile', 'attendances'])->get();
        $count = 0;

        $engine = new \App\Services\Payroll\PayrollEngine();

        foreach ($employees as $emp) {
            $record = $engine->calculateForEmployee($emp, $validated['month'], $validated['year']);
            if ($record) {
                $count++;
                $emp->notify(new \App\Notifications\PayrollProcessedNotification($record));
            }
        }

        return back()->with('success', "{$count} payroll drafts generated for {$validated['month']} {$validated['year']}. Notifications dispatched.");
    }

    public function generateUserDraft(Request $request, User $user)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'year' => 'required|string',
        ]);

        $engine = new \App\Services\Payroll\PayrollEngine();
        $record = $engine->calculateForEmployee($user, $validated['month'], $validated['year']);

        if (! $record) {
            return back()->with('error', 'Unable to generate draft. Ensure employee has a base salary set.');
        }

        return back()->with('success', "Draft generated for {$validated['month']} {$validated['year']}. Review below.");
    }

    public function finalize(PayrollRecord $record)
    {
        $record->update(['status' => 'Paid']);

        return back()->with('success', 'Payroll record finalized and marked as paid.');
    }

    public function destroy(PayrollRecord $record)
    {
        // Safety: Only allow deleting records in 'Draft' status.
        if ($record->status !== 'Draft') {
            return back()->with('error', 'Only payroll drafts can be deleted. Finalized records are locked for audit.');
        }

        $record->delete();

        return back()->with('success', 'Payroll draft deleted successfully.');
    }

    public function payslip(PayrollRecord $record)
    {
        // Authorization: Employee can only see their own, HR/Admin can see all.
        if (! auth()->user()->hasAnyRole(['HR', 'Admin']) && auth()->id() !== $record->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $record->load(['user.employeeProfile.jobRole.department', 'lineItems']);

        return view('payroll.payslip', compact('record'));
    }

    public function index()
    {
        $employees = User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))->with('payrollRecords')->get();

        return view('payroll.index', compact('employees'));
    }

    public function show(User $user)
    {
        // Authorization: Employee can only see their own, HR/Admin can see all.
        if (! auth()->user()->hasAnyRole(['HR', 'Admin']) && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $records = $user->payrollRecords()->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        return view('payroll.show', compact('user', 'records'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|string',
            'year' => 'required|string',
            'gross_pay' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        // Prevention: Users (including Admins) cannot issue their own payroll records.
        if (auth()->id() == $validated['user_id']) {
            return back()->with('error', 'You are not authorized to issue payroll records to yourself.');
        }

        $bonus = $validated['bonus'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $netPay = $validated['gross_pay'] + $bonus - $deductions;

        PayrollRecord::create(array_merge($validated, [
            'net_pay' => $netPay,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'status' => 'Paid',
        ]));

        return back()->with('success', 'Payroll record calculated and added.');
    }
}

<?php

namespace App\Services\Payroll;

use App\Models\User;
use App\Models\PayrollRecord;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class PayrollEngine
{
    public function calculateForEmployee(User $employee, string $month, string $year): ?PayrollRecord
    {
        $basePay = $employee->employeeProfile->base_salary ?? 0;

        if ($basePay <= 0) {
            return null; // Cannot process without base pay
        }

        $payload = [
            'employee' => $employee,
            'month' => $month,
            'year' => $year,
            'gross_pay' => 0,
            'net_pay' => 0,
            'deductions' => 0,
            'bonus' => 0,
            'line_items' => []
        ];

        return DB::transaction(function () use ($payload) {
            $result = app(Pipeline::class)
                ->send($payload)
                ->through([
                    Calculators\BaseSalaryCalculator::class,
                    Calculators\AttendanceDeductionCalculator::class,
                    Calculators\SSSCalculator::class,
                    Calculators\PhilHealthCalculator::class,
                    Calculators\PagIBIGCalculator::class,
                    Calculators\TaxCalculator::class,
                ])
                ->thenReturn();

            $record = PayrollRecord::updateOrCreate([
                'user_id' => $result['employee']->id,
                'month' => $result['month'],
                'year' => $result['year'],
            ], [
                'gross_pay' => $result['gross_pay'],
                'deductions' => $result['deductions'],
                'bonus' => $result['bonus'],
                'net_pay' => $result['net_pay'],
                'status' => 'Draft'
            ]);

            // 3. Save Breakdown Line Items
            $record->lineItems()->delete(); // Clear existing if re-calculating
            foreach ($result['line_items'] as $item) {
                $record->lineItems()->create([
                    'name' => $item['type'],
                    'amount' => abs($item['amount']),
                    'type' => $item['amount'] >= 0 ? 'Addition' : 'Deduction',
                    'percentage' => $item['percentage'] ?? null,
                ]);
            }

            // 4. 13th Month Pay Logic (Philippine Law)
            if ($result['month'] === 'December') {
                $thirteenthMonth = $result['employee']->employeeProfile->base_salary ?? 0; // Simplified: Full month salary if employed all year
                $record->increment('bonus', $thirteenthMonth);
                $record->increment('net_pay', $thirteenthMonth);

                // In a fully developed app, we'd calculate exactly 1/12 of total earnings
            }

            return $record;
        });
    }
}

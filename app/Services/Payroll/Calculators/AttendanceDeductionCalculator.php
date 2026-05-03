<?php

namespace App\Services\Payroll\Calculators;

use Carbon\Carbon;
use Closure;

class AttendanceDeductionCalculator
{
    public function handle(array $payload, Closure $next)
    {
        $employee = $payload['employee'];
        $month = $payload['month'];
        $year = $payload['year'];
        $basePay = $payload['gross_pay'] ?? 0;

        if ($basePay <= 0) {
            return $next($payload);
        }

        $startDate = Carbon::parse("1 $month $year")->startOfMonth();
        $monthEnd = $startDate->copy()->endOfMonth();
        
        // If processing current month, only expect attendance up to today
        $calculationEndDate = ($startDate->isCurrentMonth() && $startDate->isCurrentYear()) 
            ? Carbon::today() 
            : $monthEnd;

        // Calculate Expected Working Days (weekdays) - inclusive of end date
        $expectedDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $calculationEndDate->copy()->addDay());

        $presentDays = $employee->attendances()
            ->whereBetween('date', [$startDate->toDateString(), $monthEnd->toDateString()])
            ->whereIn('status', ['Present', 'Late']) 
            ->count();

        $absentDays = max(0, $expectedDays - $presentDays);
        $dailyRate = $basePay / max(1, $expectedDays);
        $absenceDeduction = $absentDays * $dailyRate;

        if ($absenceDeduction > 0) {
            $payload['deductions'] += $absenceDeduction;
            $payload['net_pay'] -= $absenceDeduction;
            
            $payload['line_items'][] = [
                'type' => 'Absence Deduction',
                'amount' => -$absenceDeduction
            ];
        }

        return $next($payload);
    }
}

<?php

namespace App\Services\Payroll\Calculators;

use Closure;

/**
 * Calculates SSS (Social Security System) contributions for the Philippines.
 * Accurate for 2025-2026 based on the Social Security Act of 2018 (RA 11199).
 * Total Rate: 14.5% (Employer: 10% | Employee: 4.5%)
 * Maximum Monthly Salary Credit (MSC): ₱30,000
 */
class SSSCalculator
{
    public function handle(array $payload, Closure $next)
    {
        $grossPay = $payload['net_pay'] ?? 0; // Gross after attendance deductions
        
        if ($grossPay <= 0) {
            return $next($payload);
        }

        // Fetch configurable settings or fallback to defaults (RA 11199)
        $rate = \App\Models\Setting::get('statutory.sss_rate', 4.5) / 100;
        $cap = \App\Models\Setting::get('statutory.sss_msc_cap', 30000);

        // MSC is capped at the configured limit
        $msc = min(max(4000, $grossPay), $cap); 
        $employeeContribution = round($msc * $rate, 2);

        $payload['deductions'] += $employeeContribution;
        $payload['net_pay'] -= $employeeContribution;
        
        $payload['line_items'][] = [
            'type' => 'SSS Contribution',
            'amount' => -$employeeContribution,
            'percentage' => $rate * 100
        ];

        // Store for tax calculation (Mandatory statutory contributions are non-taxable)
        $payload['statutory_total'] = ($payload['statutory_total'] ?? 0) + $employeeContribution;

        return $next($payload);
    }
}

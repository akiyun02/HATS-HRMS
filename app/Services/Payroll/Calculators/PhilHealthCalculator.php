<?php

namespace App\Services\Payroll\Calculators;

use Closure;

/**
 * Calculates PhilHealth (Health Insurance) contributions for the Philippines.
 * Accurate for 2025-2026 based on the Universal Health Care Act (RA 11223).
 * Premium Rate: 5% of Basic Monthly Salary
 * Floor: ₱10,000 | Ceiling: ₱100,000
 * Split: 50/50 (Employee share: 2.5%)
 */
class PhilHealthCalculator
{
    public function handle(array $payload, Closure $next)
    {
        $baseSalary = $payload['employee']->employeeProfile->base_salary ?? 0;
        
        if ($baseSalary <= 0) {
            return $next($payload);
        }

        // Fetch configurable settings or fallback to defaults (RA 11223)
        $rate = \App\Models\Setting::get('statutory.philhealth_rate', 2.5) / 100;
        $floor = \App\Models\Setting::get('statutory.philhealth_msc_floor', 10000);
        $ceiling = \App\Models\Setting::get('statutory.philhealth_msc_ceiling', 100000);

        // Calculate based on configured bounds
        $msc = max($floor, min($baseSalary, $ceiling));
        $employeeContribution = round($msc * $rate, 2);

        $payload['deductions'] += $employeeContribution;
        $payload['net_pay'] -= $employeeContribution;
        
        $payload['line_items'][] = [
            'type' => 'PhilHealth Contribution',
            'amount' => -$employeeContribution,
            'percentage' => $rate * 100
        ];

        // Store for tax calculation (Mandatory statutory contributions are non-taxable)
        $payload['statutory_total'] = ($payload['statutory_total'] ?? 0) + $employeeContribution;

        return $next($payload);
    }
}

<?php

namespace App\Services\Payroll\Calculators;

use Closure;

/**
 * Calculates Pag-IBIG (HDMF) contributions for the Philippines.
 * Accurate for 2026 based on the 2024 mandate increase.
 * Monthly Fund Salary (MFS) Ceiling: ₱10,000
 * Rate: 2% Employee / 2% Employer
 * Fixed Employee Contribution: ₱200
 */
class PagIBIGCalculator
{
    public function handle(array $payload, Closure $next)
    {
        $grossPay = $payload['net_pay'] ?? 0;
        
        if ($grossPay <= 0) {
            return $next($payload);
        }

        // Fetch configurable settings or fallback to default
        $employeeContribution = \App\Models\Setting::get('statutory.pagibig_amount', 200);

        $payload['deductions'] += $employeeContribution;
        $payload['net_pay'] -= $employeeContribution;
        
        $payload['line_items'][] = [
            'type' => 'Pag-IBIG Contribution',
            'amount' => -$employeeContribution
        ];

        // Store for tax calculation (Mandatory statutory contributions are non-taxable)
        $payload['statutory_total'] = ($payload['statutory_total'] ?? 0) + $employeeContribution;

        return $next($payload);
    }
}

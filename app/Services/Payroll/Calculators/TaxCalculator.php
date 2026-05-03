<?php

namespace App\Services\Payroll\Calculators;

use Closure;

/**
 * Calculates Philippine Withholding Tax based on the TRAIN Law (Republic Act 10963).
 * Accurate for 2026 using the Phase 2 (2023 onwards) Revised Personal Income Tax Table.
 */
class TaxCalculator
{
    public function handle(array $payload, Closure $next)
    {
        // Taxable Income = Gross Pay - Absences - Mandatory Statutory Contributions (SSS, PHIC, HDMF)
        // Statutory total was accumulated by previous pipes in the pipeline.
        $taxableIncome = $payload['net_pay'] ?? 0;

        if ($taxableIncome <= 0) {
            return $next($payload);
        }

        $tax = 0;

        /**
         * RA 10963 (TRAIN Law) Monthly Withholding Tax Table (2023 - present):
         * 1. Not over ₱20,833: 0%
         * 2. Over ₱20,833 but not over ₱33,333: 15% of excess over ₱20,833
         * 3. Over ₱33,333 but not over ₱66,667: ₱1,875 + 20% of excess over ₱33,333
         * 4. Over ₱66,667 but not over ₱166,667: ₱8,541.67 + 25% of excess over ₱66,667
         * 5. Over ₱166,667 but not over ₱666,667: ₱33,541.67 + 30% of excess over ₱166,667
         * 6. Over ₱666,667: ₱183,541.67 + 35% of excess over ₱666,667
         */
        if ($taxableIncome <= 20833) {
            $tax = 0;
        } elseif ($taxableIncome <= 33333) {
            $tax = ($taxableIncome - 20833) * 0.15;
        } elseif ($taxableIncome <= 66667) {
            $tax = 1875 + (($taxableIncome - 33333) * 0.20);
        } elseif ($taxableIncome <= 166667) {
            $tax = 8541.67 + (($taxableIncome - 66667) * 0.25);
        } elseif ($taxableIncome <= 666667) {
            $tax = 33541.67 + (($taxableIncome - 166667) * 0.30);
        } else {
            $tax = 183541.67 + (($taxableIncome - 666667) * 0.35);
        }

        $tax = round($tax, 2);

        if ($tax > 0) {
            $payload['deductions'] += $tax;
            $payload['net_pay'] -= $tax;
            
            $payload['line_items'][] = [
                'type' => 'Withholding Tax (TRAIN Law)',
                'amount' => -$tax
            ];
        }

        return $next($payload);
    }
}

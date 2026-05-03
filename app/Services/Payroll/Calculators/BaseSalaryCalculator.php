<?php

namespace App\Services\Payroll\Calculators;

use Closure;

class BaseSalaryCalculator
{
    public function handle(array $payload, Closure $next)
    {
        $employee = $payload['employee'];
        $basePay = $employee->employeeProfile->base_salary ?? 0;

        $payload['gross_pay'] = $basePay;
        $payload['net_pay'] = $basePay;
        $payload['deductions'] = 0;
        
        $payload['line_items'][] = [
            'type' => 'Base',
            'amount' => $basePay
        ];

        return $next($payload);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AccrueLeaves extends Command
{
    protected $signature = 'leaves:accrue {--year=} {--month=}';
    protected $description = 'Calculate and accrue monthly leaves for all active employees based on Philippine Labor Law and company policies.';

    public function handle()
    {
        $year = $this->option('year') ?? Carbon::now()->year;
        $month = $this->option('month') ?? Carbon::now()->month;

        $this->info("Accruing leaves for {$year}-{$month}...");

        $leaveTypes = LeaveType::all();
        $employees = User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))->get();

        DB::transaction(function () use ($employees, $leaveTypes, $year) {
            foreach ($employees as $employee) {
                foreach ($leaveTypes as $leaveType) {
                    $balance = LeaveBalance::firstOrCreate(
                        ['user_id' => $employee->id, 'leave_type_id' => $leaveType->id, 'year' => $year],
                        ['accrued_days' => 0, 'used_days' => 0, 'forfeited_days' => 0]
                    );

                    $accrual = 0;

                    // Accrual Logic based on Type
                    switch ($leaveType->name) {
                        case 'Vacation Leave':
                        case 'Sick Leave':
                            // Standard monthly accrual (15 days / 12 = 1.25)
                            $accrual = round($leaveType->max_days / 12, 2);
                            break;

                        case 'Service Incentive Leave (SIL)':
                            // Mandated after 1 year of service. 
                            // Simplified: Grant in full if they've been here over a year.
                            $yearsOfService = $employee->employeeProfile?->joining_date?->diffInYears(now()) ?? 0;
                            if ($yearsOfService >= 1 && $balance->accrued_days < 5) {
                                $balance->accrued_days = 5;
                            }
                            break;

                        case 'Maternity Leave':
                        case 'Paternity Leave':
                        case 'Solo Parent Leave':
                        case 'VAWC Leave':
                        case 'Magna Carta for Women':
                            // These are granted in full as statutory entitlements
                            $balance->accrued_days = $leaveType->max_days;
                            break;

                        default:
                            // Default to monthly accrual for others
                            $accrual = round($leaveType->max_days / 12, 2);
                    }

                    // Apply accrual if defined
                    if ($accrual > 0) {
                        if ($balance->accrued_days + $accrual > $leaveType->max_days) {
                            $balance->accrued_days = $leaveType->max_days;
                        } else {
                            $balance->accrued_days += $accrual;
                        }
                    }

                    $balance->save();
                }
            }
        });

        $this->info("Successfully processed Philippine Law-compliant leaves for " . $employees->count() . " employees.");
    }
}

<?php

namespace App\Services\Leave;

use App\Models\LeaveLedgerEntry;
use App\Models\LeavePolicy;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveEntitlementService
{
    /**
     * Assign a leave policy to an employee and initialize entitlements.
     */
    public function assignPolicy(User $user, LeavePolicy $policy): void
    {
        DB::transaction(function () use ($user, $policy) {
            $user->employeeProfile->update([
                'leave_policy_id' => $policy->id,
            ]);

            $this->initializeEntitlements($user, $policy);
        });
    }

    /**
     * Initialize entitlements based on the assigned policy.
     */
    protected function initializeEntitlements(User $user, LeavePolicy $policy): void
    {
        $hireDate = $user->employeeProfile->joining_date ?? now();
        $year = now()->year;

        foreach ($policy->leaveTypes as $leaveType) {
            $annualDays = $leaveType->pivot->annual_days;
            $accrualType = $leaveType->pivot->accrual_type;

            $amount = match ($accrualType) {
                'fixed' => $annualDays,
                'prorated' => $this->calculateProratedDays($annualDays, $hireDate),
                'monthly' => $this->calculateMonthlyAccrual($annualDays, $hireDate),
                default => $annualDays,
            };

            if ($amount > 0) {
                $this->createLedgerEntry($user, $leaveType->id, $amount, 'allocation', "Initial entitlement allocation for policy: {$policy->name}");
            }
        }
    }

    /**
     * Calculate prorated days from hire date to end of year.
     */
    protected function calculateProratedDays(float $annualDays, Carbon $hireDate): float
    {
        $endOfYear = $hireDate->copy()->endOfYear();
        
        // If hired before this year, full days
        if ($hireDate->year < now()->year) {
            return $annualDays;
        }

        $totalDaysInYear = $hireDate->daysInYear;
        $remainingDays = $hireDate->diffInDays($endOfYear) + 1;

        return round(($annualDays / $totalDaysInYear) * $remainingDays, 2);
    }

    /**
     * Calculate monthly accrual from hire date to current month.
     */
    protected function calculateMonthlyAccrual(float $annualDays, Carbon $hireDate): float
    {
        if ($hireDate->year < now()->year) {
            $months = now()->month;
        } else {
            $months = max(0, $hireDate->diffInMonths(now()) + 1);
        }

        $monthlyRate = $annualDays / 12;
        return round($monthlyRate * $months, 2);
    }

    /**
     * Regularize an employee and switch to a regular policy.
     */
    public function regularizeEmployee(User $user, LeavePolicy $newPolicy): void
    {
        DB::transaction(function () use ($user, $newPolicy) {
            $user->employeeProfile->update([
                'is_regularized' => true,
                'leave_policy_id' => $newPolicy->id,
            ]);

            // Re-initialize entitlements for the new policy
            $this->initializeEntitlements($user, $newPolicy);
        });
    }

    /**
     * Manual adjustment of leave balance.
     */
    public function adjustBalance(User $user, int $leaveTypeId, float $amount, string $reason): void
    {
        $this->createLedgerEntry($user, $leaveTypeId, $amount, 'adjustment', $reason);
    }

    /**
     * Create a ledger entry and update the legacy leave_balances table for compatibility.
     */
    protected function createLedgerEntry(User $user, int $leaveTypeId, float $amount, string $type, string $description): LeaveLedgerEntry
    {
        return DB::transaction(function () use ($user, $leaveTypeId, $amount, $type, $description) {
            $entry = LeaveLedgerEntry::create([
                'user_id' => $user->id,
                'leave_type_id' => $leaveTypeId,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
            ]);

            // Sync with legacy leave_balances table
            $balance = $user->leaveBalances()->where('leave_type_id', $leaveTypeId)->where('year', now()->year)->first();
            
            if (!$balance) {
                $user->leaveBalances()->create([
                    'leave_type_id' => $leaveTypeId,
                    'year' => now()->year,
                    'accrued_days' => $amount > 0 ? $amount : 0,
                    'used_days' => $amount < 0 ? abs($amount) : 0,
                    'forfeited_days' => 0,
                ]);
            } else {
                if ($amount > 0) {
                    $balance->increment('accrued_days', $amount);
                } else {
                    $balance->increment('used_days', abs($amount));
                }
            }

            return $entry;
        });
    }

    /**
     * Get the current effective balance for a user and leave type.
     */
    public function getBalance(User $user, int $leaveTypeId): float
    {
        return LeaveLedgerEntry::where('user_id', $user->id)
            ->where('leave_type_id', $leaveTypeId)
            ->sum('amount');
    }
}

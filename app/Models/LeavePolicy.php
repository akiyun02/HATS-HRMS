<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeavePolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_probationary',
        'is_default',
    ];

    public function leaveTypes(): BelongsToMany
    {
        return $this->belongsToMany(LeaveType::class, 'leave_policy_leave_type')
            ->withPivot(['annual_days', 'accrual_type', 'carry_over_limit'])
            ->withTimestamps();
    }

    public function employeeProfiles(): HasMany
    {
        return $this->hasMany(EmployeeProfile::class);
    }
}

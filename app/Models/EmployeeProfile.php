<?php

namespace App\Models;

use App\Traits\Auditable;
use Database\Factories\EmployeeProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    /** @use HasFactory<EmployeeProfileFactory> */
    use Auditable, HasFactory;

    protected $fillable = [
        'user_id',
        'job_role_id',
        'leave_policy_id',
        'base_salary',
        'employee_id',
        'joining_date',
        'probation_end_date',
        'is_regularized',
        'phone',
        'address',
        'gender',
        'birthday',
        'marital_status',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'birthday' => 'date',
            'probation_end_date' => 'date',
            'is_regularized' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function leavePolicy(): BelongsTo
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}

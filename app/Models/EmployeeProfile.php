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

    protected static function booted(): void
    {
        static::creating(function (EmployeeProfile $profile) {
            if (empty($profile->employee_id)) {
                $lastProfile = static::where('employee_id', 'like', 'EMP-%')
                    ->orderByRaw('CAST(SUBSTRING(employee_id FROM 5) AS INTEGER) DESC')
                    ->first();

                if ($lastProfile) {
                    $lastNumber = (int) substr($lastProfile->employee_id, 4);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                $profile->employee_id = 'EMP-'.str_pad((string) $newNumber, 3, '0', STR_PAD_LEFT);
            }
        });
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

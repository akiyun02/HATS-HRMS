<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'year',
        'accrued_days',
        'used_days',
        'forfeited_days',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getAvailableDaysAttribute()
    {
        return max(0, $this->accrued_days - $this->used_days - $this->forfeited_days);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'date',
        'requested_clock_in',
        'requested_clock_out',
        'reason',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'requested_clock_in' => 'datetime:H:i',
        'requested_clock_out' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}

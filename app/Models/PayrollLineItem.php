<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollLineItem extends Model
{
    protected $fillable = [
        'payroll_record_id',
        'name',
        'amount',
        'type',
        'percentage',
    ];

    public function payrollRecord(): BelongsTo
    {
        return $this->belongsTo(PayrollRecord::class);
    }
}

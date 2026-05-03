<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollRecord extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'month', 'year', 'gross_pay', 'bonus', 'deductions', 'net_pay', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lineItems()
    {
        return $this->hasMany(PayrollLineItem::class);
    }
}

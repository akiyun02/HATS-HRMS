<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'name',
        'api_token',
        'location',
        'is_active',
    ];

    protected $hidden = [
        'api_token',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(BiometricLog::class);
    }
}

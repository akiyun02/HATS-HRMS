<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollComponent extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_taxable',
        'description',
    ];

    public function employeeCompensations(): HasMany
    {
        return $this->hasMany(EmployeeCompensation::class);
    }
}

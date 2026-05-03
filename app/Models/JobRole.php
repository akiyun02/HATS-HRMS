<?php

namespace App\Models;

use Database\Factories\JobRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobRole extends Model
{
    /** @use HasFactory<JobRoleFactory> */
    use HasFactory;

    protected $fillable = ['department_id', 'name', 'description'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employeeProfiles(): HasMany
    {
        return $this->hasMany(EmployeeProfile::class);
    }
}

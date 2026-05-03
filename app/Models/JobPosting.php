<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status'];

    public function applicants()
    {
        return $this->hasMany(JobApplicant::class);
    }
}

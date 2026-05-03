<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicant extends Model
{
    protected $fillable = [
        'job_posting_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'resume_path',
        'status',
        'cover_letter',
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function evaluations()
    {
        return $this->hasMany(ApplicantEvaluation::class);
    }
}

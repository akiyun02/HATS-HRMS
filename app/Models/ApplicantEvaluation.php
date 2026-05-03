<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantEvaluation extends Model
{
    protected $fillable = [
        'job_applicant_id',
        'evaluator_id',
        'rating',
        'notes',
    ];

    public function applicant()
    {
        return $this->belongsTo(JobApplicant::class, 'job_applicant_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}

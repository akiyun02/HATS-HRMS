<?php

namespace App\Models;

use Database\Factories\PerformanceReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    /** @use HasFactory<PerformanceReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewer_id',
        'review_date',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'review_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}

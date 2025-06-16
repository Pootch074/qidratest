<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodAssessor extends Model
{
    protected $table = 'period_assessors';
    protected $primaryKey = 'id';
    protected $fillable = ['period_assessment_id', 'user_id'];

    public function period_assessment(): BelongsTo
    {
        return $this->belongsTo(PeriodAssessment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

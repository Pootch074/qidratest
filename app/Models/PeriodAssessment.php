<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodAssessment extends Model
{
    protected $table = 'period_assessments';
    protected $primaryKey = 'id';
    protected $fillable = ['period_id', 'lgu_id', 'user_id', 'assessment_start_date', 'assessment_end_date', 'status'];
    protected $dates = ['assessment_start_date', 'assessment_end_date'];

    const STATUS_PENDING = 'pending';
    const STATUS_ONGOING = 'on-going';
    const STATUS_COMPLETED = 'completed';
    const STATUS_INCOMPLETE = 'incomplete';

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
    public function lgu(): BelongsTo
    {
        return $this->belongsTo(Lgu::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

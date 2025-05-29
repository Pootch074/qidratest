<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriodAssessment extends Model
{
    protected $table = 'period_assessments';
    protected $primaryKey = 'id';
    protected $fillable = ['period_id', 'lgu_id', 'rmt_id', 'assessment_start_date', 'assessment_end_date', 'status'];
    protected $dates = ['assessment_start_date', 'assessment_end_date'];

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
        return $this->belongsTo(User::class, 'rmt_id');
    }
}

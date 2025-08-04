<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentRecommendation extends Model
{
    //
    protected $fillable = [
        'period_id',
        'lgu_id',
        'questionnaire_id',
        'recommendations',
        'user_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestionnaire extends Model
{
    //
    protected $fillable = [
        'period_id',
        'lgu_id',
        'questionnaire_id',
        'questionnaire_level_id',
        'remarks',
        'recommendations',
        'number_of_beneficiaries'
    ];
}

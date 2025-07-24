<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionnaireLevel;

class AssessmentQuestionnaire extends Model
{
    protected $fillable = [
        'period_id',
        'lgu_id',
        'questionnaire_id',
        'questionnaire_level_id',
        'remarks',
        'recommendations',
        'number_of_beneficiaries',
        'user_id'
    ];

    public function questionnaireLevel()
    {
        return $this->belongsTo(QuestionnaireLevel::class, 'questionnaire_level_id');
    }

    // In App\Models\AssessmentQuestionnaire.php
    public function level()
    {
        return $this->belongsTo(QuestionnaireLevel::class, 'questionnaire_level_id');
    }


}

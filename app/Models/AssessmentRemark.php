<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentRemark extends Model
{
    //
    protected $fillable = [
        'period_id',
        'lgu_id',
        'questionnaire_id',
        'remarks',
        'user_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionnaireTree extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'questionnaire_name',
        'effectivity_date',
        'status',
    ];

    protected $dates = ['deleted_at']; // Ensure Laravel treats it as a date
}

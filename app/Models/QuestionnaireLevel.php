<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'questionnaire_id', 'level', 'remarks', 'created_at', 'updated_at'
    ];
}

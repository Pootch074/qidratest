<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeansOfVerification extends Model
{
    use SoftDeletes;
    
    use HasFactory;
    protected $fillable = [
        'id', 'questionnaire_id', 'means', 'created_at', 'updated_at', 'deleted_at'
    ];
}



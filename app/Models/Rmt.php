<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rmt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rmts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'designation',
        'years_current_position',
        'salary_grade'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ]; // Ensure Laravel treats it as a date


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LguUser extends Model
{
    //
    protected $fillable = ['user_id', 'lgu_id'];

    public function lgu(): BelongsTo
    {
        return $this->belongsTo(Lgu::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

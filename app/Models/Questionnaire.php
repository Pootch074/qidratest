<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Questionnaire extends Model
{
    //
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ENDED = 'ended';

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Questionnaire::class, 'parent_id', 'id');
    }

}

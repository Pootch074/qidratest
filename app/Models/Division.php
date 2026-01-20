<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'division_id');
    }
}

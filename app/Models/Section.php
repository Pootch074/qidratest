<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'section_id');
    }

    public function steps()
    {
        return $this->hasMany(Step::class, 'section_id');
    }
}

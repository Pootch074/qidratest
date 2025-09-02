<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}

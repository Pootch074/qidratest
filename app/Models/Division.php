<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public function office()
    {
        return $this->belongsTo(\App\Models\Office::class, 'office_id');
    }

}

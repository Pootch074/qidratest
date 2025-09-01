<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public function windows()
    {
        return $this->hasMany(Window::class, 'step_id');
    }
    public function section()
    {
        return $this->belongsTo(\App\Models\Section::class, 'section_id');
    }


}

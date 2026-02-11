<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['step_number', 'step_name', 'section_id'];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'step_id');
    }
}

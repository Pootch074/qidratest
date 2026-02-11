<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['step_id', 'category_name'];

    public function step()
    {
        return $this->belongsTo(Step::class, 'step_id');
    }
    public function window()
    {
        return $this->hasMany(Window::class, 'category_id');
    }
}

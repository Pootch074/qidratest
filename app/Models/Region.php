<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'regions';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
    protected $dates = ['deleted_at']; // Ensure Laravel treats it as a date
}

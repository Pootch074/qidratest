<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{

    protected $table = 'periods';
    protected $primaryKey = 'id';
    protected $fillable = ['questionnaire_id', 'name', 'start_date', 'end_date', 'status', 'user_id'];
    protected $dates = ['start_date', 'end_date'];
}

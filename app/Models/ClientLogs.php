<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'fullname',
        'section_id',
        'phone_number',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}

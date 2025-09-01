<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'client_type',
        'step_id',
        'window_id',
        'section_id',
        'queue_status',
    ];

    // In Transaction.php (Model)
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

}

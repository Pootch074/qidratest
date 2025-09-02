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
    // Add accessor for formatted_number
    public function getFormattedNumberAttribute()
    {
        $prefix = strtolower($this->client_type) === 'priority' ? 'P' : 'R';
        return $prefix . str_pad($this->queue_number, 3, '0', STR_PAD_LEFT);
    }

    // Add accessor for client styling
    public function getStyleClassAttribute()
    {
        $styleMap = [
            'priority' => 'bg-[#d92d27]',
            'regular'  => 'bg-[#150e60]',
            'vip'      => 'bg-purple-500 text-white',
            'guest'    => 'bg-green-500 text-white',
        ];

        return $styleMap[strtolower($this->client_type)] ?? 'bg-gray-300 text-black';
    }
}

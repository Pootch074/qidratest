<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'queue_number',
        'section_id',
        'step_id',
        'window_id',
        'client_type',
        'ticket_status',
        'queue_status'
    ];

    // In Transaction.php (Model)
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    // Add accessor for formatted_number
    public function getFormattedNumberAttribute()
    {
        $map = [
            'priority' => 'P',
            'regular'  => 'R',
            'returnee' => 'T', // e.g. T001 for Returnee
        ];
        $prefix = $map[strtolower($this->client_type)] ?? 'R';
        return $prefix . str_pad($this->queue_number, 3, '0', STR_PAD_LEFT);
    }


    // Add accessor for client styling
    public function getStyleClassAttribute()
    {
        $styleMap = [
            'priority' => 'bg-[#d92d27] text-white',
            'regular'  => 'bg-[#150e60] text-white',
            'returnee' => 'bg-yellow-600 text-white',
            'vip'      => 'bg-purple-500 text-white',
            'guest'    => 'bg-green-500 text-white',
        ];

        return $styleMap[strtolower($this->client_type)] ?? 'bg-gray-300 text-black';
    }




    public function step()
    {
        return $this->belongsTo(Step::class);
    }
    // Relationship to Window
    public function window()
    {
        return $this->belongsTo(Window::class);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ClientType;
use App\Enums\QueueStatus;  

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'queue_number',
        'full_name',
        'client_type',
        'step_id',
        'window_id',
        'recall_count',
        'ticket_status',
        'queue_status',
    ];

    protected $casts = [
        'client_type' => ClientType::class,
        'queue_status' => QueueStatus::class,
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function window()
    {
        return $this->belongsTo(Window::class);
    }

    public function getFormattedNumberAttribute()
    {
        $map = [
            'priority' => 'P',
            'regular' => 'R',
            'returnee' => 'T',
        ];

        $clientTypeValue = $this->client_type instanceof ClientType
            ? strtolower($this->client_type->value)
            : strtolower($this->client_type);

        $prefix = $map[$clientTypeValue] ?? 'R';

        return $prefix . str_pad($this->queue_number, 3, '0', STR_PAD_LEFT);
    }

    public function getStyleClassAttribute()
    {
        $styleMap = [
            'priority' => 'bg-[#d92d27] text-white',
            'regular' => 'bg-[#150e60] text-white',
            'returnee' => 'bg-yellow-600 text-white',
            'vip' => 'bg-purple-500 text-white',
            'guest' => 'bg-green-500 text-white',
        ];

        $clientTypeValue = $this->client_type instanceof ClientType
            ? strtolower($this->client_type->value)
            : strtolower($this->client_type);

        return $styleMap[$clientTypeValue] ?? 'bg-gray-300 text-black';
    }

    public function scopeToday(Builder $query)
    {
        return $query->whereDate('created_at', Carbon::today('Asia/Manila'));
    }

    public function scopeYesterday(Builder $query)
    {
        return $query->whereDate('created_at', Carbon::yesterday('Asia/Manila'));
    }

    public function scopeForSection(Builder $query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeExcludeSections(Builder $query, $sectionIds)
    {
        return $query->whereNotIn('section_id', $sectionIds);
    }

    public function scopeDeferred(Builder $query)
    {
        return $query->where('queue_status', 'deferred');
    }

    public function scopeWaiting(Builder $query)
    {
        return $query->where('queue_status', 'waiting');
    }

    public function scopeIssued(Builder $query)
    {
        return $query->where('ticket_status', 'issued');
    }

    public function scopeWithoutTicket(Builder $query)
    {
        return $query->whereNull('ticket_status');
    }

    public function scopeWithQueueNumber(Builder $query)
    {
        return $query->where('queue_number', '>', 0);
    }

    public function scopeOfClientType($query, $type)
    {
        $value = $type instanceof ClientType ? $type->value : strtolower($type);
        return $query->where('client_type', $value);
    }

}

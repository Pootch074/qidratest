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


    public function getQueueLabelAttribute()
    {
        $clientType = strtolower($this->client_type->value ?? $this->client_type ?? '');
        $prefix = match ($clientType) {
            'priority' => 'P',
            'regular'  => 'R',
            'deferred' => 'D',
            default    => strtoupper(substr($clientType, 0, 1)),
        };

        return $prefix . str_pad($this->queue_number, 3, '0', STR_PAD_LEFT);
    }

    public function getClientTypeBadgeAttribute()
    {
        return match (strtolower($this->client_type->value ?? $this->client_type ?? '')) {
            'priority' => '<span class="px-2 py-1 rounded-full text-white text-xs bg-[#ee1c25]">Priority</span>',
            'regular'  => '<span class="px-2 py-1 rounded-full text-white text-xs bg-[#2e3192]">Regular</span>',
            'deferred' => '<span class="px-2 py-1 rounded-full text-black text-xs bg-[#fef200]">Returnee</span>',
            default    => '<span class="px-2 py-1 rounded-full text-gray-700 bg-gray-200 text-xs">Unknown</span>',
        };
    }

    public function getQueueStatusBadgeAttribute(): string
    {
        $statusColors = [
            'waiting' => 'bg-yellow-400 text-yellow-900',
            'pending' => 'bg-orange-400 text-orange-900',
            'serving' => 'bg-green-500 text-white',
            'completed' => 'bg-gray-500 text-white',
        ];

        $statusValue = $this->queue_status instanceof QueueStatus
            ? strtolower($this->queue_status->value)
            : strtolower($this->queue_status ?? '');

        $statusClass = $statusColors[$statusValue] ?? 'bg-gray-300 text-gray-700';

        return sprintf(
            '<span class="px-2 py-1 rounded-full text-xs font-semibold %s">%s</span>',
            $statusClass,
            ucfirst($statusValue ?: 'Unknown')
        );
    }


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

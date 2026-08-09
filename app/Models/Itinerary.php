<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    protected $fillable = ['trip_id', 'day_number', 'title', 'location', 'scheduled_time', 'status', 'notes', 'sort_order'];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'planned' => 'bg-blue-100 text-blue-700',
            'on_progress' => 'bg-yellow-100 text-yellow-700',
            'done' => 'bg-green-100 text-green-700',
            'skip' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'planned' => 'Planned',
            'on_progress' => 'On Progress',
            'done' => 'Done',
            'skip' => 'Skipped',
            default => $this->status,
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'planned' => '📋',
            'on_progress' => '🔄',
            'done' => '✅',
            'skip' => '⏭️',
            default => '📋',
        };
    }
}

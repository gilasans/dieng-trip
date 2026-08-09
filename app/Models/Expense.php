<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = ['trip_id', 'member_id', 'category', 'amount', 'description', 'receipt_photo'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function splitAmount(int $totalMembers): float
    {
        return $totalMembers > 0 ? $this->amount / $totalMembers : 0;
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'BBM' => '⛽',
            'Tol' => '🛣️',
            'Makan' => '🍽️',
            'Tiket' => '🎫',
            'Parkir' => '🅿️',
            'Lainnya' => '📦',
            default => '💰',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'BBM' => 'bg-orange-100 text-orange-700',
            'Tol' => 'bg-blue-100 text-blue-700',
            'Makan' => 'bg-green-100 text-green-700',
            'Tiket' => 'bg-purple-100 text-purple-700',
            'Parkir' => 'bg-yellow-100 text-yellow-700',
            'Lainnya' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

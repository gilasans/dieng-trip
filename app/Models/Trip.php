<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'total_fund', 'vehicle_info', 'notes'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_fund' => 'decimal:2',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function totalExpenses(): float
    {
        return $this->expenses()->sum('amount');
    }

    public function remainingFund(): float
    {
        return $this->total_fund - $this->totalExpenses();
    }

    public function getDaysCountAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}

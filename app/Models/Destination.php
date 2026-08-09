<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['name', 'description', 'image', 'best_time', 'estimated_duration', 'latitude', 'longitude'];
}

<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        Trip::create([
            'name' => 'Liburan Keluarga ke Dieng',
            'start_date' => '2026-08-22',
            'end_date' => '2026-08-24',
            'total_fund' => 6500000,
            'vehicle_info' => '1 Mobil Calya',
            'notes' => 'Berangkat jam 01:00. Patungan: 3 KK x 1,5 Juta + 1 KK x 2 Juta',
        ]);
    }
}

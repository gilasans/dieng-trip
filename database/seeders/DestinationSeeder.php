<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Bukit Sikunir',
                'description' => 'Spot sunrise terbaik di Dieng yang dijuluki "Negeri di Atas Awan". Pemandangan golden sunrise dengan lautan awan yang spektakuler di ketinggian 2.263 mdpl.',
                'image' => 'destinations/sikunir.jpg',
                'best_time' => '04:30 - 07:00 (Sunrise)',
                'estimated_duration' => '2-3 jam',
                'latitude' => -7.2114,
                'longitude' => 109.9053,
            ],
            [
                'name' => 'Telaga Warna',
                'description' => 'Danau vulkanik yang berubah warna karena kandungan sulfur dan mineral. Warnanya bisa berubah dari hijau, biru, hingga kekuningan tergantung kadar belerang.',
                'image' => 'destinations/telaga-warna.jpg',
                'best_time' => '08:00 - 11:00 (Pagi)',
                'estimated_duration' => '1-2 jam',
                'latitude' => -7.2167,
                'longitude' => 109.9167,
            ],
            [
                'name' => 'Kawah Sikidang',
                'description' => 'Kawah vulkanik aktif dengan lumpur panas mendidih dan uap belerang. Dinamakan Sikidang karena titik semburannya berpindah-pindah seperti kijang.',
                'image' => 'destinations/kawah-sikidang.jpg',
                'best_time' => '08:00 - 14:00 (Pagi-Siang)',
                'estimated_duration' => '1 jam',
                'latitude' => -7.2203,
                'longitude' => 109.9119,
            ],
            [
                'name' => 'Candi Arjuna',
                'description' => 'Kompleks candi Hindu tertua di Jawa yang dibangun abad ke-8. Terdiri dari 5 candi yang dinamai tokoh pewayangan Mahabharata.',
                'image' => 'destinations/candi-arjuna.jpg',
                'best_time' => '08:00 - 15:00 (Pagi-Siang)',
                'estimated_duration' => '1 jam',
                'latitude' => -7.2069,
                'longitude' => 109.9108,
            ],
            [
                'name' => 'Telaga Menjer',
                'description' => 'Danau alami seluas 70 hektar di kaki Gunung Sindoro. Airnya jernih dan tenang, cocok untuk piknik keluarga sambil menikmati udara sejuk pegunungan.',
                'image' => 'destinations/telaga-menjer.jpg',
                'best_time' => '09:00 - 15:00 (Siang)',
                'estimated_duration' => '1-2 jam',
                'latitude' => -7.2833,
                'longitude' => 109.9167,
            ],
            [
                'name' => 'Swiss Van Java',
                'description' => 'Kawasan wisata yang menawarkan pemandangan mirip Swiss di Eropa. Hamparan hijau dengan latar belakang pegunungan yang memukau.',
                'image' => 'destinations/swiss-van-java.jpg',
                'best_time' => '07:00 - 10:00 (Pagi)',
                'estimated_duration' => '1-2 jam',
                'latitude' => -7.2100,
                'longitude' => 109.9200,
            ],
            [
                'name' => 'Taman Langit',
                'description' => 'Destinasi wisata baru dengan spot foto instagramable di ketinggian. Menawarkan pemandangan 360 derajat dataran tinggi Dieng yang menakjubkan.',
                'image' => 'destinations/taman-langit.jpg',
                'best_time' => '09:00 - 15:00 (Siang)',
                'estimated_duration' => '1-2 jam',
                'latitude' => -7.2150,
                'longitude' => 109.9080,
            ],
            [
                'name' => 'Pintu Langit',
                'description' => 'Spot foto ikonik dengan gerbang berlatar langit dan awan. Terletak di ketinggian dengan view dataran tinggi yang memesona.',
                'image' => 'destinations/pintu-langit.jpg',
                'best_time' => '08:00 - 14:00 (Pagi-Siang)',
                'estimated_duration' => '1-2 jam',
                'latitude' => -7.2120,
                'longitude' => 109.9060,
            ],
            [
                'name' => 'Banyu Alam',
                'description' => 'Wisata alam dengan air terjun dan kolam alami yang menyegarkan. Suasana hutan tropis yang asri menjadi daya tarik tersendiri bagi pengunjung.',
                'image' => 'destinations/banyu-alam.jpg',
                'best_time' => '08:00 - 14:00 (Pagi-Siang)',
                'estimated_duration' => '2 jam',
                'latitude' => -7.2200,
                'longitude' => 109.9100,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}

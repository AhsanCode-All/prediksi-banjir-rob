<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelurahan;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        $kelurahan = [
            ['Ancol', 0.79, 0.555],
            ['Cilincing', 0.14, 3.052],
            ['Kalibaru', 4.59, 0.370],
            ['Kamal Muara', 6.68, 1.467],
            ['Kapuk Muara', 4.02, 3.266],
            ['Kebon Bawang', 1.55, 1.024],
            ['Kelapa Gading Barat', 8.80, 5.628],
            ['Kelapa Gading Timur', 4.89, 6.817],
            ['Koja', 1.14, 0.823],
            ['Lagoa', 1.39, 0.929],
            ['Marunda', 0.20, 1.719],
            ['Pademangan Barat', 2.24, 1.286],
            ['Pademangan Timur', -0.76, 0.853],
            ['Papanggo', 0.08, 1.296],
            ['Pegangsaan Dua', 4.00, 5.970],
            ['Pejagalan', 9.14, 2.876],
            ['Penjaringan', 7.91, 1.124],
            ['Pluit', 1.42, 1.118],
            ['Rawa Badak Selatan', 4.17, 2.386],
            ['Rawa Badak Utara', 7.42, 1.470],
            ['Rorotan', 1.82, 5.820],
            ['Semper Barat', 3.74, 2.937],
            ['Semper Timur', 4.54, 1.182],
            ['Sukapura', 3.01, 4.777],
            ['Sungai Bambu', -0.97, 2.590],
            ['Sunter Agung', 11.17, 2.043],
            ['Sunter Jaya', 4.38, 3.179],
            ['Tanjung Priok', 5.37, 1.460],
            ['Tugu Selatan', 3.01, 2.981],
            ['Tugu Utara', 1.81, 1.494],
            ['Warakas', 2.09, 0.622],
        ];

        foreach ($kelurahan as $index => $data) {
            Kelurahan::updateOrCreate(
                ['kode_wilayah' => 'KLU' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'nama_kelurahan' => $data[0],
                    'elevasi' => $data[1],
                    'jarak_pantai' => $data[2],
                ]
            );
        }
    }
}
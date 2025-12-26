<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MejaSeeder extends Seeder
{
    public function run()
    {
        $userId = 1; // Pastikan user dengan ID 1 sudah ada

        for ($i = 1; $i <= 10; $i++) {
            DB::table('meja')->insert([
                'user_id'    => $userId,
                'nama_meja'  => 'Spot ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status'     => 'tersedia',
                // Simulasi variasi harga rata-rata pemancingan
                'tarif_pagi'  => 35000 + (rand(0, 5) * 1000), // Range 35rb - 40rb
                'tarif_siang' => 30000 + (rand(0, 5) * 1000), // Range 30rb - 35rb
                'tarif_sore'  => 40000 + (rand(0, 5) * 1000), // Range 40rb - 45rb
                'tarif_malam' => 50000 + (rand(0, 10) * 1000), // Range 50rb - 60rb
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
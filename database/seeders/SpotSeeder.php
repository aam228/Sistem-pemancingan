<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SpotSeeder extends Seeder
{
    public function run(): void
    {

        $user = User::find(2) ?? User::first();
        
        $spots = [];

        for ($i = 1; $i <= 15; $i++) {
            $spots[] = [
                'user_id'     => $user->id,
                'nama_spot'   => 'Lapak ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status'      => 'tersedia', 
                'tarif_pagi'  => 25000,
                'tarif_siang' => 20000,
                'tarif_sore'  => 30000,
                'tarif_malam' => 40000,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        for ($i = 1; $i <= 5; $i++) {
            $spots[] = [
                'user_id'     => $user->id,
                'nama_spot'   => 'VIP Spot ' . $i,
                'status'      => 'tersedia', 
                'tarif_pagi'  => 50000,
                'tarif_siang' => 45000,
                'tarif_sore'  => 60000,
                'tarif_malam' => 75000,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('spots')->insert($spots);
    }
}
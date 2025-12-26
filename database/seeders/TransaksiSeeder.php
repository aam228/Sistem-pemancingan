<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        $userId = 1;
        $pelanggan = ['Budi Santoso', 'Agus Prayogo', 'Hendra Wijaya', 'Slamet Riadi', 'Eko Prasetyo', 'Dedi Kurniawan', 'Andi Suherman', 'Rian Hidayat', 'Yanto', 'Mulyono'];
        $sesi_list = [
            'pagi'  => ['jam' => 7, 'tarif_col' => 'tarif_pagi'],
            'siang' => ['jam' => 11, 'tarif_col' => 'tarif_siang'],
            'sore'  => ['jam' => 15, 'tarif_col' => 'tarif_sore'],
            'malam' => ['jam' => 19, 'tarif_col' => 'tarif_malam'],
        ];

        // Buat data transaksi untuk 7 hari terakhir
        for ($day = 0; $day < 7; $day++) {
            $tanggal = Carbon::now()->subDays($day);

            foreach (range(1, 5) as $t) { // 5 transaksi per hari
                $mejaId = rand(1, 10);
                $meja = DB::table('meja')->where('id', $mejaId)->first();
                
                $tipeSesi = array_rand($sesi_list);
                $sesiData = $sesi_list[$tipeSesi];
                
                $waktuMulai = (clone $tanggal)->setTime($sesiData['jam'], 0);
                $durasi = rand(2, 4);
                $waktuSelesai = (clone $waktuMulai)->addHours($durasi);
                
                $tarifSesi = $meja->{$sesiData['tarif_col']};

                DB::table('transaksi')->insert([
                    'user_id'            => $userId,
                    'meja_id'            => $mejaId,
                    'nama_pelanggan'     => $pelanggan[array_rand($pelanggan)],
                    'tipe_sesi'          => $tipeSesi,
                    'durasi'             => $durasi,
                    'total_harga'        => $tarifSesi, // Harga flat sesuai sesi
                    'waktu_mulai'        => $waktuMulai,
                    'waktu_selesai'      => $waktuSelesai,
                    'jumlah_ikan_kecil'  => rand(3, 15),
                    'berat_ikan_babon'   => rand(0, 3) == 0 ? 0 : rand(1, 5) + (rand(0, 9) / 10), // Ada kalanya zonk (0) atau dapet kg
                    'created_at'         => $waktuMulai,
                    'updated_at'         => $waktuMulai,
                ]);
            }
        }
    }
}
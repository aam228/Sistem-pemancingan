<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::find(2) ?? User::first();
        $userId = $user->id; 
        
        $spots = DB::table('spots')->where('user_id', $userId)->get();
        $paymentMethods = DB::table('payment_methods')->get();

        $cashMethods = $paymentMethods->where('tipe', 'cash');
        $qrisMethods = $paymentMethods->where('tipe', 'qris');
        $tfMethods = $paymentMethods->where('tipe', 'transfer');

        if ($spots->isEmpty()) {
            $this->command->error("Data Spot tidak ditemukan. Jalankan SpotSeeder dulu!");
            return;
        }

        if ($paymentMethods->isEmpty()) {
            $this->command->error("Data Payment Method kosong. Isi tabel payment_methods dulu!");
            return;
        }

        $pelanggan = [
            'Pak Bambang', 'Bang Jago', 'Mas Yono', 'Haji Lulung', 'Cak Mamat', 
            'Pak De Toto', 'Oom Agus', 'Pak Kumis', 'Slamet Mancing', 'Hendra Kicau', 
            'Dedi Tegeg', 'Yanto Strike', 'Udin Galatama', 'Eko Pancing'
        ];

        $transaksi = [];

        for ($i = 30; $i >= 0; $i--) {
            $tanggalSesi = Carbon::now()->subDays($i);
            $jumlahTransaksiHarian = ($tanggalSesi->isWeekend()) ? rand(8, 12) : rand(3, 6);

            for ($j = 0; $j < $jumlahTransaksiHarian; $j++) {
                $spot = $spots->random();
                
                $prob = rand(1, 100);
                if ($prob <= 60 && $cashMethods->isNotEmpty()) {
                    $pId = $cashMethods->random()->id;
                } elseif ($prob <= 90 && $qrisMethods->isNotEmpty()) {
                    $pId = $qrisMethods->random()->id;
                } elseif ($tfMethods->isNotEmpty()) {
                    $pId = $tfMethods->random()->id;
                } else {
                    $pId = $paymentMethods->random()->id;
                }

                $tipeSesi = collect(['pagi', 'siang', 'sore', 'malam'])->random();
                $waktuMulai = $tanggalSesi->copy()->setTime(rand(7, 20), 0, 0);
                $waktuSelesai = $waktuMulai->copy()->addHours(rand(2, 4));

                $hargaSesi = match($tipeSesi) {
                    'pagi'  => $spot->tarif_pagi,
                    'siang' => $spot->tarif_siang,
                    'sore'  => $spot->tarif_sore,
                    'malam' => $spot->tarif_malam,
                    default => 25000
                };

                $beratBabon = (rand(1, 10) > 8) ? (rand(10, 30) / 10) : 0; 
                $totalHarga = $hargaSesi + ($beratBabon * 25000);

                $transaksi[] = [
                    'user_id'           => $userId,
                    'spot_id'           => $spot->id,
                    'member_id'         => null, 
                    'payment_method_id' => $pId,
                    'nama_pelanggan'    => $pelanggan[array_rand($pelanggan)],
                    'tipe_sesi'         => $tipeSesi,
                    'total_harga'       => $totalHarga,
                    'waktu_mulai'       => $waktuMulai,
                    'waktu_selesai'     => $waktuSelesai,
                    'jumlah_ikan_kecil' => rand(0, 10),
                    'berat_ikan_babon'  => $beratBabon,
                    'created_at'        => $waktuSelesai,
                    'updated_at'        => $waktuSelesai,
                ];
            }
        }

        foreach (array_chunk($transaksi, 50) as $chunk) {
            DB::table('transaksi')->insert($chunk);
        }

        $this->command->info("Selesai! Data transaksi (Tanpa Member) telah dibuat.");
    }
}
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
        // Tetapkan User ID 2 sesuai akun kamu agar muncul di dashboard
        $user = User::find(2) ?? User::first();
        $userId = $user->id; 
        
        // Ambil data pendukung
        $spots = DB::table('spots')->where('user_id', $userId)->get();
        $members = DB::table('members')->get(); 
        
        // AMBIL DATA PAYMENT METHODS (ID 1, 2, dan 3 yang kamu buat)
        $paymentMethods = DB::table('payment_methods')->get();

        if ($spots->isEmpty()) {
            $this->command->error("Data Spot tidak ditemukan untuk User ID $userId. Jalankan SpotSeeder dulu!");
            return;
        }

        // --- DAFTAR NAMA BAPAK-BAPAK PEMANCING (Untuk Repeat Order) ---
        $pelangganReguler = [
            'Pak Bambang', 'Bang Jago', 'Mas Yono', 'Haji Lulung', 'Cak Mamat', 
            'Pak De Toto', 'Bang Tiger', 'Oom Agus', 'Pak Kumis', 'Mas Broo'
        ];

        $pelangganBaru = [
            'Slamet Mancing', 'Hendra Kicau', 'Dedi Tegeg', 'Yanto Strike', 
            'Udin Galatama', 'Gatot Kaca', 'Eko Pancing', 'Iwan Bawal', 'Soni Lele'
        ];

        $transaksi = [];

        // Simulasi 30 Hari Terakhir agar Analytics kamu bagus grafiknya
        for ($i = 30; $i >= 0; $i--) {
            $tanggalSesi = Carbon::now()->subDays($i);
            
            // Weekend lebih ramai (8-12 transaksi), hari biasa (3-6 transaksi)
            $jumlahTransaksiHarian = ($tanggalSesi->isWeekend()) ? rand(8, 12) : rand(3, 6);

            for ($j = 0; $j < $jumlahTransaksiHarian; $j++) {
                $spot = $spots->random();
                
                // 1. Logika Member (40% peluang member)
                $isMember = (rand(1, 10) <= 4) && $members->isNotEmpty();
                $member = $isMember ? $members->random() : null;
                
                // 2. Logika Nama & Repeat Order
                if ($isMember) {
                    $namaPelanggan = $member->nama;
                } else {
                    // 70% peluang diambil dari daftar Reguler (Repeat Order)
                    $namaPelanggan = (rand(1, 10) <= 7) 
                        ? $pelangganReguler[array_rand($pelangganReguler)] 
                        : $pelangganBaru[array_rand($pelangganBaru)];
                }

                $tipeSesi = collect(['pagi', 'siang', 'sore', 'malam'])->random();
                $jamMulai = match($tipeSesi) {
                    'pagi'  => rand(7, 9),
                    'siang' => rand(11, 13),
                    'sore'  => rand(15, 17),
                    'malam' => rand(19, 21),
                    default => 8
                };

                $waktuMulai = $tanggalSesi->copy()->setTime($jamMulai, 0, 0);
                $durasi = rand(2, 4); 
                $waktuSelesai = $waktuMulai->copy()->addHours($durasi);

                // Tarif dasar
                $hargaSesi = match($tipeSesi) {
                    'pagi'  => $spot->tarif_pagi,
                    'siang' => $spot->tarif_siang,
                    'sore'  => $spot->tarif_sore,
                    'malam' => $spot->tarif_malam,
                    default => 25000
                };

                $beratBabon = (rand(1, 10) > 7) ? (rand(10, 50) / 10) : 0; 
                $totalHarga = $hargaSesi + ($beratBabon * 35000);

                if ($isMember && isset($member->diskon_persen)) {
                    $totalHarga -= ($member->diskon_persen / 100) * $totalHarga;
                }

                $transaksi[] = [
                    'user_id'           => $userId,
                    'spot_id'           => $spot->id,
                    'member_id'         => $isMember ? $member->id : null,
                    // PILIH PAYMENT METHOD SECARA ACAK DARI ID 1, 2, ATAU 3
                    'payment_method_id' => $paymentMethods->isNotEmpty() ? $paymentMethods->random()->id : null,
                    'nama_pelanggan'    => $namaPelanggan,
                    'tipe_sesi'         => $tipeSesi,
                    'durasi'            => $durasi,
                    'total_harga'       => $totalHarga,
                    'waktu_mulai'       => $waktuMulai,
                    'waktu_selesai'     => $waktuSelesai,
                    'jumlah_ikan_kecil' => rand(0, 15),
                    'berat_ikan_babon'  => $beratBabon,
                    'created_at'        => $waktuSelesai,
                    'updated_at'        => $waktuSelesai,
                ];
            }
        }

        // Masukkan data ke database
        foreach (array_chunk($transaksi, 50) as $chunk) {
            DB::table('transaksi')->insert($chunk);
        }

        $this->command->info("Selesai! Data transaksi realistis untuk User ID $userId telah dibuat.");
    }
}
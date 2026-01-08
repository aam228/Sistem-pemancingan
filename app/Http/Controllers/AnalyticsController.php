<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Spot; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $month = $now->format('m');
        $year = $now->year;

        $userId = Auth::id();

        $pendapatanHariIni = Transaksi::where('user_id', $userId)
                                ->whereDate('waktu_mulai', $today)
                                ->sum('total_harga');

        $pendapatanBulanIni = Transaksi::where('user_id', $userId)
                                ->whereMonth('waktu_mulai', $month)
                                ->whereYear('waktu_mulai', $year)
                                ->sum('total_harga');

        $jumlahTransaksi = Transaksi::where('user_id', $userId)
                                ->whereMonth('waktu_mulai', $month)
                                ->whereYear('waktu_mulai', $year)
                                ->count();

        // Ini untuk melihat sesi mana yang paling sering dipilih pelanggan bulan ini
        $distribusiSesi = Transaksi::where('user_id', $userId)
                                ->whereMonth('waktu_mulai', $month)
                                ->whereYear('waktu_mulai', $year)
                                ->select('tipe_sesi', DB::raw('count(*) as jumlah'))
                                ->groupBy('tipe_sesi')
                                ->get();

        $pendapatanPerHari = Transaksi::where('user_id', $userId)
                                ->select(
                                    DB::raw('DATE(waktu_mulai) as tanggal'),
                                    DB::raw('SUM(total_harga) as total')
                                )
                                ->where('waktu_mulai', '>=', Carbon::now()->subDays(7))
                                ->groupBy('tanggal')
                                ->orderBy('tanggal', 'ASC')
                                ->get();

        $pendapatanPerSpot = Transaksi::where('transaksi.user_id', $userId)
                                ->join('spots', 'transaksi.spot_id', '=', 'spots.id')
                                ->select('spots.nama_spot', DB::raw('SUM(transaksi.total_harga) as total'))
                                ->groupBy('spots.nama_spot', 'spots.id') // Tambahkan ID agar SQL strict tidak error
                                ->get();

        $jamSibuk = Transaksi::where('user_id', $userId)
                            ->select(
                                DB::raw('HOUR(waktu_mulai) as jam'),
                                DB::raw('COUNT(*) as jumlah')
                            )
                            ->groupBy('jam')
                            ->orderBy('jam', 'ASC')
                            ->get();

        return view('analytics.index', compact(
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'jumlahTransaksi',
            'distribusiSesi',
            'pendapatanPerHari',
            'pendapatanPerSpot',
            'jamSibuk'
        ));
    }
}
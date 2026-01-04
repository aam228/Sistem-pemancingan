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

        $loggedInUserId = Auth::id();

        $pendapatanHariIni = Transaksi::where('user_id', $loggedInUserId)
                                ->whereDate('waktu_mulai', $today)
                                ->sum('total_harga');

        $pendapatanBulanIni = Transaksi::where('user_id', $loggedInUserId)
                                ->whereMonth('waktu_mulai', $month)
                                ->whereYear('waktu_mulai', $year)
                                ->sum('total_harga');

        $jumlahTransaksi = Transaksi::where('user_id', $loggedInUserId)
                                ->whereMonth('waktu_mulai', $month)
                                ->whereYear('waktu_mulai', $year)
                                ->count();

        $rataRataDurasi = Transaksi::where('user_id', $loggedInUserId)
                                ->whereMonth('waktu_mulai', $month)
                                ->whereYear('waktu_mulai', $year)
                                ->avg('durasi') ?? 0;

        $pendapatanPerHari = Transaksi::where('user_id', $loggedInUserId)
                                ->select(
                                    DB::raw('DATE(waktu_mulai) as tanggal'),
                                    DB::raw('SUM(total_harga) as total')
                                )
                                ->where('waktu_mulai', '>=', Carbon::now()->subDays(7))
                                ->groupBy('tanggal')
                                ->orderBy('tanggal', 'ASC')
                                ->get();

        $pendapatanPerSpot = DB::table('transaksi')
                                ->join('spots', 'transaksi.spot_id', '=', 'spots.id') 
                                ->where('transaksi.user_id', $loggedInUserId)
                                ->select('spots.nama_spot', DB::raw('SUM(transaksi.total_harga) as total')) 
                                ->groupBy('spots.nama_spot')
                                ->get();

        $jamSibuk = Transaksi::where('user_id', $loggedInUserId)
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
            'rataRataDurasi',
            'pendapatanPerHari',
            'pendapatanPerSpot',
            'jamSibuk'
        ));
    }
}
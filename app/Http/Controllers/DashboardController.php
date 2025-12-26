<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $mejas = $user
            ? $user->mejas()->get()
            : collect();

        $transaksis_berjalan = $user
            ? $user->transaksis()
                ->with('meja')
                ->where('waktu_selesai', '>', now())
                ->get()
            : collect();

        return view('dashboard', compact(
            'mejas',
            'transaksis_berjalan'
        ));
    }
}
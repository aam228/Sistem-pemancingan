<?php

namespace App\Http\Controllers;

use App\Models\Spot; 
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $spots = $user->spots()->get();

        $transaksis_berjalan = $user->transaksis()
            ->with('spot') 
            ->whereNull('jumlah_ikan_kecil') 
            ->get();

        return view('dashboard', compact('spots', 'transaksis_berjalan'));
    }
}
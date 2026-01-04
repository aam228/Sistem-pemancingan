<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Spot;
use App\Models\PaymentMethod;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    private function getSesiConfig(string $tipe)
    {
        return [
            'pagi' => ['start' => '05:00', 'end' => '10:00'],
            'siang' => ['start' => '10:00', 'end' => '14:00'],
            'sore' => ['start' => '14:00', 'end' => '18:00'],
            'malam' => ['start' => '18:00', 'end' => '06:00', 'next_day' => true],
            'pagi_sore' => ['start' => '05:00', 'end' => '18:00'],
            'full' => ['start' => '05:00', 'end' => '10:00', 'next_day' => true],
        ][$tipe] ?? null;
    }

    private function getHargaSesi(Spot $spot, string $tipeSesi)
    {
        return match ($tipeSesi) {
            'pagi'  => $spot->tarif_pagi,
            'siang' => $spot->tarif_siang,
            'sore'  => $spot->tarif_sore,
            'malam' => $spot->tarif_malam,
            default => 0,
        };
    }

    public function create($spot_id)
    {
        $spot = Spot::findOrFail($spot_id);
        $members = Member::where('status', 'active')->get();

        if ($spot->user_id !== Auth::id()) {
            abort(403);
        }

        return view('transaksi.create', compact('spot', "members"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'spot_id'        => 'required|exists:spots,id',
            'nama_pelanggan' => 'required|string|max:255',
            'tipe_sesi'      => 'required|string',
            'member_id'      => 'nullable|exists:members,id',
        ]);

        $spot = Spot::findOrFail($request->spot_id);
        $config = $this->getSesiConfig($request->tipe_sesi);

        if (!$config) {
            return back()->withErrors('Tipe sesi tidak valid.');
        }

        $now = now();
        $waktuMulai = $now;

        $waktuSelesaiEstimasi = Carbon::parse($now->format('Y-m-d') . ' ' . $config['end']);
        if (!empty($config['next_day']) && $now->format('H:i') >= $config['start']) {
            $waktuSelesaiEstimasi->addDay();
        }

        $hargaSesi = $this->getHargaSesi($spot, $request->tipe_sesi);

        $transaksi = Auth::user()->transaksis()->create([
            'spot_id'           => $spot->id,
            'member_id'         => $request->member_id,
            'nama_pelanggan'    => $request->nama_pelanggan,
            'tipe_sesi'         => $request->tipe_sesi,
            'total_harga'       => $hargaSesi,
            'waktu_mulai'       => $waktuMulai,
            'waktu_selesai'     => $waktuSelesaiEstimasi,
            'jumlah_ikan_kecil' => null,
            'berat_ikan_babon'  => 0,
        ]);

        $spot->update(['status' => 'digunakan']);

        return redirect()->route('dashboard')->with('success', 'Sesi pemancingan dimulai.');
    }

    public function selesaiForm($id)
    {
        $transaksi = Transaksi::with(['spot', 'member'])->findOrFail($id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $paymentMethods = PaymentMethod::where('is_active', 1)->get();
        return view('transaksi.selesai', compact('transaksi', 'paymentMethods'));
    }

    public function selesaiProses(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $transaksi = Transaksi::with(['spot', 'member'])->findOrFail($id);

            if ($transaksi->user_id !== Auth::id()) {
                abort(403);
            }

            $request->validate([
                'jumlah_ikan_kecil' => ['required', 'integer', 'min:0'],
                'berat_ikan_babon'  => ['required', 'numeric', 'min:0'],
                'payment_method_id' => ['required', 'exists:payment_methods,id'],
            ]);

            $hargaDasarKecil = 5000;
            $hargaDasarBabon = 25000;

            $persenDiskon = 0;
            if ($transaksi->member && $transaksi->member->status === 'active') {
                $persenDiskon = $transaksi->member->diskon_persen;
            }

            $tarifKecil = $hargaDasarKecil * ((100 - $persenDiskon) / 100);
            $tarifBabon = $hargaDasarBabon * ((100 - $persenDiskon) / 100);

            $subTotalIkanKecil = $request->jumlah_ikan_kecil * $tarifKecil;
            $subTotalIkanBabon = $request->berat_ikan_babon * $tarifBabon;

            $totalHargaFinal = $transaksi->total_harga + $subTotalIkanKecil + $subTotalIkanBabon;

            $waktuSelesaiReal = now();

            $transaksi->update([
                'jumlah_ikan_kecil' => $request->jumlah_ikan_kecil,
                'berat_ikan_babon'  => $request->berat_ikan_babon,
                'total_harga'       => $totalHargaFinal,
                'waktu_selesai'     => $waktuSelesaiReal,
                'payment_method_id' => $request->payment_method_id,
            ]);

            if ($transaksi->member) {
                $poinBaru = floor($totalHargaFinal / 10000);
                $transaksi->member->increment('poin', $poinBaru);
            }

            $transaksi->spot->update(['status' => 'tersedia']);

            return redirect()->route('dashboard')
                ->with('success', 'Transaksi selesai. Diskon ' . $persenDiskon . '% telah diterapkan!');
        });
    }

    public function batal($id)
    {
        $transaksi = Transaksi::with('spot')->findOrFail($id);
        if ($transaksi->user_id !== Auth::id()) abort(403);

        $transaksi->spot->update(['status' => 'tersedia']);
        $transaksi->delete();

        return redirect()->route('dashboard')->with('success', 'Transaksi dibatalkan.');
    }

    public function histori()
    {
        $transaksis = Auth::user()->transaksis()->with('spot')->orderBy('created_at', 'desc')->paginate(10);
        return view('transaksi.histori', compact('transaksis'));
    }

    public function laporan(Request $request)
    {
        $spots = Auth::user()->spots; 

        $query = Auth::user()->transaksis()->with('spot');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('waktu_mulai', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay(),
            ]);
        }

        if ($request->filled('nama_pelanggan')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        if ($request->filled('spot_id')) {
            $query->where('spot_id', $request->spot_id);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksis->sum('total_harga');

        return view('transaksi.laporan', compact('transaksis', 'totalPendapatan', 'spots'));
    }
}
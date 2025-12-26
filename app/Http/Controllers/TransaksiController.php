<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Meja;
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
            'pagi' => [
                'start' => '05:00',
                'end'   => '10:00',
            ],
            'siang' => [
                'start' => '10:00',
                'end'   => '14:00',
            ],
            'sore' => [
                'start' => '14:00',
                'end'   => '18:00',
            ],
            'malam' => [
                'start'    => '18:00',
                'end'      => '06:00',
                'next_day' => true,
            ],
            'pagi_sore' => [
                'start' => '05:00',
                'end'   => '18:00',
            ],
            'full' => [
                'start'    => '05:00',
                'end'      => '10:00',
                'next_day' => true,
            ],
        ][$tipe] ?? null;
    }

    public function create($meja_id)
    {
        $meja = Meja::findOrFail($meja_id);

        if ($meja->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('transaksi.create', compact('meja'));
    }

    private function getHargaSesi(Meja $meja, string $tipeSesi)
    {
        return match ($tipeSesi) {
            'pagi'  => $meja->tarif_pagi,
            'siang' => $meja->tarif_siang,
            'sore'  => $meja->tarif_sore,
            'malam' => $meja->tarif_malam,
            default => 0,
        };
    }


    public function store(Request $request)
    {
        $request->validate([
            'meja_id'        => 'required|exists:meja,id',
            'nama_pelanggan'=> 'required|string|max:255',
            'tipe_sesi'     => 'required|string',
        ]);

        $meja = Meja::findOrFail($request->meja_id);
        $config = $this->getSesiConfig($request->tipe_sesi);

        if (!$config) {
            return back()->withErrors('Tipe sesi tidak valid.');
        }

        $now = now();

        $waktuMulai = $now;
        $waktuSelesai = Carbon::parse(
            $now->format('Y-m-d') . ' ' . $config['end']
        );

        if (!empty($config['next_day']) && $now->format('H:i') >= $config['start']) {
            $waktuSelesai->addDay();
        }

        $hargaSesi = $this->getHargaSesi($meja, $request->tipe_sesi);

        $transaksi = Auth::user()->transaksis()->create([
            'meja_id'        => $meja->id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'tipe_sesi'      => $request->tipe_sesi,
            'total_harga'    => $hargaSesi,
            'waktu_mulai'    => $waktuMulai,
            'waktu_selesai'  => $waktuSelesai,

            'jumlah_ikan_kecil' => 0,
            'berat_ikan_babon'  => 0,
        ]);

        $meja->update(['status' => 'digunakan']);

        return redirect()->route('dashboard')
            ->with('success', 'Sesi pemancingan berhasil dimulai.');
    }

    public function selesaiForm($id)
    {
        $transaksi = Transaksi::with('meja')->findOrFail($id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('transaksi.selesai', compact('transaksi'));
    }

    public function selesaiProses(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $transaksi = Transaksi::with('meja')->findOrFail($id);

            if ($transaksi->user_id !== Auth::id()) {
                abort(403);
            }

            $request->validate([
                'jumlah_ikan_kecil' => 'required|integer|min:0',
                'berat_ikan_babon'  => 'required|numeric|min:0',
            ]);

            $hargaIkanKecil = $request->jumlah_ikan_kecil * 5000;
            $hargaIkanBabon = $request->berat_ikan_babon * 25000;

            $totalHarga = $transaksi->total_harga + $hargaIkanKecil + $hargaIkanBabon;

            $waktuSelesaiFinal = now()->lessThan($transaksi->waktu_selesai)
                ? now()
                : $transaksi->waktu_selesai;

            $transaksi->update([
                'jumlah_ikan_kecil' => $request->jumlah_ikan_kecil,
                'berat_ikan_babon'  => $request->berat_ikan_babon,
                'total_harga'       => $totalHarga,
                'waktu_selesai'     => $waktuSelesaiFinal,
            ]);

            $transaksi->meja->update(['status' => 'tersedia']);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Sesi selesai.');
    }

    public function batal($id)
    {
        $transaksi = Transaksi::with('meja')->findOrFail($id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $transaksi->meja->update(['status' => 'tersedia']);
        $transaksi->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Transaksi dibatalkan.');
    }

    public function histori()
    {
        $transaksis = Auth::user()->transaksis()
            ->with('meja')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transaksi.histori', compact('transaksis'));
    }

    public function hapus(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $transaksi->delete();

        return redirect()->route('transaksi.histori')
            ->with('success', 'Transaksi dihapus.');
    }

    public function laporan(Request $request)
    {
        $query = Auth::user()->transaksis()->with('meja');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay(),
            ]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksis->sum('total_harga');
        $mejas = Auth::user()->mejas()->get();

        return view('transaksi.laporan', compact(
            'transaksis',
            'totalPendapatan',
            'mejas'
        ));
    }

    public function cetakLaporan(Request $request)
    {
        $query = Auth::user()->transaksis()->with('meja');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay(),
            ]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $transaksis->sum('total_harga');

        $dataMeja = [];
        foreach ($transaksis->groupBy('meja_id') as $mejaId => $list) {
            $namaMeja = optional($list->first()->meja)->nama_meja ?? 'Meja #' . $mejaId;
            $totalDurasi = $list->sum('durasi');
            $totalPendapatanMeja = $list->sum('total_harga');
            $persentase = $totalPendapatan > 0
                ? ($totalPendapatanMeja / $totalPendapatan) * 100
                : 0;

            $dataMeja[] = [
                'nama_meja' => $namaMeja,
                'total_durasi' => $totalDurasi,
                'total_pendapatan' => $totalPendapatanMeja,
                'persentase' => $persentase,
            ];
        }

        $pdf = Pdf::loadView(
            'transaksi.laporan-pdf',
            compact('transaksis', 'totalPendapatan', 'dataMeja')
        )->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-pemancingan.pdf');
    }
}

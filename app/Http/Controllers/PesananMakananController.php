<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\PesananMakanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PesananMakananController extends Controller
{
    public function create($transaksi_id)
    {
        $transaksi = Transaksi::with(['pesananMakanan.produk', 'spot'])
            ->findOrFail($transaksi_id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        $produk = Auth::user()->produks()->get();
        
        $pesananMakanan = $transaksi->pesananMakanan;

        return view('pesanan.create', compact('transaksi', 'produk', 'pesananMakanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'produk' => 'required|array',
            'produk.*' => 'integer|min:0',
        ]);

        try {
            $transaksi = Transaksi::findOrFail($request->transaksi_id);
            
            if ($transaksi->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses untuk menambahkan pesanan ke transaksi ini.');
            }

            $hasOrder = false;
            foreach ($request->produk as $jumlah) {
                if ($jumlah > 0) {
                    $hasOrder = true;
                    break;
                }
            }
            
            if (!$hasOrder) {
                return back()->with('error', 'Pilih setidaknya satu produk dengan jumlah lebih dari 0.');
            }

            foreach ($request->produk as $produk_id => $jumlah) {
                if ($jumlah > 0) {
                    $produk = Produk::findOrFail($produk_id);
                    
                    if ($produk->user_id !== Auth::id()) {
                        throw new \Exception('Produk yang dipilih tidak valid atau bukan milik Anda.');
                    }

                    $subtotal = (float) $jumlah * (float) $produk->harga;

                    Auth::user()->pesananMakanan()->create([
                        'transaksi_id' => $transaksi->id,
                        'spot_id' => $transaksi->spot_id, 
                        'produk_id' => $produk->id,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            return redirect()->route('pesanan.create', ['transaksi_id' => $transaksi->id])
                ->with('success', 'Pesanan berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            Log::error('Error creating pesanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan pesanan: ' . $e->getMessage());
        }
    }

    public function redirectFromTransaksi($transaksi_id)
    {
        $transaksi = Transaksi::findOrFail($transaksi_id);
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }
        return redirect()->route('pesanan.create', ['transaksi_id' => $transaksi->id]);
    }

    public function getPesananByTransaksi($transaksi_id)
    {
        $transaksi = Transaksi::findOrFail($transaksi_id);
        if ($transaksi->user_id !== Auth::id()) {
            return response()->json(['error' => 'Anda tidak memiliki akses ke transaksi ini.'], 403);
        }
        
        $pesananMakanan = $transaksi->pesananMakanan()->with('produk')->get();
        return response()->json($pesananMakanan);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Auth::user()->mejas()->get();
        return view('meja.index', compact('mejas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_meja' => 'required|string|max:255|unique:meja,nama_meja,NULL,id,user_id,' . Auth::id(),

            'tarif_pagi'  => 'required|numeric|min:0',
            'tarif_siang' => 'required|numeric|min:0',
            'tarif_sore'  => 'required|numeric|min:0',
            'tarif_malam' => 'required|numeric|min:0',
        ]);

        Auth::user()->mejas()->create([
            'nama_meja'   => $validated['nama_meja'],
            'status'      => 'tersedia',
            'tarif_pagi'  => $validated['tarif_pagi'],
            'tarif_siang' => $validated['tarif_siang'],
            'tarif_sore'  => $validated['tarif_sore'],
            'tarif_malam' => $validated['tarif_malam'],
        ]);

        return redirect()
            ->route('meja.index')
            ->with('success', 'Meja berhasil ditambahkan.');
    }

    public function update(Request $request, Meja $meja)
    {
        $this->authorizeMeja($meja);

        $validated = $request->validate([
            'nama_meja' => 'required|string|max:255|unique:meja,nama_meja,' . $meja->id . ',id,user_id,' . Auth::id(),

            'tarif_pagi'  => 'required|numeric|min:0',
            'tarif_siang' => 'required|numeric|min:0',
            'tarif_sore'  => 'required|numeric|min:0',
            'tarif_malam' => 'required|numeric|min:0',
        ]);

        $meja->update($validated);

        return redirect()
            ->route('meja.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja)
    {
        $this->authorizeMeja($meja);
        $meja->delete();

        return redirect()
            ->route('meja.index')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'meja_id' => 'required|exists:meja,id',
            'status'  => 'required|in:tersedia,digunakan',
        ]);

        $meja = Meja::findOrFail($request->meja_id);
        $this->authorizeMeja($meja);

        $meja->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status meja berhasil diperbarui.',
        ]);
    }

    public function resetStatus(Meja $meja)
    {
        $this->authorizeMeja($meja);

        $meja->update(['status' => 'tersedia']);

        return redirect()
            ->back()
            ->with('success', 'Status meja berhasil direset.');
    }

    public function reset(Meja $meja)
    {
        if ($meja->user_id !== Auth::id()) {
            abort(403);
        }

        // hanya reset kondisi meja
        $meja->update([
            'status' => 'tersedia',
        ]);

        return back()->with('success', 'Meja berhasil di-reset manual.');
    }


    private function authorizeMeja(Meja $meja)
    {
        if ($meja->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}

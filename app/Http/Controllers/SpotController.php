<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotController extends Controller
{
    public function index()
    {
        $spots = Auth::user()->spots()->get();
        return view('spot.index', compact('spots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_spot'   => 'required|string|max:255|unique:spots,nama_spot,NULL,id,user_id,' . Auth::id(),
            'tarif_pagi'  => 'required|numeric|min:0',
            'tarif_siang' => 'required|numeric|min:0',
            'tarif_sore'  => 'required|numeric|min:0',
            'tarif_malam' => 'required|numeric|min:0',
        ]);

        Auth::user()->spots()->create([
            'nama_spot'   => $validated['nama_spot'],
            'status'      => 'tersedia',
            'tarif_pagi'  => $validated['tarif_pagi'],
            'tarif_siang' => $validated['tarif_siang'],
            'tarif_sore'  => $validated['tarif_sore'],
            'tarif_malam' => $validated['tarif_malam'],
        ]);

        return redirect()
            ->route('spot.index')
            ->with('success', 'Spot pancing berhasil ditambahkan.');
    }

    public function update(Request $request, Spot $spot)
    {
        $this->authorizeSpot($spot);

        $validated = $request->validate([
            'nama_spot'   => 'required|string|max:255|unique:spots,nama_spot,' . $spot->id . ',id,user_id,' . Auth::id(),
            'tarif_pagi'  => 'required|numeric|min:0',
            'tarif_siang' => 'required|numeric|min:0',
            'tarif_sore'  => 'required|numeric|min:0',
            'tarif_malam' => 'required|numeric|min:0',
        ]);

        $spot->update($validated);

        return redirect()
            ->route('spot.index')
            ->with('success', 'Spot pancing berhasil diperbarui.');
    }

    public function destroy(Spot $spot)
    {
        $this->authorizeSpot($spot);
        $spot->delete();

        return redirect()
            ->route('spot.index')
            ->with('success', 'Spot pancing berhasil dihapus.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|exists:spots,id',
            'status'  => 'required|in:tersedia,digunakan,perawatan',
        ]);

        $spot = Spot::findOrFail($request->spot_id);
        $this->authorizeSpot($spot);

        $spot->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status spot berhasil diperbarui.',
        ]);
    }

    public function resetStatus(Spot $spot)
    {
        $this->authorizeSpot($spot);

        $spot->update(['status' => 'tersedia']);

        return redirect()
            ->back()
            ->with('success', 'Status spot berhasil direset.');
    }

    public function reset(Spot $spot)
    {
        if ($spot->user_id !== Auth::id()) {
            abort(403);
        }

        $spot->update([
            'status' => 'tersedia',
        ]);

        return back()->with('success', 'Spot berhasil di-reset manual.');
    }

    private function authorizeSpot(Spot $spot)
    {
        if ($spot->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
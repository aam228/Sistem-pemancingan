<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Auth::user()->produks()->get();
        return view('produk.index', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255|unique:produk,nama_produk,NULL,id,user_id,'.Auth::id(),
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = uniqid().'.webp';
            $path = 'produk-images/'.$filename;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file)->toWebp(90);

            Storage::disk('public')->put($path, (string) $image);

            $validated['gambar'] = $path;
        }

        Auth::user()->produks()->create($validated);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        if ($produk->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat produk ini.');
        }
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        if ($produk->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        if ($produk->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255|unique:produk,nama_produk,' . $produk->id . ',id,user_id,'.Auth::id(),
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $file = $request->file('gambar');
            $filename = uniqid().'.webp';
            $path = 'produk-images/'.$filename;

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file)->toWebp(90);

            Storage::disk('public')->put($path, (string) $image);
            $validated['gambar'] = $path;
        }

        $produk->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus produk ini.');
        }

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function editJson(Produk $produk)
    {
        if ($produk->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data produk ini.');
        }
        return response()->json($produk);
    }
}
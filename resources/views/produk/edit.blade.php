@extends('layouts.app')

@section('content')
<div class="app-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">Edit Makanan</h4>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-1">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm p-4 border-0 rounded-4">
        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_produk" class="form-label fw-semibold">Nama Makanan</label>
                <input type="text" name="nama_produk" id="nama_produk" class="form-control" 
                       value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                @error('nama_produk')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label fw-semibold">Harga (Rp)</label>
                <input type="text" name="harga" id="harga" class="form-control" 
                       value="{{ old('harga', $produk->harga) }}" required>
                @error('harga')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label fw-semibold">Gambar Produk</label>
                <input type="file" name="gambar" id="gambar" class="form-control">
                @if ($produk->gambar)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" 
                             alt="{{ $produk->nama_produk }}" 
                             class="img-thumbnail" style="max-height: 200px;">
                    </div>
                @endif
                @error('gambar')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save2 me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Format input harga ke format rupiah
    function formatRupiah(input) {
        let value = input.value.replace(/\D/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const hargaInput = document.getElementById('harga');
        hargaInput.addEventListener('input', () => formatRupiah(hargaInput));

        hargaInput.closest('form').addEventListener('submit', () => {
            hargaInput.value = hargaInput.value.replace(/\./g, '');
        });
    });
</script>
@endpush

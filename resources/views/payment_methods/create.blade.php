@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Tambah Payment Method</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ route('payment-methods.store') }}">
                @csrf

                {{-- TIPE --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Tipe Pembayaran</label>
                    <select name="tipe" class="form-select" id="tipe" required>
                        <option value="" selected disabled>-- Pilih Tipe --</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer (Bank)</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                {{-- NAMA METODE --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Metode</label>
                    <input type="text" name="nama_metode" class="form-control" 
                           placeholder="Contoh: BCA, Mandiri, atau QRIS Toko" required>
                </div>

                {{-- FIELD KHUSUS PEMILIK (Akan muncul di Transfer & QRIS) --}}
                <div id="owner-field" class="d-none">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Pemilik (Atas Nama)</label>
                        <input type="text" name="nama_pemilik" class="form-control" placeholder="Nama lengkap pemilik">
                    </div>
                </div>

                {{-- FIELD KHUSUS TRANSFER --}}
                <div id="transfer-fields" class="d-none">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Informasi Bank</label>
                        <input name="nama_bank" class="form-control mb-2" placeholder="Nama Bank (Contoh: BCA)">
                        <input name="no_rekening" class="form-control" placeholder="Nomor Rekening">
                    </div>
                </div>

                {{-- FIELD KHUSUS QRIS --}}
                <div id="qris-fields" class="d-none">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload QR Image</label>
                        <input type="file" name="qr_image" class="form-control" accept="image/*">
                        <div class="form-text">Upload file gambar QRIS yang valid.</div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="form-check form-switch mt-4">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" checked>
                    <label class="form-check-label" for="isActive">Aktifkan Metode Ini</label>
                </div>

                <hr>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('payment-methods.index') }}" class="btn btn-light me-2">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Metode</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('tipe').addEventListener('change', function () {
    const val = this.value;
    
    // Sembunyikan semua dulu
    document.getElementById('transfer-fields').classList.add('d-none');
    document.getElementById('qris-fields').classList.add('d-none');
    document.getElementById('owner-field').classList.add('d-none');

    if (val === 'transfer') {
        document.getElementById('transfer-fields').classList.remove('d-none');
        document.getElementById('owner-field').classList.remove('d-none');
    } else if (val === 'qris') {
        document.getElementById('qris-fields').classList.remove('d-none');
        document.getElementById('owner-field').classList.remove('d-none');
    }
    // Jika 'cash', maka field tambahan tetap tersembunyi
});
</script>
@endsection
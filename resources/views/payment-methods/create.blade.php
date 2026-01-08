@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h4 class="mb-0 text-body">Tambah Payment Method</h4>
        </div>
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data" action="{{ route('payment-methods.store') }}">
                @csrf

                {{-- TIPE --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-body">Tipe Pembayaran</label>
                    <select name="tipe" class="form-select bg-body text-body border-secondary-subtle" id="tipe" required>
                        <option value="" selected disabled>-- Pilih Tipe --</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer (Bank)</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                {{-- NAMA METODE --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-body">Nama Metode</label>
                    <input type="text" name="nama_metode" class="form-control bg-body text-body border-secondary-subtle" 
                           placeholder="Contoh: BCA, Mandiri, atau QRIS Toko" required>
                </div>

                {{-- FIELD NAMA PEMILIK (Sekarang muncul di Cash, Transfer, & QRIS) --}}
                <div id="owner-field" class="d-none">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-body">Nama Pemilik (Atas Nama / Penerima)</label>
                        <input type="text" id="input_nama_pemilik" name="nama_pemilik" class="form-control bg-body text-body border-secondary-subtle" placeholder="Nama lengkap pemilik/penerima">
                    </div>
                </div>

                {{-- FIELD KHUSUS TRANSFER --}}
                <div id="transfer-fields" class="d-none">
                    <div class="mb-4 p-3 rounded bg-body-tertiary border">
                        <label class="form-label fw-bold text-body">Informasi Bank</label>
                        <input name="nama_bank" class="form-control mb-2 bg-body text-body" placeholder="Nama Bank (Contoh: BCA)">
                        <input name="no_rekening" class="form-control bg-body text-body" placeholder="Nomor Rekening">
                    </div>
                </div>

                {{-- FIELD KHUSUS QRIS --}}
                <div id="qris-fields" class="d-none">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-body">Upload QR Image</label>
                        <input type="file" name="qr_image" class="form-control bg-body text-body" accept="image/*">
                        <div class="form-text text-secondary">Upload file gambar QRIS yang valid.</div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="form-check form-switch mt-4">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" checked>
                    <label class="form-check-label text-body" for="isActive">Aktifkan Metode Ini</label>
                </div>

                <hr class="my-4 opacity-25">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Metode</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('tipe').addEventListener('change', function () {
    const val = this.value;
    const transferFields = document.getElementById('transfer-fields');
    const qrisFields = document.getElementById('qris-fields');
    const ownerField = document.getElementById('owner-field');
    const ownerInput = document.getElementById('input_nama_pemilik');
    
    // Sembunyikan semua field tambahan dulu
    transferFields.classList.add('d-none');
    qrisFields.classList.add('d-none');
    ownerField.classList.add('d-none');
    ownerInput.disabled = true; // Disable agar tidak terkirim jika tidak relevan

    // Logika Tampilan
    if (val === 'cash' || val === 'transfer' || val === 'qris') {
        ownerField.classList.remove('d-none');
        ownerInput.disabled = false;
    }

    if (val === 'transfer') {
        transferFields.classList.remove('d-none');
    } else if (val === 'qris') {
        qrisFields.classList.remove('d-none');
    }
});
</script>
@endsection
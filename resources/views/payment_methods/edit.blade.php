@extends('layouts.app')

@section('title', 'Edit Metode Pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('payment-methods.index') }}">Metode Pembayaran</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card bg-body border shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="mb-0 text-body fw-bold">Edit Metode Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('payment-methods.update', $paymentMethod->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- TIPE --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-body-secondary">Tipe Pembayaran</label>
                            <select name="tipe" id="tipe_select" class="form-select border-secondary-subtle py-2" required>
                                <option value="cash" {{ $paymentMethod->tipe == 'cash' ? 'selected' : '' }}>TUNAI (CASH)</option>
                                <option value="transfer" {{ $paymentMethod->tipe == 'transfer' ? 'selected' : '' }}>TRANSFER BANK</option>
                                <option value="qris" {{ $paymentMethod->tipe == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>

                        {{-- NAMA METODE --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-body-secondary">Nama Metode</label>
                            <input type="text" name="nama_metode" class="form-control" 
                                   value="{{ old('nama_metode', $paymentMethod->nama_metode) }}" 
                                   placeholder="Contoh: BCA, Mandiri, atau QRIS Dana" required>
                        </div>

                        {{-- AREA DINAMIS (Muncul berdasarkan pilihan tipe) --}}
                        <div id="dynamic_fields" class="{{ $paymentMethod->tipe == 'cash' ? 'd-none' : '' }}">
                            
                            {{-- NAMA PEMILIK (Untuk Transfer & QRIS) --}}
                            <div class="mb-4 border-start border-primary border-4 ps-3 py-1">
                                <label class="form-label small fw-bold text-uppercase text-body-secondary">Nama Pemilik (A.N.)</label>
                                <input type="text" name="nama_pemilik" class="form-control" 
                                       value="{{ old('nama_pemilik', $paymentMethod->nama_pemilik) }}" 
                                       placeholder="Nama lengkap pemilik rekening/akun">
                            </div>

                            {{-- KHUSUS TRANSFER --}}
                            <div id="transfer_fields" class="{{ $paymentMethod->tipe != 'transfer' ? 'd-none' : '' }}">
                                <div class="row g-3">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-body-secondary">Nama Bank</label>
                                        <input type="text" name="nama_bank" class="form-control" 
                                               value="{{ old('nama_bank', $paymentMethod->nama_bank) }}" 
                                               placeholder="Contoh: Bank BCA">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label small fw-bold text-uppercase text-body-secondary">No. Rekening</label>
                                        <input type="text" name="no_rekening" class="form-control" 
                                               value="{{ old('no_rekening', $paymentMethod->no_rekening) }}" 
                                               placeholder="Masukkan nomor rekening">
                                    </div>
                                </div>
                            </div>

                            {{-- KHUSUS QRIS --}}
                            <div id="qris_fields" class="{{ $paymentMethod->tipe != 'qris' ? 'd-none' : '' }}">
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-uppercase text-body-secondary">Update QR Image</label>
                                    
                                    @if($paymentMethod->qr_image)
                                        <div class="mb-3">
                                            <p class="small text-muted mb-2">QR Saat Ini:</p>
                                            <img src="{{ asset('storage/' . $paymentMethod->qr_image) }}" 
                                                 class="img-thumbnail border-secondary-subtle shadow-sm mb-2" 
                                                 style="max-width: 150px;">
                                        </div>
                                    @endif

                                    <input type="file" name="qr_image" class="form-control" accept="image/*">
                                    <div class="form-text small italic">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="form-check form-switch mt-4 bg-body-tertiary p-3 rounded border">
                            <div class="ms-4">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       value="1" {{ $paymentMethod->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-body" for="is_active">Metode Aktif</label>
                                <div class="small text-muted">Hanya metode aktif yang muncul di kasir.</div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-light btn-sm px-4">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm px-4 shadow">
                                <i class="fas fa-save me-1"></i> Update Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeSelect = document.getElementById('tipe_select');
        const dynamicFields = document.getElementById('dynamic_fields');
        const transferFields = document.getElementById('transfer_fields');
        const qrisFields = document.getElementById('qris_fields');

        function toggleInputs() {
            const val = tipeSelect.value;
            
            // Tampilkan container utama jika bukan cash
            if (val === 'cash') {
                dynamicFields.classList.add('d-none');
            } else {
                dynamicFields.classList.remove('d-none');
            }

            // Tampilkan sub-field spesifik
            if (val === 'transfer') {
                transferFields.classList.remove('d-none');
                qrisFields.classList.add('d-none');
            } else if (val === 'qris') {
                qrisFields.classList.remove('d-none');
                transferFields.classList.add('d-none');
            } else {
                transferFields.classList.add('d-none');
                qrisFields.classList.add('d-none');
            }
        }

        tipeSelect.addEventListener('change', toggleInputs);
    });
</script>

<style>
    /* Menyelaraskan tampilan input dengan tema */
    .form-control, .form-select {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
    }
    .form-control:focus, .form-select:focus {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .bg-body-tertiary {
        background-color: var(--bs-tertiary-bg) !important;
    }
</style>
@endpush
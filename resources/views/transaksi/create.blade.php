@extends('layouts.app')

@section('title', 'Mulai Sesi Pemancingan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Mulai Sesi</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            {{-- Card menggunakan bg-body agar mengikuti tema --}}
            <div class="card shadow-sm border bg-body">
                <div class="card-header bg-transparent border-bottom text-center py-4">
                    <div class="mb-3">
                        {{-- Icon menggunakan bg-opacity agar tetap lembut di dark mode --}}
                        <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle">
                            <i class="fas fa-fish fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h4 class="mb-1 text-body fw-bold">Mulai Sesi</h4>
                    <p class="text-muted mb-0">Kolam: <span class="text-body fw-semibold">{{ $meja->nama_meja }}</span></p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transaksi.store') }}" method="POST" id="sesiForm">
                        @csrf
                        <input type="hidden" name="meja_id" value="{{ $meja->id }}">

                        <div class="mb-4">
                            <label for="nama_pelanggan" class="form-label text-body fw-medium">
                                Nama Pemancing
                            </label>
                            <input type="text" 
                                   name="nama_pelanggan" 
                                   id="nama_pelanggan"
                                   class="form-control bg-body-tertiary border-secondary-subtle" 
                                   placeholder="Masukkan nama pemancing"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="tipe_sesi" class="form-label text-body fw-medium">
                                Pilih Sesi Pemancingan
                            </label>
                            <select name="tipe_sesi" 
                                    id="tipe_sesi" 
                                    class="form-select bg-body-tertiary border-secondary-subtle" 
                                    required>
                                <option value="" class="text-muted">-- Pilih Sesi --</option>
                                <option value="pagi">
                                    Pagi (05.00 - 10.00) - Rp {{ number_format($meja->tarif_pagi, 0, ',', '.') }}
                                </option>
                                <option value="siang">
                                    Siang (10.00 - 14.00) - Rp {{ number_format($meja->tarif_siang, 0, ',', '.') }}
                                </option>
                                <option value="sore">
                                    Sore (14.00 - 18.00) - Rp {{ number_format($meja->tarif_sore, 0, ',', '.') }}
                                </option>
                                <option value="malam">
                                    Malam (18.00 - 06.00) - Rp {{ number_format($meja->tarif_malam, 0, ',', '.') }}
                                </option>
                            </select>
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i> Tarif disesuaikan dengan pengaturan spot.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-light p-2 bg-danger">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                <i class="fas fa-play me-2"></i>Mulai Sesi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Transisi halus untuk input saat fokus */
    .form-control:focus, .form-select:focus {
        background-color: var(--bs-body-bg) !important;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    /* Penyesuaian select option untuk beberapa browser */
    .form-select option {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('sesiForm');
    const btn = document.getElementById('submitBtn');

    form.addEventListener('submit', function (e) {
        btn.disabled = true;
        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Menyiapkan Lapak...
        `;
    });
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Selesai Sesi Pemancingan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Selesai Sesi</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            {{-- Card Utama menggunakan bg-body --}}
            <div class="card shadow-sm border bg-body">
                <div class="card-header bg-transparent border-bottom text-center py-4">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 d-inline-block p-3 rounded-circle">
                            <i class="fas fa-weight-scale fa-2x text-success"></i>
                        </div>
                    </div>
                    <h4 class="mb-1 text-body fw-bold">Selesai Sesi</h4>
                    <p class="text-muted mb-0">
                        <span class="text-body fw-semibold">{{ $transaksi->nama_pelanggan }}</span> - Kolam {{ $transaksi->meja->nama_meja }}
                    </p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transaksi.selesai.proses', $transaksi->id) }}" method="POST" id="selesaiForm">
                        @csrf
                        @method('PUT')

                        {{-- Detail Sesi dengan bg-body-tertiary --}}
                        <div class="card border mb-4 bg-body-tertiary">
                            <div class="card-body py-3">
                                <h6 class="mb-3 text-body small fw-bold text-uppercase" style="letter-spacing: 1px;">Detail Waktu</h6>
                                <div class="row small">
                                    <div class="col-6 border-end">
                                        <p class="mb-1 text-muted">Mulai</p>
                                        <p class="mb-0 text-body fw-bold">{{ $transaksi->waktu_mulai->format('H:i') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1 text-muted">Selesai (Est)</p>
                                        <p class="mb-0 text-body fw-bold">{{ $transaksi->waktu_selesai ? $transaksi->waktu_selesai->format('H:i') : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Input Hasil Tangkapan --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="jumlah_ikan_kecil" class="form-label text-body fw-medium">
                                    Ikan Kecil (ekor)
                                </label>
                                <input type="number" 
                                       name="jumlah_ikan_kecil" 
                                       id="jumlah_ikan_kecil" 
                                       class="form-control bg-body-tertiary border-secondary-subtle" 
                                       value="0" 
                                       min="0"
                                       required>
                                <div class="form-text text-success small"><i class="fas fa-tag me-1"></i>Rp 5.000/ekor</div>
                            </div>

                            <div class="col-md-6">
                                <label for="berat_ikan_babon" class="form-label text-body fw-medium">
                                    Ikan Babon (kg)
                                </label>
                                <input type="number" 
                                       name="berat_ikan_babon" 
                                       id="berat_ikan_babon" 
                                       class="form-control bg-body-tertiary border-secondary-subtle" 
                                       value="0" 
                                       min="0" 
                                       step="0.1"
                                       required>
                                <div class="form-text text-success small"><i class="fas fa-tag me-1"></i>Rp 25.000/kg</div>
                            </div>
                        </div>

                        {{-- Total Biaya Preview Highlight --}}
                        <div class="card border-success bg-success bg-opacity-10 mb-4 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-2 text-success fw-bold text-uppercase small">Total Tagihan</h6>
                                <h3 class="mb-0 fw-bold text-success" id="total_preview">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </h3>
                                <small class="text-muted small">Paket + Hasil Tangkapan</small>
                            </div>
                        </div>

                        {{-- Input Pembayaran & Kembalian --}}
                        <div class="card border-0 bg-body-tertiary mb-4 shadow-sm">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="uang_diterima" class="form-label small fw-bold text-body text-uppercase">Uang Diterima</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-secondary-subtle">Rp</span>
                                            <input type="number" 
                                                id="uang_diterima" 
                                                class="form-control form-control-lg border-0 shadow-none bg-secondary-subtle fw-bold" 
                                                placeholder="0"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-body text-uppercase mb-3">Kembalian</label>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0 fw-bold text-primary" id="kembalian_display">Rp 0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-muted p-0">
                                <i class="fas fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm" id="submitBtn">
                                <i class="fas fa-check-circle me-2"></i>Selesai & Bayar
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
    /* Styling input agar nyaman di dark mode */
    .form-control:focus {
        background-color: var(--bs-body-bg) !important;
        border-color: var(--bs-success);
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
    }
    
    /* Menghilangkan arrow spinner di input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ikanKecilInput = document.getElementById('jumlah_ikan_kecil');
    const ikanBabonInput = document.getElementById('berat_ikan_babon');
    const uangDiterimaInput = document.getElementById('uang_diterima');
    
    const totalPreview = document.getElementById('total_preview');
    const kembalianDisplay = document.getElementById('kembalian_display');
    const submitBtn = document.getElementById('submitBtn');
    
    const hargaPaket = {{ $transaksi->total_harga }};
    const hargaIkanKecil = 5000;
    const hargaIkanBabon = 25000;

    function formatRupiah(angka) {
        return 'Rp ' + Math.max(0, angka).toLocaleString('id-ID');
    }

    function calculateEverything() {
        // 1. Hitung Total Tagihan
        const ikanKecil = parseInt(ikanKecilInput.value) || 0;
        const ikanBabon = parseFloat(ikanBabonInput.value) || 0;
        
        const totalTagihan = hargaPaket + (ikanKecil * hargaIkanKecil) + (ikanBabon * hargaIkanBabon);
        totalPreview.textContent = formatRupiah(totalTagihan);

        // 2. Hitung Kembalian
        const uangDiterima = parseInt(uangDiterimaInput.value) || 0;
        const kembalian = uangDiterima - totalTagihan;

        // Tampilkan kembalian (hanya jika uang cukup)
        if (uangDiterima > 0 && kembalian >= 0) {
            kembalianDisplay.textContent = formatRupiah(kembalian);
            kembalianDisplay.classList.replace('text-danger', 'text-primary');
            submitBtn.disabled = false; // Aktifkan tombol jika uang cukup
        } else if (uangDiterima > 0 && kembalian < 0) {
            kembalianDisplay.textContent = 'Uang Kurang';
            kembalianDisplay.classList.replace('text-primary', 'text-danger');
            submitBtn.disabled = true; // Matikan tombol jika uang kurang
        } else {
            kembalianDisplay.textContent = 'Rp 0';
            submitBtn.disabled = true;
        }
    }

    // Jalankan setiap ada input
    ikanKecilInput.addEventListener('input', calculateEverything);
    ikanBabonInput.addEventListener('input', calculateEverything);
    uangDiterimaInput.addEventListener('input', calculateEverything);

    // Jalankan sekali saat load
    calculateEverything();
});
</script>
@endpush
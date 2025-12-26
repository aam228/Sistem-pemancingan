@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-body">Dashboard</h4>
            <p class="text-muted mb-0">Combro Fishing Management</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-body border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            {{-- Gunakan opacity pada background agar ikon tetap kontras di dark mode --}}
                            <div class="rounded-circle p-3 bg-success bg-opacity-10">
                                <i class="fas fa-water text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 text-body">
                            <h5 class="mb-0 fw-bold">{{ $mejas->where('status','tersedia')->count() }}</h5>
                            <p class="text-muted mb-0 small uppercase">Kolam Tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-body border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3 bg-warning bg-opacity-10">
                                <i class="fas fa-fish text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 text-body">
                            <h5 class="mb-0 fw-bold">{{ $mejas->where('status','digunakan')->count() }}</h5>
                            <p class="text-muted mb-0 small">Kolam Digunakan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-body border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3 bg-primary bg-opacity-10">
                                <i class="fas fa-clock text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 text-body">
                            <h5 class="mb-0 fw-bold">{{ $transaksis_berjalan->count() ?? 0 }}</h5>
                            <p class="text-muted mb-0 small">Sesi Berjalan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolam List --}}
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 text-body">Daftar Kolam</h5>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
                @foreach($mejas as $meja)
                @php
                    $transaksi_meja = $transaksis_berjalan->firstWhere('meja_id', $meja->id);
                    $sisa_detik = $transaksi_meja ? now()->diffInSeconds($transaksi_meja->waktu_selesai, false) : null;
                @endphp

                <div class="col">
                    <div class="border rounded p-3 h-100 bg-body-tertiary shadow-sm transition-card">
                        {{-- Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-body">
                                <i class="fas fa-water text-primary me-2"></i>
                                {{ $meja->nama_meja }}
                            </h6>
                            @if($meja->status === 'tersedia')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Tersedia</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Digunakan</span>
                            @endif
                        </div>

                        {{-- Info Transaksi Aktif --}}
                        @if($transaksi_meja)
                        <div class="border rounded p-3 mb-3 bg-body shadow-sm">
                            <div class="mb-2">
                                <small class="text-muted">Pelanggan</small>
                                <p class="mb-0 fw-semibold text-body">{{ $transaksi_meja->nama_pelanggan }}</p>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">Mulai: {{ $transaksi_meja->waktu_mulai->format('H:i') }}</small>
                                
                                @if($sisa_detik > 0)
                                <small class="text-muted">Sisa waktu:</small>
                                <div class="fw-bold text-danger countdown" data-seconds="{{ $sisa_detik }}">
                                    {{ gmdate('H:i:s', $sisa_detik) }}
                                </div>
                                @else
                                <div class="text-danger small fw-bold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Waktu Habis
                                </div>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('transaksi.selesai.form', $transaksi_meja->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-check me-1"></i>Selesai
                                </a>
                                <div class="btn-group w-100">
                                    <a href="{{ route('pesanan.create', $transaksi_meja->id) }}" 
                                       class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-plus me-1"></i>Pesan
                                    </a>
                                    <form method="POST" action="{{ route('transaksi.batal', $transaksi_meja->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Batalkan sesi ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Button Mulai Sesi --}}
                        @if($meja->status === 'tersedia')
                        <a href="{{ route('transaksi.create', $meja->id) }}" 
                           class="btn btn-success w-100">
                            <i class="fas fa-play me-1"></i>Mulai Sesi
                        </a>
                        @else
                            @if($transaksi_meja)
                                {{-- Jika ada transaksi, tombol utama sudah di handle di atas --}}
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-pause me-1"></i>Sedang Digunakan
                                </button>
                            @endif
                        @endif

                        {{-- Reset Meja --}}
                        @if($meja->status === 'digunakan' && !$transaksi_meja)
                        <form method="POST" action="{{ route('meja.reset', $meja->id) }}" class="mt-2">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger w-100"
                                    onclick="return confirm('Reset kolam ini secara manual?')">
                                <i class="fas fa-redo me-1"></i>Reset Kolam
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElements = document.querySelectorAll('.countdown');
    
    countdownElements.forEach(el => {
        let seconds = parseInt(el.dataset.seconds, 10);

        function updateTimer() {
            if (seconds <= 0) {
                el.innerHTML = '<span class="text-danger fw-bold">Waktu Habis</span>';
                return;
            }

            const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
            const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            
            el.textContent = `${h}:${m}:${s}`;
            seconds--;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });

    setTimeout(() => {
        window.location.reload();
    }, 120000);
});
</script>

<style>
.countdown {
    font-family: 'Courier New', Courier, monospace;
    font-size: 1rem;
    letter-spacing: 1px;
}
.transition-card {
    transition: transform 0.2s ease-in-out, border-color 0.2s;
}
.transition-card:hover {
    transform: translateY(-3px);
    border-color: var(--bs-primary) !important;
}
[data-bs-theme="dark"] .bg-success-subtle { background-color: rgba(25, 135, 84, 0.2) !important; }
[data-bs-theme="dark"] .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.2) !important; }
[data-bs-theme="dark"] .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.2) !important; }
[data-bs-theme="dark"] .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.2) !important; }
</style>
@endsection
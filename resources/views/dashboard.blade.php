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
            <h4 class="mb-1 text-body fw-bold">Dashboard</h4>
            <p class="text-muted mb-0 small">Combro Fishing Management — Panel Kendali Utama</p>
        </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card bg-body border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3 bg-success bg-opacity-10">
                                <i class="fas fa-water text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 text-body">
                            <h5 class="mb-0 fw-bold">{{ $spots->where('status','tersedia')->count() }}</h5>
                            <p class="text-muted mb-0 small text-uppercase">Spot Tersedia</p>
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
                            <h5 class="mb-0 fw-bold">{{ $spots->where('status','digunakan')->count() }}</h5>
                            <p class="text-muted mb-0 small text-uppercase">Spot Digunakan</p>
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
                            <h5 class="mb-0 fw-bold">{{ $transaksis_berjalan->count() }}</h5>
                            <p class="text-muted mb-0 small text-uppercase">Sesi Berjalan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Spot List --}}
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <h5 class="mb-0 text-body fw-bold">Monitoring Spot Pancing</h5>
        </div>
        <div class="card-body bg-body-tertiary">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
                @foreach($spots as $spot)
                @php
                    // Logika pencarian transaksi berdasarkan spot_id
                    $transaksi_spot = $transaksis_berjalan->firstWhere('spot_id', $spot->id);
                    $sisa_detik = $transaksi_spot ? now()->diffInSeconds($transaksi_spot->waktu_selesai, false) : null;
                    $is_overtime = $transaksi_spot && $sisa_detik <= 0;
                @endphp

                <div class="col">
                    <div class="border rounded p-3 h-100 shadow-sm transition-card {{ $is_overtime ? 'bg-danger bg-opacity-10 border-danger' : 'bg-body' }}">
                        {{-- Header Card --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-body fw-bold">
                                <i class="fas fa-map-marker-alt {{ $is_overtime ? 'text-danger' : 'text-primary' }} me-2"></i>
                                {{ $spot->nama_spot }}
                            </h6>
                            @if($is_overtime)
                                <span class="badge bg-danger animate-pulse border border-danger">LEMBUR</span>
                            @elseif($spot->status === 'tersedia')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Tersedia</span>
                            @elseif($spot->status === 'perawatan')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Perawatan</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Aktif</span>
                            @endif
                        </div>

                        @if($transaksi_spot)
                        <div class="border rounded p-3 mb-3 bg-body-tertiary shadow-sm">
                            <div class="mb-2">
                                <small class="text-muted d-block small">Nama Pemancing</small>
                                <p class="mb-0 fw-bold text-body">{{ $transaksi_spot->nama_pelanggan }}</p>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block small">Mulai: {{ $transaksi_spot->waktu_mulai->format('H:i') }}</small>
                                
                                <div class="mt-2">
                                    <small class="text-muted small">{{ $is_overtime ? 'Kelebihan waktu:' : 'Sisa waktu:' }}</small>
                                    <div class="fw-bold {{ $is_overtime ? 'text-danger fs-5' : 'text-primary fs-5' }} countdown" data-seconds="{{ $sisa_detik }}">
                                        00:00:00
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100 fw-bold" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-plus-circle me-1"></i> Tambah Sesi
                                    </button>
                                    <ul class="dropdown-menu shadow w-100">
                                        <form action="{{ route('transaksi.tambah-sesi', $transaksi_spot->id) }}" method="POST">
                                            @csrf
                                            <li>
                                                <button type="submit" name="sesi_baru" value="pagi" class="dropdown-item d-flex justify-content-between">
                                                    <span>Pagi</span> <small class="text-muted">Rp{{ number_format($transaksi_spot->spot->tarif_pagi, 0, ',', '.') }}</small>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="submit" name="sesi_baru" value="siang" class="dropdown-item d-flex justify-content-between">
                                                    <span>Siang</span> <small class="text-muted">Rp{{ number_format($transaksi_spot->spot->tarif_siang, 0, ',', '.') }}</small>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="submit" name="sesi_baru" value="sore" class="dropdown-item d-flex justify-content-between">
                                                    <span>Sore</span> <small class="text-muted">Rp{{ number_format($transaksi_spot->spot->tarif_sore, 0, ',', '.') }}</small>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="submit" name="sesi_baru" value="malam" class="dropdown-item d-flex justify-content-between">
                                                    <span>Malam</span> <small class="text-muted">Rp{{ number_format($transaksi_spot->spot->tarif_malam, 0, ',', '.') }}</small>
                                                </button>
                                            </li>
                                        </form>
                                    </ul>
                                </div>
                                
                                {{-- Tombol Utama --}}
                                <a href="{{ route('transaksi.selesai.form', $transaksi_spot->id) }}" 
                                class="btn btn-sm {{ $is_overtime ? 'btn-danger shadow' : 'btn-success' }} fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>Selesai & Bayar
                                </a>

                                <div class="btn-group w-100">
                                    <a href="{{ route('pesanan.create', $transaksi_spot->id) }}" 
                                    class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i>Pesan
                                    </a>
                                    <form method="POST" action="{{ route('transaksi.batal', $transaksi_spot->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger ms-1"
                                                onclick="return confirm('Batalkan sesi ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- Button Action untuk Spot Kosong --}}
                        @if($spot->status === 'tersedia')
                        <a href="{{ route('transaksi.create', $spot->id) }}" 
                           class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-play me-2"></i>Mulai Sesi
                        </a>
                        @else
                            @if(!$transaksi_spot)
                                <button class="btn btn-secondary w-100 opacity-75" disabled>
                                    <i class="fas fa-lock me-2"></i>Sedang Digunakan
                                </button>
                            @endif
                        @endif

                        {{-- Tombol Emergency Reset --}}
                        @if($spot->status === 'digunakan' && !$transaksi_spot)
                        <form method="POST" action="{{ route('spot.reset', $spot->id) }}" class="mt-2">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger w-100 py-2"
                                    onclick="return confirm('Reset spot ini secara manual ke status Tersedia?')">
                                <i class="fas fa-redo me-2"></i>Reset Status Spot
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElements = document.querySelectorAll('.countdown');
    
    function updateTimers() {
        countdownElements.forEach(el => {
            let seconds = parseInt(el.dataset.seconds, 10);
            
            const absSeconds = Math.abs(seconds);
            const h = String(Math.floor(absSeconds / 3600)).padStart(2, '0');
            const m = String(Math.floor((absSeconds % 3600) / 60)).padStart(2, '0');
            const s = String(absSeconds % 60).padStart(2, '0');
            
            el.textContent = (seconds < 0 ? '- ' : '') + `${h}:${m}:${s}`;
            el.dataset.seconds = seconds - 1;

            // Trigger reload jika waktu pas habis untuk update visual card
            if (seconds === 0) {
                setTimeout(() => location.reload(), 1000);
            }
        });
    }

    setInterval(updateTimers, 1000);
    updateTimers();

    // Auto-refresh setiap 10 menit untuk sinkronisasi data server
    setTimeout(() => {
        window.location.reload();
    }, 600000);
});
</script>
@endpush

@push('styles')
<style>
.countdown {
    font-family: 'Courier New', Courier, monospace;
    letter-spacing: 1px;
}
.transition-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.transition-card:hover {
    transform: translateY(-2px);
}
.animate-pulse {
    animation: pulse-red 2s infinite;
}
@keyframes pulse-red {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(0.95); }
    100% { opacity: 1; transform: scale(1); }
}
</style>
@endpush
@endsection
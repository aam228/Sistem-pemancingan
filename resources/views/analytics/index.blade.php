@extends('layouts.app')

@section('title', 'Analytics Bisnis')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')
<div x-data="{ activeTab: 'ringkasan' }" class="container-fluid py-4">
    
    <div class="mb-4">
        <h4 class="fw-bold text-body mb-1">
            <i class="fa-solid fa-chart-pie me-2 text-primary"></i>Statistik Bisnis
        </h4>
        <p class="text-secondary small">Analisis performa operasional dan pendapatan.</p>
    </div>

    <div class="d-flex border-bottom mb-4">
        <button class="btn btn-link nav-link px-4 py-2 fw-bold text-decoration-none"
            :class="activeTab === 'ringkasan' ? 'border-bottom border-primary border-3 text-primary' : 'text-muted'"
            @click="activeTab = 'ringkasan'">Ringkasan</button>
        <button class="btn btn-link nav-link px-4 py-2 fw-bold text-decoration-none"
            :class="activeTab === 'spot' ? 'border-bottom border-primary border-3 text-primary' : 'text-muted'"
            @click="activeTab = 'spot'">Analisis Spot & Waktu</button>
    </div>

    <div>
        <div x-show="activeTab === 'ringkasan'" class="animate__animated animate__fadeIn">
            
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card bg-primary text-white border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Hari Ini</small>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card bg-success text-white border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Bulan Ini</small>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card bg-info text-white border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Total Transaksi</small>
                            <h4 class="mb-0 fw-bold">{{ $jumlahTransaksi }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card bg-body border shadow-sm h-100">
                        <div class="card-body p-2 px-3">
                            <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem;">Distribusi Sesi</small>
                            <div class="row g-1">
                                @forelse($distribusiSesi as $sesi)
                                    <div class="col-6" style="font-size: 0.7rem;">
                                        <span class="text-uppercase text-secondary">{{ $sesi->tipe_sesi }}:</span>
                                        <span class="fw-bold text-body">{{ $sesi->jumlah }}</span>
                                    </div>
                                @empty
                                    <small class="text-muted italic">Belum ada data</small>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-body border shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-body mb-4"><i class="fas fa-line-chart me-2 text-primary"></i>Tren Pendapatan (7 Hari Terakhir)</h6>
                    <div style="height:300px;">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'spot'" class="animate__animated animate__fadeIn">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card bg-body border shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-body mb-4"><i class="fas fa-fish me-2 text-success"></i>Pendapatan per Spot</h6>
                            <div style="height:350px;">
                                <canvas id="chartSpot"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card bg-body border shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-body mb-4"><i class="fas fa-clock me-2 text-info"></i>Jam Kedatangan Pelanggan</h6>
                            <div style="height:350px;">
                                <canvas id="chartJamSibuk"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Deteksi Tema (Light/Dark)
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const textColor = isDark ? '#adb5bd' : '#495057';
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.maintainAspectRatio = false;

    // 1. Chart Tren Pendapatan
    new Chart(document.getElementById('chartPendapatan'), {
        type: 'line',
        data: {
            labels: {!! json_encode($pendapatanPerHari->pluck('tanggal')) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($pendapatanPerHari->pluck('total')) !!},
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } }
            }
        }
    });

    // 2. Chart Pendapatan per Spot
    new Chart(document.getElementById('chartSpot'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($pendapatanPerSpot->pluck('nama_spot')) !!},
            datasets: [{
                data: {!! json_encode($pendapatanPerSpot->pluck('total')) !!},
                backgroundColor: '#198754',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } }
            }
        }
    });

    // 3. Chart Jam Sibuk
    new Chart(document.getElementById('chartJamSibuk'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($jamSibuk->map(fn($j) => $j->jam . ':00')) !!},
            datasets: [{
                data: {!! json_encode($jamSibuk->pluck('jumlah')) !!},
                backgroundColor: '#0dcaf0',
                borderRadius: 5
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Analytics Bisnis')

@section('head')
    {{-- Memastikan Library ter-load di awal --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')
<div x-data="{ activeTab: 'ringkasan' }" class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-body fw-bold">
                <i class="fa-solid fa-chart-line me-2 text-primary"></i>Statistik Bisnis
            </h4>
            <p class="text-muted small mb-0">Laporan performa operasional Combro Fishing</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs border-bottom mb-4">
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-3" 
                    :class="activeTab === 'ringkasan' ? 'active border-primary border-bottom border-3 text-primary' : 'text-muted border-0'"
                    @click="activeTab = 'ringkasan'">
                <i class="fas fa-th-large me-2"></i>Ringkasan
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4 py-3" 
                    :class="activeTab === 'spot' ? 'active border-primary border-bottom border-3 text-primary' : 'text-muted border-0'"
                    @click="activeTab = 'spot'">
                <i class="fas fa-map-marker-alt me-2"></i>Analisis Spot
            </button>
        </li>
    </ul>

    <div>
        {{-- TAB 1: RINGKASAN --}}
        <div x-show="activeTab === 'ringkasan'" class="animate__animated animate__fadeIn">
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card bg-primary text-white border-0 shadow-sm">
                        <div class="card-body p-3">
                            <small class="text-white-50 text-uppercase fw-bold small">Hari Ini</small>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card bg-success text-white border-0 shadow-sm">
                        <div class="card-body p-3">
                            <small class="text-white-50 text-uppercase fw-bold small">Bulan Ini</small>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card bg-info text-white border-0 shadow-sm">
                        <div class="card-body p-3">
                            <small class="text-white-50 text-uppercase fw-bold small">Transaksi</small>
                            <h4 class="mb-0 fw-bold">{{ $jumlahTransaksi }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card bg-warning text-dark border-0 shadow-sm">
                        <div class="card-body p-3">
                            <small class="text-black-50 text-uppercase fw-bold small">Rerata Durasi</small>
                            <h4 class="mb-0 fw-bold">{{ number_format($rataRataDurasi, 1) }} Jam</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-body border shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-body mb-4"><i class="fas fa-chart-area me-2 text-primary"></i>Tren Pendapatan Harian</h6>
                    <div style="position: relative; height:300px; width:100%">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: ANALISIS SPOT --}}
        <div x-show="activeTab === 'spot'" class="animate__animated animate__fadeIn">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card bg-body border shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-body mb-4"><i class="fas fa-water me-2 text-success"></i>Pendapatan per Spot Pancing</h6>
                            <div style="position: relative; height:350px; width:100%">
                                <canvas id="chartSpot"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card bg-body border shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-body mb-4"><i class="fas fa-clock me-2 text-info"></i>Jam Paling Sibuk</h6>
                            <div style="position: relative; height:350px; width:100%">
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
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const textColor = isDark ? '#adb5bd' : '#495057';
    const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';

    // Global Defaults
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.responsive = true;

    // 1. Chart Pendapatan
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
                tension: 0.4
            }]
        },
        options: { 
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            } 
        }
    });

    // 2. Chart Spot (Disesuaikan variabel dari Controller)
    new Chart(document.getElementById('chartSpot'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($pendapatanPerSpot->pluck('nama_spot')) !!},
            datasets: [{
                data: {!! json_encode($pendapatanPerSpot->pluck('total')) !!},
                backgroundColor: '#198754',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
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
                borderRadius: 4
            }]
        },
        options: { 
            plugins: { 
                legend: { display: false } 
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endpush
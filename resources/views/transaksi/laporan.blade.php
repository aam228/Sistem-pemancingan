@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('transaksi.histori') }}">Histori Transaksi</a></li>
    <li class="breadcrumb-item active">Laporan Keuangan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border border-secondary-subtle shadow-sm">
        <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3">
            <h5 class="mb-0 text-body fw-bold">Laporan Keuangan</h5>
        </div>

        <div class="card-body">
            <div class="card bg-body-tertiary border border-secondary-subtle mb-4 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('transaksi.laporan') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="tanggal_mulai" class="form-label text-body small fw-bold">TANGGAL MULAI</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                       class="form-control form-control-sm bg-body border-secondary-subtle" 
                                       value="{{ request('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_selesai" class="form-label text-body small fw-bold">TANGGAL SELESAI</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                       class="form-control form-control-sm bg-body border-secondary-subtle" 
                                       value="{{ request('tanggal_selesai') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="nama_pelanggan" class="form-label text-body small fw-bold">PELANGGAN</label>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" 
                                       class="form-control form-control-sm bg-body border-secondary-subtle" 
                                       placeholder="Cari nama..."
                                       value="{{ request('nama_pelanggan') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="spot_id" class="form-label text-body small fw-bold">SPOT PANCING</label>
                                <select name="spot_id" id="spot_id" class="form-select form-select-sm bg-body border-secondary-subtle">
                                    <option value="">Semua Spot</option>
                                    @foreach($spots as $spot)
                                        <option value="{{ $spot->id }}" {{ request('spot_id') == $spot->id ? 'selected' : '' }}>
                                            {{ $spot->nama_spot ?? 'Spot #' . $spot->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                                <i class="fas fa-filter me-1"></i> Filter Data
                            </button>
                            <a href="{{ route('transaksi.cetak', request()->query()) }}" 
                               class="btn btn-success btn-sm px-3 shadow-sm" target="_blank">
                                <i class="fas fa-print me-1"></i> Cetak PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card bg-body border border-secondary-subtle shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center g-0 text-center text-md-start">
                        <div class="col-md-4 border-end-md pe-md-4 ps-2 mb-3 mb-md-0">
                            <small class="text-uppercase fw-bold text-secondary mb-1 d-block" style="font-size: 0.7rem;">
                                Total Pendapatan
                            </small>
                            <h3 class="mb-0 fw-bold text-success">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h3>
                        </div>

                        <div class="col-md-8 ps-md-4">
                            <div class="row g-2">
                                <div class="col-4">
                                    <small class="text-muted d-block small mb-1">CASH</small>
                                    <div class="fw-bold text-body">Rp {{ number_format($totalCash ?? 0, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4 border-start border-secondary-subtle ps-3">
                                    <small class="text-muted d-block small mb-1">TRANSFER</small>
                                    <div class="fw-bold text-body">Rp {{ number_format($totalTransfer ?? 0, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4 border-start border-secondary-subtle ps-3">
                                    <small class="text-muted d-block small mb-1">QRIS</small>
                                    <div class="fw-bold text-body">Rp {{ number_format($totalQris ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-body-tertiary text-body-secondary border-bottom border-secondary-subtle">
                        <tr class="text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            <th class="ps-3 py-3">ID</th>
                            <th>Pelanggan</th>
                            <th>Spot</th>
                            <th>Ikan (K/B)</th>
                            <th>Total</th>
                            <th>Metode & Penerima</th> <th>Waktu</th>
                            <th class="pe-3">Input</th>
                        </tr>
                    </thead>
                    <tbody class="text-body border-top-0">
                        @forelse($transaksis as $transaksi)
                        <tr>
                            <td class="ps-3 text-body-secondary fw-bold">#{{ $transaksi->id }}</td>
                            <td class="text-nowrap fw-medium">{{ Str::limit($transaksi->nama_pelanggan, 15) }}</td>
                            <td><span class="badge border border-primary-subtle text-primary">{{ $transaksi->spot->nama_spot ?? 'N/A' }}</span></td>
                            <td>
                                <div class="fw-bold">{{ $transaksi->jumlah_ikan_kecil }} <small class="text-muted">ekor</small></div>
                                <div class="small">{{ $transaksi->berat_ikan_babon }} <small class="text-muted">kg</small></div>
                            </td>
                            <td class="text-nowrap fw-bold text-success">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($transaksi->paymentMethod)
                                    <div class="lh-sm">
                                        <div class="fw-bold text-body" style="font-size: 0.75rem;">{{ $transaksi->paymentMethod->nama_metode }}</div>
                                        <div class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">
                                            A.N. {{ $transaksi->paymentMethod->nama_pemilik ?? '-' }}
                                        </div>
                                        <div class="text-muted italic" style="font-size: 0.6rem;">
                                            {{ strtoupper($transaksi->paymentMethod->tipe) }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted italic opacity-50">-</span>
                                @endif
                            </td>
                            <td class="text-nowrap text-body-secondary" style="font-size: 0.75rem;">
                                <div>Selesai: {{ optional($transaksi->waktu_selesai)->format('d/m/y H:i') }}</div>
                                <div class="opacity-50">Durasi: {{ $transaksi->durasi ?? '-' }} Jam</div>
                            </td>
                            <td class="text-nowrap text-body-secondary small pe-3">{{ optional($transaksi->created_at)->format('d/m/y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted bg-body-tertiary">
                                <i class="fas fa-search fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada data transaksi untuk filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid var(--bs-border-color) !important;
        }
    }
    .table thead th {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        color: var(--bs-secondary-color);
    }
    .italic { font-style: italic; }
</style>
@endpush
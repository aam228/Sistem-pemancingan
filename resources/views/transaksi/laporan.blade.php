@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('transaksi.histori') }}">Histori Transaksi</a></li>
    <li class="breadcrumb-item active">Laporan Keuangan</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <h5 class="mb-0 text-body fw-bold">Laporan Keuangan</h5>
        </div>

        <div class="card-body">
            {{-- Filter Form --}}
            <div class="card bg-body-tertiary border mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('transaksi.laporan') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="tanggal_mulai" class="form-label text-body small fw-bold">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                       class="form-control form-control-sm bg-body border-secondary-subtle" 
                                       value="{{ request('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_selesai" class="form-label text-body small fw-bold">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                       class="form-control form-control-sm bg-body border-secondary-subtle" 
                                       value="{{ request('tanggal_selesai') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="nama_pelanggan" class="form-label text-body small fw-bold">Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" 
                                       class="form-control form-control-sm bg-body border-secondary-subtle" 
                                       placeholder="Cari nama..."
                                       value="{{ request('nama_pelanggan') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="meja_id" class="form-label text-body small fw-bold">Spot/Meja</label>
                                <select name="meja_id" id="meja_id" class="form-select form-select-sm bg-body border-secondary-subtle">
                                    <option value="">Semua Meja</option>
                                    @foreach($mejas as $meja)
                                        <option value="{{ $meja->id }}" {{ request('meja_id') == $meja->id ? 'selected' : '' }}>
                                            {{ $meja->nama_meja ?? 'Meja #' . $meja->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('transaksi.cetak', request()->query()) }}" 
                               class="btn btn-success btn-sm px-3" target="_blank">
                                <i class="fas fa-print me-1"></i> Cetak PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="card bg-success bg-opacity-10 border-success-subtle mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-success fw-bold text-uppercase small">Total Pendapatan</h6>
                            <p class="text-body-secondary mb-0 small">Periode: 
                                @if(request('tanggal_mulai') && request('tanggal_selesai'))
                                    <span class="fw-semibold">{{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }} - {{ date('d/m/Y', strtotime(request('tanggal_selesai'))) }}</span>
                                @else
                                    <span class="fw-semibold">Semua Waktu</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-0 text-success fw-bold">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-body-secondary text-body-secondary border-bottom">
                        <tr>
                            <th class="ps-3 py-2">ID</th>
                            <th>Pelanggan</th>
                            <th>Meja</th>
                            <th>Durasi</th>
                            <th>Kecil</th>
                            <th>Babon</th>
                            <th>Total</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th class="pe-3">Input</th>
                        </tr>
                    </thead>
                    <tbody class="text-body">
                        @forelse($transaksis as $transaksi)
                        <tr>
                            <td class="ps-3">{{ $transaksi->id }}</td>
                            <td class="text-nowrap fw-medium">{{ Str::limit($transaksi->nama_pelanggan, 15) }}</td>
                            <td>{{ $transaksi->meja->nama_meja ?? 'N/A' }}</td>
                            <td class="text-nowrap">{{ $transaksi->durasi }} Jam</td>
                            <td>{{ $transaksi->jumlah_ikan_kecil }}</td>
                            <td class="text-nowrap">{{ $transaksi->berat_ikan_babon }} Kg</td>
                            <td class="text-nowrap fw-bold text-success">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-nowrap text-body-secondary small">{{ optional($transaksi->waktu_mulai)->format('d/m/y H:i') }}</td>
                            <td class="text-nowrap text-body-secondary small">{{ optional($transaksi->waktu_selesai)->format('d/m/y H:i') }}</td>
                            <td class="text-nowrap text-body-secondary small pe-3">{{ optional($transaksi->created_at)->format('d/m/y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted bg-body-tertiary">
                                <i class="fas fa-search fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada data transaksi yang sesuai dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Reset gaya tabel agar dinamis */
    .table thead th {
        background-color: var(--bs-tertiary-bg);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.7rem;
    }

    .table-hover tbody tr:hover {
        background-color: var(--bs-tertiary-bg) !important;
    }

    /* Memastikan input date memiliki icon yang terlihat di dark mode */
    [data-bs-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
@endpush
@endsection
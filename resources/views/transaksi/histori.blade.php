@extends('layouts.app')

@section('title', 'Histori Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Histori Transaksi</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-body fw-bold">Histori Transaksi</h5>
                <a href="{{ route('transaksi.laporan') }}" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-chart-line me-1"></i> Laporan
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-body-secondary text-body-secondary border-bottom">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Spot</th>
                            <th>Pelanggan</th>
                            <th>Kecil</th>
                            <th>Babon</th>
                            <th>Total</th>
                            <th>Metode Bayar</th> {{-- Kolom yang disesuaikan --}}
                            <th>Waktu Sesi</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-body">
                        @forelse ($transaksis as $index => $transaksi)
                        <tr>
                            <td class="ps-3 text-body-secondary">{{ $transaksis->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $transaksi->spot->nama_spot ?? 'N/A' }}</span>
                            </td>
                            <td class="text-nowrap">{{ Str::limit($transaksi->nama_pelanggan, 15) }}</td>
                            <td>{{ $transaksi->jumlah_ikan_kecil }} <small class="text-muted">ekor</small></td>
                            <td class="text-nowrap">{{ $transaksi->berat_ikan_babon }} <small class="text-muted">Kg</small></td>
                            <td class="text-nowrap fw-bold text-success">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($transaksi->paymentMethod)
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-body">{{ $transaksi->paymentMethod->nama_metode }}</span>
                                        <small class="text-muted text-uppercase" style="font-size: 0.65rem;">
                                            {{ $transaksi->paymentMethod->tipe }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted small italic">-</span>
                                @endif
                            </td>
                            <td class="text-nowrap text-body-secondary small">
                                <div><i class="far fa-clock me-1"></i> {{ optional($transaksi->waktu_mulai)->format('d/m/y H:i') }}</div>
                                <div><i class="fas fa-check-circle me-1 text-success"></i> {{ optional($transaksi->waktu_selesai)->format('d/m/y H:i') }}</div>
                            </td>
                            <td class="text-center pe-3">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger border-0 py-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal"
                                        data-transaksi-id="{{ $transaksi->id }}"
                                        data-nama-pelanggan="{{ $transaksi->nama_pelanggan }}"
                                        data-total-harga="{{ number_format($transaksi->total_harga, 0, ',', '.') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted bg-body-tertiary">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada data histori transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-transparent border-top py-3">
                <nav aria-label="Page navigation" class="mb-0">
                    {{ $transaksis->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body fw-bold">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-body">
                <p>Anda yakin ingin menghapus transaksi ini? Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="bg-body-tertiary p-3 rounded border shadow-sm">
                    <p class="mb-1"><strong>Pelanggan:</strong> <span id="modal-nama-pelanggan"></span></p>
                    <p class="mb-0 fw-medium text-danger"><strong>Total Tagihan:</strong> Rp <span id="modal-total-harga"></span></p>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-3">Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            document.getElementById('modal-nama-pelanggan').textContent = btn.dataset.namaPelanggan;
            document.getElementById('modal-total-harga').textContent = btn.dataset.totalHarga;
            document.getElementById('deleteForm').action = `/transaksi/${btn.dataset.transaksiId}`;
        });
    }
});
</script>

<style>
    .table thead th {
        background-color: var(--bs-tertiary-bg);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.7rem;
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        color: var(--bs-secondary-color);
    }

    .table-hover tbody tr:hover {
        background-color: var(--bs-tertiary-bg) !important;
    }

    .pagination {
        margin-bottom: 0;
        gap: 4px; 
    }

    .pagination .page-link {
        border-radius: 6px !important; 
        border: 1px solid var(--bs-border-color);
        color: var(--bs-secondary-color);
        padding: 6px 12px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .pagination .page-link:hover {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
        transform: translateY(-1px); 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
        box-shadow: 0 4px 6px -1px rgba(var(--bs-primary-rgb), 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background-color: var(--bs-tertiary-bg);
        color: var(--bs-tertiary-color);
        opacity: 0.6;
    }

    .btn-outline-danger i {
        font-size: 0.85rem;
    }
</style>
@endpush
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
                            <th>Durasi</th>
                            <th>Kecil</th>
                            <th>Babon</th>
                            <th>Total</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-body">
                        @forelse ($transaksis as $index => $transaksi)
                        <tr>
                            <td class="ps-3">{{ $transaksis->firstItem() + $index }}</td>
                            <td class="fw-medium">{{ $transaksi->meja->nama_meja ?? 'N/A' }}</td>
                            <td class="text-nowrap">{{ Str::limit($transaksi->nama_pelanggan, 15) }}</td>
                            <td class="text-nowrap">{{ $transaksi->durasi }} Jam</td>
                            <td>{{ $transaksi->jumlah_ikan_kecil }}</td>
                            <td class="text-nowrap">{{ $transaksi->berat_ikan_babon }} Kg</td>
                            <td class="text-nowrap fw-bold text-success">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-nowrap text-body-secondary small">{{ optional($transaksi->waktu_mulai)->format('d/m/y H:i') }}</td>
                            <td class="text-nowrap text-body-secondary small">{{ optional($transaksi->waktu_selesai)->format('d/m/y H:i') }}</td>
                            <td class="text-center pe-3">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger border-0 py-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal"
                                        data-transaksi-id="{{ $transaksi->id }}"
                                        data-nama-pelanggan="{{ $transaksi->nama_pelanggan }}"
                                        data-nama-meja="{{ $transaksi->meja->nama_meja ?? 'N/A' }}"
                                        data-jumlah-ikan-kecil="{{ $transaksi->jumlah_ikan_kecil }}"
                                        data-berat-ikan-babon="{{ $transaksi->berat_ikan_babon }}"
                                        data-total-harga="{{ number_format($transaksi->total_harga, 0, ',', '.') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted bg-body-tertiary">
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
            <div class="d-flex justify-content-center">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-body">
                <p>Anda yakin ingin menghapus transaksi ini?</p>
                <div class="bg-body-tertiary p-3 rounded border shadow-sm">
                    <p class="mb-1"><strong>Spot:</strong> <span id="modal-nama-meja"></span></p>
                    <p class="mb-1"><strong>Pelanggan:</strong> <span id="modal-nama-pelanggan"></span></p>
                    <p class="mb-1 fw-medium text-primary"><strong>Total Tagihan:</strong> Rp <span id="modal-total-harga"></span></p>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
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
            document.getElementById('modal-nama-meja').textContent = btn.dataset.namaMeja;
            document.getElementById('modal-nama-pelanggan').textContent = btn.dataset.namaPelanggan;
            document.getElementById('modal-total-harga').textContent = btn.dataset.totalHarga;
            document.getElementById('deleteForm').action = `/transaksi/${btn.dataset.transaksiId}`;
        });
    }
});
</script>

<style>
    /* Reset gaya manual agar mengikuti tema Bootstrap */
    .table thead th {
        background-color: var(--bs-tertiary-bg);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }

    .table-responsive {
        border-radius: 0;
        overflow-x: auto;
    }

    /* Memastikan table tetap readable di mobile */
    @media (max-width: 992px) {
        .table { min-width: 900px; }
    }

    /* Efek hover yang lebih lembut sesuai tema */
    .table-hover tbody tr:hover {
        background-color: var(--bs-tertiary-bg) !important;
    }

    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush
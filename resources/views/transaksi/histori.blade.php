@extends('layouts.app')

@section('title', 'Histori Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Histori Transaksi</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border border-secondary-subtle shadow-sm">
        <div class="card-header bg-transparent border-bottom border-secondary-subtle py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-body fw-bold">Histori Transaksi</h5>
                <a href="{{ route('transaksi.laporan') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="fas fa-chart-line me-1"></i> Laporan Lanjutan
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-body-tertiary border-bottom border-secondary-subtle">
                        <tr class="text-body-secondary small text-uppercase">
                            <th class="ps-3 py-3" width="50">No</th>
                            <th>Spot</th>
                            <th>Pelanggan</th>
                            <th>Ikan (K/B)</th>
                            <th>Total</th>
                            <th>Metode & Penerima</th>
                            <th>Waktu Sesi</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-body border-top-0">
                        @forelse ($transaksis as $index => $transaksi)
                        <tr>
                            <td class="ps-3 text-body-secondary">{{ $transaksis->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $transaksi->spot->nama_spot ?? 'N/A' }}</span>
                            </td>
                            <td class="text-nowrap fw-medium">{{ Str::limit($transaksi->nama_pelanggan, 18) }}</td>
                            <td>
                                <div class="fw-bold">{{ $transaksi->jumlah_ikan_kecil ?? 0 }} <span class="small fw-normal text-muted">ekor</span></div>
                                <div class="small">{{ $transaksi->berat_ikan_babon ?? 0 }} <span class="small fw-normal text-muted">kg</span></div>
                            </td>
                            <td class="text-nowrap fw-bold text-success">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($transaksi->paymentMethod)
                                    <div class="d-flex flex-column lh-sm">
                                        <span class="fw-bold text-body" style="font-size: 0.75rem;">{{ $transaksi->paymentMethod->nama_metode }}</span>
                                        <span class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">
                                            A.N. {{ $transaksi->paymentMethod->nama_pemilik ?? '-' }}
                                        </span>
                                        <span class="text-muted italic" style="font-size: 0.6rem;">
                                            {{ strtoupper($transaksi->paymentMethod->tipe) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted small italic opacity-50">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-nowrap text-body-secondary" style="font-size: 0.75rem;">
                                <div class="mb-1"><i class="far fa-clock me-1 opacity-50"></i> {{ optional($transaksi->waktu_mulai)->format('d/m/y H:i') }}</div>
                                <div class="text-success"><i class="fas fa-check-circle me-1"></i> {{ optional($transaksi->waktu_selesai)->format('d/m/y H:i') }}</div>
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
                            <td colspan="8" class="text-center py-5 text-muted bg-body-tertiary">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada histori transaksi ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-transparent border-top border-secondary-subtle py-3">
            {{ $transaksis->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content bg-body border border-secondary-subtle shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-circle text-danger fa-3 font-size-3rem"></i>
                </div>
                <h6 class="text-body fw-bold mb-3">Hapus Histori?</h6>
                <div class="bg-body-tertiary p-3 rounded border border-secondary-subtle small mb-4 text-start">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Pelanggan:</div>
                    <div class="fw-bold text-body mb-2" id="modal-nama-pelanggan"></div>
                    <div class="text-muted small text-uppercase fw-bold mb-1">Tagihan:</div>
                    <div class="text-danger fw-bold h6 mb-0" id="modal-total-harga"></div>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Ya, Hapus Permanen</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    </div>
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
            document.getElementById('modal-total-harga').textContent = 'Rp ' + btn.dataset.totalHarga;
            document.getElementById('deleteForm').action = `/transaksi/${btn.dataset.transaksiId}`;
        });
    }
});
</script>

<style>
    /* Styling Tambahan */
    .table thead th {
        font-size: 0.65rem;
        letter-spacing: 0.5px;
    }
    .table-hover tbody tr:hover {
        background-color: var(--bs-tertiary-bg) !important;
    }
    .font-size-3rem {
        font-size: 3rem;
    }
    .italic { font-style: italic; }
</style>
@endpush
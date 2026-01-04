@extends('layouts.app')

@section('title', 'Metode Pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Metode Pembayaran</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-body fw-bold">Pengaturan Pembayaran</h5>
                <a href="{{ route('payment-methods.create') }}" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-plus me-1"></i> Tambah Metode
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    {{-- Menggunakan tertiary-bg agar header kontras di kedua tema --}}
                    <thead class="table-body-secondary text-body-secondary border-bottom">
                        <tr>
                            <th class="ps-3" width="50">No</th>
                            <th>Tipe</th>
                            <th>Metode & Pemilik</th>
                            <th>Detail (Rekening/QR)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-3" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-body">
                        @forelse ($methods as $index => $m)
                        <tr>
                            <td class="ps-3">{{ $index + 1 }}</td>
                            <td>
                                {{-- Ganti text-dark ke text-body agar menyesuaikan tema --}}
                                <span class="badge border text-body text-uppercase small" style="font-size: 0.65rem;">
                                    {{ $m->tipe }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $m->nama_metode }}</div>
                                <small class="text-muted">{{ $m->nama_pemilik ?? '-' }}</small>
                            </td>
                            <td>
                                @if($m->tipe === 'qris')
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ asset('storage/'.$m->qr_image) }}" 
                                             class="img-thumbnail bg-body shadow-sm img-preview-trigger border-secondary-subtle" 
                                             style="max-width: 50px; cursor: pointer;" 
                                             alt="QRIS"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#previewImageModal"
                                             data-url="{{ asset('storage/'.$m->qr_image) }}"
                                             data-title="{{ $m->nama_metode }} - {{ $m->nama_pemilik }}">
                                        <small class="d-block text-muted mt-1" style="font-size: 10px;">Klik untuk lihat</small>
                                    </div>
                                @elseif($m->tipe === 'transfer')
                                    <div class="lh-sm">
                                        <span class="text-uppercase fw-bold small d-block text-body-secondary">{{ $m->nama_bank }}</span>
                                        <code class="text-primary fs-6">{{ $m->no_rekening }}</code>
                                    </div>
                                @else
                                    <span class="text-muted small italic text-body-secondary">Pembayaran Tunai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $m->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    {{-- Menggunakan outline agar lebih bersih di dark mode --}}
                                    <a href="{{ route('payment-methods.edit', $m->id) }}" class="btn btn-sm btn-outline-warning border-0">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-id="{{ $m->id }}" data-nama="{{ $m->nama_metode }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted bg-body-tertiary">
                                <i class="fas fa-wallet fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada metode pembayaran yang dikonfigurasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW GAMBAR (Tetap Dark Backdrop untuk Fokus) --}}
<div class="modal fade" id="previewImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-white fw-bold" id="previewTitle"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="imageFull" class="img-fluid rounded shadow-lg border border-3 border-white" style="max-height: 75vh;">
            </div>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-body text-center p-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <h6 class="mb-2 text-body">Hapus Metode?</h6>
                <p class="small text-muted mb-4">Metode <strong id="modal-nama-metode" class="text-body"></strong> akan dihapus permanen.</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                        {{-- Ganti btn-light ke btn-secondary agar terlihat di dark mode --}}
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
    const previewModal = document.getElementById('previewImageModal');
    previewModal.addEventListener('show.bs.modal', function(event) {
        const trigger = event.relatedTarget;
        document.getElementById('imageFull').src = trigger.dataset.url;
        document.getElementById('previewTitle').textContent = trigger.dataset.title;
    });

    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('modal-nama-metode').textContent = btn.dataset.nama;
        // Pastikan route ini sesuai dengan route:list anda
        document.getElementById('deleteForm').action = `/payment-methods/${btn.dataset.id}`;
    });
});
</script>

<style>
    /* Menggunakan variabel CSS Bootstrap agar otomatis berubah saat switch tema */
    .table thead th {
        background-color: var(--bs-tertiary-bg);
        color: var(--bs-body-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.7rem;
        padding: 12px 8px !important;
    }

    .table-hover tbody tr:hover {
        background-color: var(--bs-tertiary-bg) !important;
    }

    .img-preview-trigger:hover {
        transform: scale(1.05);
        transition: all 0.2s ease;
        border-color: var(--bs-primary) !important;
    }

    #previewImageModal {
        backdrop-filter: blur(8px);
        /* Latar belakang semi-transparan yang cocok untuk kedua tema */
        background-color: rgba(0,0,0,0.6);
    }

    /* Memastikan thumbnail tetap terlihat bagus di dark mode */
    [data-bs-theme="dark"] .img-thumbnail {
        background-color: var(--bs-body-bg);
        border-color: var(--bs-border-color);
    }
</style>
@endpush
@extends('layouts.app')

@section('title', 'Management Spot')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Management Spot</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-body fw-bold">Management Spot</h5>
                <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-1"></i> Tambah Spot
                </button>
            </div>
        </div>

        <div class="card-body bg-body-tertiary">
            @if($spots->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($spots as $spot)
                <div class="col">
                    <div class="card h-100 bg-body border shadow-sm hover-card position-relative">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="p-1 rounded-circle me-2 {{ $spot->status == 'digunakan' ? 'bg-warning' : 'bg-success' }}" style="width: 10px; height: 10px;"></span>
                                    <h6 class="mb-0 text-body fw-bold">{{ $spot->nama_spot }}</h6>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm text-body border-0 shadow-none" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border">
                                        <li>
                                            <button class="dropdown-item py-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal"
                                                    data-spot-id="{{ $spot->id }}"
                                                    data-spot-nama="{{ $spot->nama_spot }}"
                                                    data-tarif-pagi="{{ (int)$spot->tarif_pagi }}"
                                                    data-tarif-siang="{{ (int)$spot->tarif_siang }}"
                                                    data-tarif-sore="{{ (int)$spot->tarif_sore }}"
                                                    data-tarif-malam="{{ (int)$spot->tarif_malam }}">
                                                <i class="fas fa-edit me-2 text-primary"></i> Edit
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('spot.destroy', $spot->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="dropdown-item text-danger py-2"
                                                        onclick="return confirm('Anda yakin ingin menghapus spot ini?')">
                                                    <i class="fas fa-trash-alt me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">Status</small>
                            @if($spot->status == 'digunakan')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Digunakan</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Tersedia</span>
                            @endif
                        </div>
                        
                        {{-- Bagian Melayang --}}
                        <div class="reveal-prices">
                            {{-- Gunakan card-body lagi di sini agar paddingnya otomatis pas (sejajar atasnya) --}}
                            <div class="card-body border-top border-secondary-subtle">
                                <small class="text-muted d-block mb-2 fw-bold text-uppercase" style="font-size: 0.65rem;">Daftar Tarif Sesi</small>
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody class="small">
                                        <tr>
                                            <td class="ps-0 text-body-secondary">Pagi</td>
                                            <td class="pe-0 text-end fw-bold text-success">Rp {{ number_format($spot->tarif_pagi, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 text-body-secondary">Siang</td>
                                            <td class="pe-0 text-end fw-bold text-success">Rp {{ number_format($spot->tarif_siang, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 text-body-secondary">Sore</td>
                                            <td class="pe-0 text-end fw-bold text-success">Rp {{ number_format($spot->tarif_sore, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 text-body-secondary">Malam</td>
                                            <td class="pe-0 text-end fw-bold text-success">Rp {{ number_format($spot->tarif_malam, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-water fa-3x text-muted mb-3"></i>
                <h5 class="text-body">Belum Ada Spot Pancing</h5>
                <p class="text-muted small">Silakan tambahkan spot atau lapak pancing baru untuk memulai.</p>
                <button type="button" class="btn btn-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#createModal">Tambah Sekarang</button>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL CREATE --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-body shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body fw-bold">Tambah Spot Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('spot.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-body fw-bold small">Nama Spot / Lapak</label>
                        <input type="text" name="nama_spot" class="form-control bg-body-tertiary border-secondary-subtle" placeholder="Contoh: Lapak 01" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-2">
                            <label class="form-label text-body small">Tarif Pagi</label>
                            <input type="text" name="tarif_pagi" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" placeholder="0" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label text-body small">Tarif Siang</label>
                            <input type="text" name="tarif_siang" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" placeholder="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-body small">Tarif Sore</label>
                            <input type="text" name="tarif_sore" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" placeholder="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-body small">Tarif Malam</label>
                            <input type="text" name="tarif_malam" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Simpan Spot</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-body shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body fw-bold">Edit Detail Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-body fw-bold small">Nama Spot / Lapak</label>
                        <input type="text" name="nama_spot" id="edit_nama_spot" class="form-control bg-body-tertiary border-secondary-subtle" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-2">
                            <label class="form-label text-body small">Tarif Pagi</label>
                            <input type="text" name="tarif_pagi" id="edit_tarif_pagi" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label text-body small">Tarif Siang</label>
                            <input type="text" name="tarif_siang" id="edit_tarif_siang" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-body small">Tarif Sore</label>
                            <input type="text" name="tarif_sore" id="edit_tarif_sore" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-body small">Tarif Malam</label>
                            <input type="text" name="tarif_malam" id="edit_tarif_malam" class="form-control bg-body-tertiary border-secondary-subtle format-rupiah" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Update Spot</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-card { transition: all 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); border-color: var(--bs-primary) !important; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }
    .modal-content { border-radius: 0.75rem; border: none; }
    .format-rupiah { font-family: 'Courier New', Courier, monospace; font-weight: bold; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi Format Rupiah Real-time
    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.querySelectorAll('.format-rupiah').forEach(input => {
        input.addEventListener('input', function() {
            this.value = formatNumber(this.value);
        });

        // Membersihkan titik sebelum form disubmit ke Controller
        const form = input.closest('form');
        form.addEventListener('submit', () => {
            input.value = input.value.replace(/\./g, '');
        });
    });

    // Modal Edit Handler
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            const id = btn.dataset.spotId;
            const form = document.getElementById('editForm');
            
            // Set Action URL
            form.action = `/spot/${id}`;
            
            // Fill Data
            document.getElementById('edit_nama_spot').value = btn.dataset.spotNama;
            
            const f = (val) => formatNumber(String(val));
            document.getElementById('edit_tarif_pagi').value = f(btn.dataset.tarifPagi);
            document.getElementById('edit_tarif_siang').value = f(btn.dataset.tarifSiang);
            document.getElementById('edit_tarif_sore').value = f(btn.dataset.tarifSore);
            document.getElementById('edit_tarif_malam').value = f(btn.dataset.tarifMalam);
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .hover-card { 
        transition: all 0.2s ease-in-out;
        position: relative;
        z-index: 1;
    }

    .reveal-prices {
        display: none;
        position: absolute;
        top: 100%; 
        left: -1px; 
        right: -1px;
        background-color: var(--bs-body-bg);
        z-index: 999;
        border: 1px solid var(--bs-primary); 
        border-top: none; 
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }

    /* Saat Hover */
    .hover-card:hover { 
        z-index: 1000;
        border-color: var(--bs-primary) !important;
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .hover-card:hover .reveal-prices {
        display: block;
    }

    .inner-padding {
        padding: 1rem; 
    }
</style>
@endpush
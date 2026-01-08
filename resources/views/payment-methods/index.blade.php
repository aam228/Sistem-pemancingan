@extends('layouts.app')

@section('title', 'Metode Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-body fw-bold">Pengaturan Pembayaran</h5>
            {{-- Tombol Tambah memicu Modal --}}
            <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> Tambah Metode
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-3" width="50">No</th>
                            <th>Tipe</th>
                            <th>Metode</th>
                            <th>Detail</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-3" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($methods as $index => $m)
                        <tr>
                            <td class="ps-3">{{ $index + 1 }}</td>
                            <td><span class="badge border text-body small text-uppercase">{{ $m->tipe }}</span></td>
                            <td>
                                <div class="fw-bold text-primary">{{ $m->nama_metode }}</div>
                                <small class="text-muted">{{ $m->nama_pemilik ?? '-' }}</small>
                            </td>
                            <td>
                                @if($m->tipe === 'qris')
                                    <img src="{{ asset('storage/'.$m->qr_image) }}" class="img-thumbnail" style="max-width: 40px; cursor: pointer;" 
                                         data-bs-toggle="modal" data-bs-target="#previewImageModal" data-url="{{ asset('storage/'.$m->qr_image) }}" data-title="{{ $m->nama_metode }}">
                                @elseif($m->tipe === 'transfer')
                                    <small class="text-uppercase fw-bold d-block">{{ $m->nama_bank }}</small>
                                    <code class="text-primary">{{ $m->no_rekening }}</code>
                                @else
                                    <span class="text-muted small italic">Tunai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $m->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-warning border-0" 
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="{{ $m->id }}" data-tipe="{{ $m->tipe }}" data-nama="{{ $m->nama_metode }}"
                                            data-owner="{{ $m->nama_pemilik }}" data-bank="{{ $m->nama_bank }}"
                                            data-rekening="{{ $m->no_rekening }}" data-status="{{ $m->is_active }}"
                                            data-qr="{{ $m->qr_image ? asset('storage/' . $m->qr_image) : '' }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $m->id }}" data-nama="{{ $m->nama_metode }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Panggil File Modals --}}
@include('payment-methods.modals')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === LOGIKA CREATE MODAL ===
    const createTipe = document.getElementById('create_tipe');
    createTipe.addEventListener('change', function() {
        const val = this.value;
        const owner = document.getElementById('create_owner_field');
        const transfer = document.getElementById('create_transfer_fields');
        const qris = document.getElementById('create_qris_fields');
        
        owner.classList.toggle('d-none', !['cash', 'transfer', 'qris'].includes(val));
        transfer.classList.toggle('d-none', val !== 'transfer');
        qris.classList.toggle('d-none', val !== 'qris');
    });

    // === LOGIKA EDIT MODAL ===
    const editModal = document.getElementById('editModal');
    const editTipe = document.getElementById('edit_tipe');
    
    function toggleEditFields(val) {
        document.getElementById('edit_owner_field').classList.toggle('d-none', !['cash', 'transfer', 'qris'].includes(val));
        document.getElementById('edit_transfer_fields').classList.toggle('d-none', val !== 'transfer');
        document.getElementById('edit_qris_fields').classList.toggle('d-none', val !== 'qris');
    }

    editTipe.addEventListener('change', (e) => toggleEditFields(e.target.value));

    editModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('editForm').action = `/payment-methods/${btn.dataset.id}`;
        
        editTipe.value = btn.dataset.tipe;
        document.getElementById('edit_nama_metode').value = btn.dataset.nama;
        document.getElementById('edit_nama_pemilik').value = btn.dataset.owner;
        document.getElementById('edit_nama_bank').value = btn.dataset.bank || '';
        document.getElementById('edit_no_rekening').value = btn.dataset.rekening || '';
        document.getElementById('edit_is_active').checked = btn.dataset.status == "1";

        const qrPreview = document.getElementById('edit_qr_preview');
        const qrContainer = document.getElementById('edit_qr_container');
        if(btn.dataset.qr) {
            qrContainer.classList.remove('d-none');
            qrPreview.src = btn.dataset.qr;
        } else { qrContainer.classList.add('d-none'); }

        toggleEditFields(btn.dataset.tipe);
    });

    // Preview & Delete logic stays the same...
    const previewModal = document.getElementById('previewImageModal');
    previewModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('imageFull').src = btn.dataset.url;
        document.getElementById('previewTitle').textContent = btn.dataset.title;
    });

    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('modal-nama-metode').textContent = btn.dataset.nama;
        document.getElementById('deleteForm').action = `/payment-methods/${btn.dataset.id}`;
    });
});
</script>
@endpush
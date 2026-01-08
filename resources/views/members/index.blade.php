@extends('layouts.app')

@section('title', 'Manajemen Member')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Members</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-body fw-bold">Daftar Member</h5>
            <button class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus me-1"></i> Tambah Member
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-body-tertiary border-bottom">
                        <tr class="text-body-secondary small text-uppercase">
                            <th class="ps-3 py-3">Kode</th>
                            <th>Nama & Poin</th>
                            <th>Kontak</th>
                            <th>Diskon</th>
                            <th>Berlaku</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-body">
                        @forelse($members as $m)
                        @php 
                            $expiredDate = \Carbon\Carbon::parse($m->expired_at);
                            $isExpired = $expiredDate->isPast(); 
                        @endphp
                        <tr>
                            <td class="ps-3 fw-bold text-primary">{{ $m->kode_member }}</td>
                            <td>
                                <div class="fw-bold">{{ $m->nama }}</div>
                                <small class="text-muted">{{ $m->poin }} Poin</small>
                            </td>
                            <td>{{ $m->telepon }}</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2">
                                    {{ $m->diskon_persen }}% OFF
                                </span>
                            </td>
                            <td>
                                <div class="{{ $isExpired ? 'text-danger fw-bold' : '' }}">
                                    {{ $expiredDate->format('d/m/Y') }}
                                    @if($isExpired) <br><span class="badge bg-danger p-1" style="font-size: 7px;">EXPIRED</span> @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $m->status == 'active' && !$isExpired ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $m->status == 'active' && !$isExpired ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-warning border-0" 
                                            data-bs-toggle="modal" data-bs-target="#modalEdit"
                                            data-id="{{ $m->id }}"
                                            data-kode="{{ $m->kode_member }}"
                                            data-nama="{{ $m->nama }}"
                                            data-telepon="{{ $m->telepon }}"
                                            data-diskon="{{ $m->diskon_persen }}"
                                            data-status="{{ $m->status }}"
                                            data-expired="{{ $expiredDate->format('Y-m-d') }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger border-0" 
                                            data-bs-toggle="modal" data-bs-target="#modalHapus"
                                            data-id="{{ $m->id }}"
                                            data-nama="{{ $m->nama }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted bg-body-tertiary">Belum ada member.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('members.modals')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logic Modal Edit
    const modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        
        // Update form action
        document.getElementById('formEditMember').action = `/members/${btn.dataset.id}`;
        
        // Isi field
        document.getElementById('edit_kode_display').textContent = btn.dataset.kode;
        document.getElementById('edit_nama').value = btn.dataset.nama;
        document.getElementById('edit_telepon').value = btn.dataset.telepon;
        document.getElementById('edit_diskon').value = btn.dataset.diskon;
        document.getElementById('edit_status').value = btn.dataset.status;
        document.getElementById('edit_expired_at').value = btn.dataset.expired;
    });

    // Logic Modal Hapus
    const modalHapus = document.getElementById('modalHapus');
    modalHapus.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('hapus_nama_display').textContent = btn.dataset.nama;
        document.getElementById('formHapusMember').action = `/members/${btn.dataset.id}`;
    });
});
</script>
@endpush
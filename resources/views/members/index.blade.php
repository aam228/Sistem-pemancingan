@extends('layouts.app')

@section('title', 'Manajemen Member')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Members</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-body fw-bold">Daftar Member</h5>
                <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah Member
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-body-secondary text-body-secondary border-bottom">
                        <tr>
                            <th class="ps-3">Kode</th>
                            <th>Nama & Poin</th>
                            <th>Kontak</th>
                            <th>Diskon (%)</th>
                            <th>Berlaku Hingga</th>
                            <th>Status</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-body">
                        @forelse($members as $m)
                        @php $isExpired = \Carbon\Carbon::parse($m->expired_at)->isPast(); @endphp
                        <tr>
                            <td class="ps-3 fw-bold text-primary">{{ $m->kode_member }}</td>
                            <td>
                                <div class="fw-bold">{{ $m->nama }}</div>
                                <small class="text-muted">{{ $m->poin }} Poin</small>
                            </td>
                            <td>{{ $m->telepon }}</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    {{ $m->diskon_persen }}% OFF
                                </span>
                            </td>
                            <td>
                                <div class="{{ $isExpired ? 'text-danger fw-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($m->expired_at)->format('d/m/Y') }}
                                    @if($isExpired) <br><small class="badge bg-danger" style="font-size: 8px;">KADALUARSA</small> @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $m->status == 'active' && !$isExpired ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $m->status == 'active' && !$isExpired ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-warning border-0" 
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $m->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('members.destroy', $m->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus member?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="modalEdit{{ $m->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-body border shadow">
                                    <form action="{{ route('members.update', $m->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header border-bottom py-2">
                                            <h6 class="modal-title">Edit Member {{ $m->kode_member }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">NAMA LENGKAP</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $m->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">NOMOR WA</label>
                                                <input type="text" name="telepon" class="form-control" value="{{ $m->telepon }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label small fw-bold text-primary text-uppercase">Persentase Diskon (%)</label>
                                                    <input type="number" name="diskon_persen" class="form-control border-primary" value="{{ $m->diskon_persen }}" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label small fw-bold text-uppercase">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active" {{ $m->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="inactive" {{ $m->status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-uppercase">Masa Berlaku</label>
                                                <input type="date" name="expired_at" class="form-control" value="{{ $m->expired_at }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top py-2">
                                            <button type="submit" class="btn btn-primary btn-sm px-4">Update Member</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-2">
                    <h6 class="modal-title">Registrasi Member Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Pelanggan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Nomor Telepon/WA</label>
                        <input type="text" name="telepon" class="form-control" placeholder="0812xxx" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-primary text-uppercase">Persentase Diskon (%)</label>
                            <input type="number" name="diskon_persen" class="form-control border-primary" value="10" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Berlaku Hingga</label>
                            <input type="date" name="expired_at" class="form-control" value="{{ date('Y-12-31') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table thead th {
        background-color: var(--bs-tertiary-bg);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.7rem;
        padding-top: 12px !important;
        padding-bottom: 12px !important;
    }
</style>
@endpush
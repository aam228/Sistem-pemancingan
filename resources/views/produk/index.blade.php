@extends('layouts.app')

@section('title', 'Kelola Makanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kelola Makanan</li>
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
                <h5 class="mb-0 text-body fw-bold">Kelola Makanan & Minuman</h5>
                <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-1"></i> Tambah Produk
                </button>
            </div>
        </div>

        <div class="card-body bg-body-tertiary">
            @if($produks->isEmpty())
                <div class="alert alert-info border-0 shadow-sm mb-0">
                    <i class="fas fa-info-circle me-2"></i> Belum ada produk yang ditambahkan.
                </div>
            @else
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    @foreach($produks as $produk)
                    <div class="col">
                        <div class="card h-100 bg-body border shadow-sm hover-product-card">
                            <div class="position-relative overflow-hidden">
                                @if($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" 
                                         alt="{{ $produk->nama_produk }}" 
                                         class="card-img-top object-fit-cover"
                                         style="height: 140px;">
                                @else
                                    <div class="bg-body-secondary d-flex align-items-center justify-content-center"
                                         style="height: 140px;">
                                        <i class="fas fa-utensils fa-2x text-muted"></i>
                                    </div>
                                @endif
                                
                                {{-- Overlay Tombol Aksi --}}
                                <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" 
                                            class="btn btn-warning btn-sm shadow"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-produk-id="{{ $produk->id }}"
                                            data-produk-nama="{{ $produk->nama_produk }}"
                                            data-produk-harga="{{ $produk->harga }}"
                                            data-produk-gambar="{{ $produk->gambar }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm shadow"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1 text-truncate text-body" title="{{ $produk->nama_produk }}">
                                    {{ $produk->nama_produk }}
                                </h6>
                                <p class="card-text fw-bold text-success mb-0">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL CREATE --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label text-body small fw-bold">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control bg-body-tertiary" placeholder="Masukkan nama makanan/minuman" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label text-body small fw-bold">Harga (Rp)</label>
                        <input type="text" name="harga" id="harga" class="form-control bg-body-tertiary format-rupiah" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label text-body small fw-bold">Gambar Produk</label>
                        <input type="file" name="gambar" id="gambar" class="form-control bg-body-tertiary">
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_produk" class="form-label text-body small fw-bold">Nama Produk</label>
                        <input type="text" name="nama_produk" id="edit_nama_produk" class="form-control bg-body-tertiary" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga" class="form-label text-body small fw-bold">Harga (Rp)</label>
                        <input type="text" name="harga" id="edit_harga" class="form-control bg-body-tertiary format-rupiah" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gambar" class="form-label text-body small fw-bold">Ganti Gambar (Opsional)</label>
                        <input type="file" name="gambar" id="edit_gambar" class="form-control bg-body-tertiary">
                        <div id="currentImage" class="mt-3 text-center border rounded p-2 bg-body-secondary d-none"></div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-product-card {
        transition: transform 0.2s, border-color 0.2s;
    }
    .hover-product-card:hover {
        transform: translateY(-5px);
        border-color: var(--bs-primary) !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    
    /* Overlay Effect */
    .product-overlay {
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        transition: opacity 0.3s;
        backdrop-filter: blur(2px);
    }
    .hover-product-card:hover .product-overlay {
        opacity: 1;
    }

    /* Modal Form Styling */
    .form-control:focus {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        border-color: var(--bs-primary);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format Rupiah
    function formatIDR(val) {
        return new Intl.NumberFormat('id-ID').format(val.replace(/\D/g, ''));
    }

    document.querySelectorAll('.format-rupiah').forEach(input => {
        input.addEventListener('input', function() {
            this.value = formatIDR(this.value);
        });
        
        const form = input.closest('form');
        form.addEventListener('submit', () => {
            input.value = input.value.replace(/\./g, '');
        });
    });
    
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const id = btn.dataset.produkId;
        const img = btn.dataset.produkGambar;
        
        const form = document.getElementById('editForm');
        form.action = `/produk/${id}`;
        
        document.getElementById('edit_nama_produk').value = btn.dataset.produkNama;
        document.getElementById('edit_harga').value = formatIDR(btn.dataset.produkHarga);
        
        const currentImgDiv = document.getElementById('currentImage');
        if (img) {
            currentImgDiv.classList.remove('d-none');
            currentImgDiv.innerHTML = `
                <small class="text-muted d-block mb-2">Gambar saat ini:</small>
                <img src="/storage/${img}" class="rounded shadow-sm" style="max-height: 120px; max-width: 100%;">
            `;
        } else {
            currentImgDiv.classList.add('d-none');
        }
    });

    editModal.addEventListener('hidden.bs.modal', () => {
        document.getElementById('editForm').reset();
    });
});
</script>
@endpush
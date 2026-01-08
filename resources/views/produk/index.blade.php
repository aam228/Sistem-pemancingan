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
        <button type="button" class="btn-close" data-bs-alert="dismiss" aria-label="Close"></button>
    </div>
    @endif

    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-body fw-bold">Kelola Makanan & Minuman</h5>
            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-1"></i> Tambah Produk
            </button>
        </div>

        <div class="card-body bg-body-tertiary p-4">
            @if($produks->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-utensils fa-3x mb-3 opacity-25"></i>
                    <p>Belum ada produk yang ditambahkan.</p>
                </div>
            @else
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    @foreach($produks as $produk)
                    <div class="col">
                        <div class="card h-100 bg-body border shadow-sm hover-product-card">
                            <div class="position-relative overflow-hidden">
                                @if($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" class="card-img-top object-fit-cover" style="height: 140px;">
                                @else
                                    <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height: 140px;">
                                        <i class="fas fa-utensils fa-2x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button type="button" class="btn btn-warning btn-sm shadow"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="{{ $produk->id }}"
                                            data-nama="{{ $produk->nama_produk }}"
                                            data-harga="{{ $produk->harga }}"
                                            data-gambar="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : '' }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    {{-- Tombol Hapus memicu Modal --}}
                                    <button type="button" class="btn btn-danger btn-sm shadow"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-id="{{ $produk->id }}"
                                            data-nama="{{ $produk->nama_produk }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body text-center p-3">
                                <h6 class="card-title mb-1 text-truncate text-body small fw-bold">{{ $produk->nama_produk }}</h6>
                                <p class="card-text fw-bold text-success mb-0 small">
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

{{-- Include Modals --}}
@include('produk.modals')

@endsection

@push('styles')
<style>
    .hover-product-card { transition: all 0.3s ease; overflow: hidden; }
    .hover-product-card:hover { transform: translateY(-5px); border-color: var(--bs-primary) !important; }
    .object-fit-cover { object-fit: cover; }
    .product-overlay { background: rgba(0, 0, 0, 0.5); opacity: 0; transition: opacity 0.3s; backdrop-filter: blur(2px); }
    .hover-product-card:hover .product-overlay { opacity: 1; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. FORMAT RUPIAH LOGIC
    function formatIDR(val) {
        return new Intl.NumberFormat('id-ID').format(val.toString().replace(/\D/g, ''));
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
    
    // 2. LOGIKA EDIT MODAL
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const id = btn.dataset.id;
        const img = btn.dataset.gambar;
        
        document.getElementById('editForm').action = `/produk/${id}`;
        document.getElementById('edit_nama_produk').value = btn.dataset.nama;
        document.getElementById('edit_harga').value = formatIDR(btn.dataset.harga);
        
        const currentImgContainer = document.getElementById('edit_image_preview_container');
        const currentImg = document.getElementById('edit_image_preview');
        
        if (img) {
            currentImgContainer.classList.remove('d-none');
            currentImg.src = img;
        } else {
            currentImgContainer.classList.add('d-none');
        }
    });

    // 3. LOGIKA DELETE MODAL
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        document.getElementById('delete_nama_produk').textContent = btn.dataset.nama;
        document.getElementById('deleteForm').action = `/produk/${btn.dataset.id}`;
    });
});
</script>
@endpush
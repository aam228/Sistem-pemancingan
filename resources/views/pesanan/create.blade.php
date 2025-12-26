@extends('layouts.app')

@section('title', 'Pesan Makanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pesan Makanan</li>
@endsection

@section('content')
<div class="container-fluid px-0 px-md-2">
    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 px-3 mb-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card bg-body border shadow-sm">
        <div class="card-header bg-transparent py-3 px-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-body fw-bold">Pesan Menu - {{ $transaksi->meja->nama_meja }}</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2">
                    <i class="fas fa-user me-1"></i> {{ Str::limit($transaksi->nama_pelanggan, 15) }}
                </span>
            </div>
        </div>

        <div class="card-body bg-body-tertiary p-3">
            <form id="pesananForm" action="{{ route('pesanan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">

                @if($produk->count() > 0)
                {{-- Grid disamakan dengan Kelola Makanan --}}
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 mb-4">
                    @foreach ($produk as $item)
                    <div class="col">
                        <div class="card bg-body border h-100 shadow-sm hover-product-card">
                            {{-- Gambar Produk disamakan height 140px --}}
                            <div class="position-relative overflow-hidden" style="height: 140px;">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" 
                                         alt="{{ $item->nama_produk }}" 
                                         class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-body-secondary">
                                        <i class="fas fa-utensils fa-2x text-muted opacity-50"></i>
                                    </div>
                                @endif
                                
                                {{-- Badge Jumlah --}}
                                <div class="position-absolute top-0 end-0 p-2" id="badge-{{ $item->id }}" style="display: none;">
                                    <span class="badge bg-success shadow-sm px-2 py-1">
                                        <span id="badge-count-{{ $item->id }}">0</span>
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-2 d-flex flex-column justify-content-between">
                                <div class="text-center mb-2">
                                    <h6 class="card-title mb-1 small text-truncate text-body" title="{{ $item->nama_produk }}">
                                        {{ $item->nama_produk }}
                                    </h6>
                                    <p class="text-success fw-bold mb-0 small">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </p>
                                </div>

                                {{-- Kontrol Jumlah --}}
                                <div class="d-flex align-items-center justify-content-between bg-body-tertiary rounded-pill p-1 border">
                                    <button type="button" 
                                            class="btn btn-sm btn-light rounded-circle shadow-sm change-btn d-flex align-items-center justify-content-center"
                                            style="width: 26px; height: 26px;"
                                            data-id="{{ $item->id }}" 
                                            data-change="-1">
                                        <i class="fas fa-minus fa-xs text-danger"></i>
                                    </button>
                                    
                                    <input type="number" 
                                           name="produk[{{ $item->id }}]" 
                                           id="jumlah-{{ $item->id }}" 
                                           data-harga="{{ $item->harga }}" 
                                           min="0" 
                                           value="0"
                                           class="form-control form-control-sm border-0 bg-transparent text-center p-0 fw-bold jumlah-input"
                                           style="box-shadow: none; width: 35px;">
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-light rounded-circle shadow-sm change-btn d-flex align-items-center justify-content-center"
                                            style="width: 26px; height: 26px;"
                                            data-id="{{ $item->id }}" 
                                            data-change="1">
                                        <i class="fas fa-plus fa-xs text-success"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-warning border-0 shadow-sm py-3 px-3 mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> Belum ada menu yang tersedia untuk dipesan.
                </div>
                @endif

                {{-- Summary Section --}}
                <div class="card bg-body border-success bg-opacity-10 mb-4 shadow-sm">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Total Pesanan</small>
                                <h4 class="mb-0 text-success fw-bold" id="totalHarga">Rp 0</h4>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Item Dipilih</small>
                                <h4 class="mb-0 text-body fw-bold" id="totalItem">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="button" 
                            class="btn btn-outline-primary btn-sm px-3 rounded-pill"
                            data-bs-toggle="modal" 
                            data-bs-target="#daftarPesananModal">
                        <i class="fas fa-receipt me-1"></i> Pesanan Saat Ini
                    </button>
                    <button type="submit" class="btn btn-success btn-sm px-4 rounded-pill shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="daftarPesananModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title text-body fw-bold">
                    <i class="fas fa-receipt me-2 text-primary"></i> Daftar Pesanan Meja
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @if($pesananMakanan->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-body-tertiary">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-3 border-0 py-2">Menu</th>
                                    <th class="text-center border-0 py-2">Qty</th>
                                    <th class="text-end border-0 py-2 pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="text-body border-top-0">
                                @php $totalAkhir = 0; @endphp
                                @foreach($pesananMakanan as $pesanan)
                                    @php
                                        $sub = $pesanan->produk->harga * $pesanan->jumlah;
                                        $totalAkhir += $sub;
                                    @endphp
                                    <tr class="small">
                                        <td class="ps-3 py-2 fw-medium">{{ $pesanan->produk->nama_produk }}</td>
                                        <td class="text-center py-2">{{ $pesanan->jumlah }}</td>
                                        <td class="text-end py-2 pe-3 fw-bold">Rp {{ number_format($sub, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-body-tertiary border-top">
                                <tr>
                                    <td colspan="2" class="ps-3 py-3 fw-bold text-body">Total Keseluruhan</td>
                                    <td class="text-end py-3 pe-3 fw-bold text-success h5 mb-0">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-basket fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted small">Belum ada pesanan makanan untuk meja ini.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menghitung ulang total harga dan total item
    const updateTotal = () => {
        let total = 0;
        let totalItems = 0;
        
        document.querySelectorAll('.jumlah-input').forEach(input => {
            const jumlah = parseInt(input.value) || 0;
            const harga = parseInt(input.dataset.harga) || 0;
            const id = input.id.replace('jumlah-', '');
            
            total += jumlah * harga;
            totalItems += jumlah;
            
            // Update Badge di pojok gambar
            const badge = document.getElementById(`badge-${id}`);
            if (badge) {
                if (jumlah > 0) {
                    badge.style.display = 'block';
                    document.getElementById(`badge-count-${id}`).textContent = jumlah;
                } else {
                    badge.style.display = 'none';
                }
            }
        });
        
        // Update tampilan summary di bawah
        const totalHargaEl = document.getElementById('totalHarga');
        const totalItemEl = document.getElementById('totalItem');
        
        if (totalHargaEl) totalHargaEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        if (totalItemEl) totalItemEl.textContent = totalItems;
    };

    // Handler tombol Plus (+) dan Minus (-)
    document.querySelectorAll('.change-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah form tersubmit secara tidak sengaja
            
            const id = this.dataset.id;
            const change = parseInt(this.dataset.change);
            const input = document.getElementById(`jumlah-${id}`);
            
            if (input) {
                let current = parseInt(input.value) || 0;
                let newValue = current + change;
                
                // Set nilai baru, minimal 0
                input.value = newValue < 0 ? 0 : newValue;
                
                // Triger hitung ulang
                updateTotal();
            }
        });
    });

    // Handler jika user mengetik angka manual di input
    document.querySelectorAll('.jumlah-input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value < 0 || this.value === '') {
                this.value = 0;
            }
            updateTotal();
        });
        
        // Mencegah input karakter non-angka
        input.addEventListener('keydown', function(e) {
            if (['-', 'e', '+', 'E'].includes(e.key)) {
                e.preventDefault();
            }
        });
    });

    // Validasi saat submit: minimal ada 1 menu yang dipesan
    const form = document.getElementById('pesananForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const totalItems = parseInt(document.getElementById('totalItem').textContent) || 0;
            if (totalItems === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu menu sebelum konfirmasi pesanan!');
            }
        });
    }

    // Jalankan sekali saat load untuk reset tampilan
    updateTotal();
});
</script>
@endpush

@push('styles')
<style>
    .hover-product-card {
        transition: transform 0.2s ease, border-color 0.2s;
    }
    .hover-product-card:hover {
        transform: translateY(-3px);
        border-color: var(--bs-primary) !important;
    }

    /* Hilangkan arrow spinner di input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Style khusus tombol minus plus agar kontras di dark mode */
    [data-bs-theme="dark"] .change-btn {
        background-color: var(--bs-tertiary-bg);
        border-color: var(--bs-border-color);
        color: white;
    }
</style>
@endpush
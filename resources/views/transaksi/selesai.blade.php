@extends('layouts.app')

@section('title', 'Selesai Sesi Pemancingan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Selesai Sesi</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm border bg-body">
                {{-- Header Sesi --}}
                <div class="card-header bg-transparent border-bottom text-center py-4">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 d-inline-block p-3 rounded-circle">
                            <i class="fas fa-fish fa-2x text-success"></i>
                        </div>
                    </div>
                    <h4 class="mb-1 fw-bold text-body">Selesai Sesi</h4>
                    <p class="text-muted mb-0 small">
                        <strong>{{ $transaksi->nama_pelanggan }}</strong> 
                        @if($transaksi->member && $transaksi->member->status === 'active')
                            <span class="badge bg-primary ms-1 shadow-sm">MEMBER: {{ $transaksi->member->diskon_persen }}% OFF</span>
                        @endif
                        — Kolam {{ $transaksi->spot->nama_spot }}
                    </p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transaksi.selesai.proses', $transaksi->id) }}" method="POST" id="selesaiForm">
                        @csrf
                        @method('PUT')

                        {{-- Input Hasil Tangkapan --}}
                        <div class="row g-3 mb-4">
                            @php
                                // Ambil diskon dari member (jika ada dan aktif)
                                $persen = ($transaksi->member && $transaksi->member->status === 'active') ? $transaksi->member->diskon_persen : 0;
                                $labelHargaKecil = 5000 * ((100 - $persen) / 100);
                                $labelHargaBabon = 25000 * ((100 - $persen) / 100);
                            @endphp

                            <div class="col-6">
                                <label class="form-label fw-bold small text-body">Ikan Kecil (ekor)</label>
                                <input type="number" name="jumlah_ikan_kecil" id="jumlah_ikan_kecil"
                                    class="form-control fw-bold bg-body-tertiary border-secondary-subtle"
                                    value="0" min="0">
                                <small class="{{ $persen > 0 ? 'text-primary fw-bold' : 'text-muted' }}">
                                    @ Rp {{ number_format($labelHargaKecil, 0, ',', '.') }}
                                </small>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-body">Ikan Babon (kg)</label>
                                <input type="number" name="berat_ikan_babon" id="berat_ikan_babon"
                                    class="form-control fw-bold bg-body-tertiary border-secondary-subtle"
                                    value="0" min="0" step="0.1">
                                <small class="{{ $persen > 0 ? 'text-primary fw-bold' : 'text-muted' }}">
                                    @ Rp {{ number_format($labelHargaBabon, 0, ',', '.') }}
                                </small>
                            </div>
                        </div>

                        {{-- Total Tagihan Dinamis --}}
                        <div class="card border-success bg-success bg-opacity-10 mb-4 shadow-sm">
                            <div class="card-body text-center py-3">
                                <h6 class="text-success fw-bold mb-1 small text-uppercase">Total Tagihan</h6>
                                <h3 class="fw-bold text-success mb-0" id="total_preview">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </h3>
                                @if($persen > 0)
                                    <div class="badge bg-primary mt-2" style="font-size: 10px;">
                                        <i class="fas fa-tags me-1"></i> POTONGAN MEMBER {{ $persen }}% TERPASANG
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Pilih Metode Pembayaran --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-body">Metode Pembayaran</label>
                            <select name="payment_method_id" id="payment_method" class="form-select fw-bold bg-body border-secondary-subtle shadow-sm" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach($paymentMethods as $pm)
                                    <option value="{{ $pm->id }}" 
                                            data-tipe="{{ $pm->tipe }}"
                                            data-bank="{{ $pm->nama_bank }}"
                                            data-norek="{{ $pm->no_rekening }}"
                                            data-pemilik="{{ $pm->nama_pemilik }}"
                                            data-qr="{{ asset('storage/' . $pm->qr_image) }}">
                                        {{ strtoupper($pm->tipe) }} - {{ $pm->nama_metode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- AREA DETAIL PEMBAYARAN (Cash, Transfer, QRIS) --}}
                        <div id="cashSection" class="card bg-body-secondary mb-4 d-none border-0 shadow-sm animate__animated animate__fadeIn">
                            <div class="card-body py-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-6 border-end">
                                        <label class="form-label small fw-bold text-body">Diterima (Rp)</label>
                                        <input type="number" id="uang_diterima" name="uang_diterima"
                                            class="form-control fw-bold bg-body border-primary h3 mb-0" placeholder="0">
                                    </div>
                                    <div class="col-6 ps-3">
                                        <label class="form-label small fw-bold text-body">Kembalian</label>
                                        <h4 class="fw-bold text-primary mb-0" id="kembalian_display">Rp 0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="transferSection" class="alert alert-info d-none bg-info bg-opacity-10 border-info animate__animated animate__fadeIn">
                            <div class="d-flex align-items-center mb-1 text-info small fw-bold">
                                <i class="fas fa-university me-2 fa-lg"></i> <span id="display_bank">BANK</span>
                            </div>
                            <h5 class="fw-bold mb-1 text-body" id="display_norek">00000000</h5>
                            <small class="text-muted text-uppercase">A.N. <span id="display_pemilik" class="text-body fw-bold">NAMA</span></small>
                        </div>

                        <div id="qrisSection" class="text-center d-none mb-4 animate__animated animate__fadeIn">
                            <div class="p-2 bg-white d-inline-block rounded shadow-sm border border-2 border-primary-subtle position-relative">
                                <img src="" id="display_qr" class="img-fluid" style="max-width:140px; cursor: pointer;" 
                                     data-bs-toggle="modal" data-bs-target="#qrisPopup">
                                <div class="position-absolute bottom-0 end-0 p-1">
                                    <button type="button" class="btn btn-sm btn-primary py-0 px-1" data-bs-toggle="modal" data-bs-target="#qrisPopup">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm px-4 pt-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm" id="submitBtn">
                                <i class="fas fa-check-circle me-1"></i> Selesai & Bayar
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL QRIS --}}
<div class="modal fade" id="qrisPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center">
                <div class="p-4 bg-white rounded-4 shadow-lg d-inline-block">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">Scan QRIS</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <img src="" id="modal_qr_img" class="img-fluid rounded border mb-3" style="max-width: 320px;">
                    <div class="py-2 px-3 bg-light rounded border">
                        <p class="mb-0 fw-bold text-primary" id="modal_qr_name">NAMA METODE</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const methodSelect = document.getElementById('payment_method');
    const cashSec = document.getElementById('cashSection');
    const transferSec = document.getElementById('transferSection');
    const qrisSec = document.getElementById('qrisSection');
    const uangInput = document.getElementById('uang_diterima');
    const kembalianDisp = document.getElementById('kembalian_display');
    const submitBtn = document.getElementById('submitBtn');
    const ikanKecil = document.getElementById('jumlah_ikan_kecil');
    const ikanBabon = document.getElementById('berat_ikan_babon');
    const totalPreview = document.getElementById('total_preview');

    // LOGIKA HARGA DARI DISKON MEMBER DB
    const hargaPaket = {{ $transaksi->total_harga }};
    const diskonPersen = {{ ($transaksi->member && $transaksi->member->status === 'active') ? $transaksi->member->diskon_persen : 0 }};
    
    const hargaKecilReguler = 5000;
    const hargaBabonReguler = 25000;

    // Perhitungan harga satuan (dibulatkan)
    const hargaKecil = Math.round(hargaKecilReguler * ((100 - diskonPersen) / 100));
    const hargaBabon = Math.round(hargaBabonReguler * ((100 - diskonPersen) / 100));

    function format(rp) {
        return 'Rp ' + Math.max(0, rp).toLocaleString('id-ID');
    }

    function hitungTotal() {
        const subKecil = (parseInt(ikanKecil.value) || 0) * hargaKecil;
        const subBabon = (parseFloat(ikanBabon.value) || 0) * hargaBabon;
        const total = hargaPaket + subKecil + subBabon;

        totalPreview.textContent = format(total);

        const opt = methodSelect.options[methodSelect.selectedIndex];
        const tipe = opt ? opt.getAttribute('data-tipe') : '';

        if (tipe === 'cash') {
            const bayar = parseInt(uangInput.value) || 0;
            const sisa = bayar - total;

            if (bayar > 0 && sisa >= 0) {
                kembalianDisp.textContent = format(sisa);
                kembalianDisp.className = 'fw-bold text-primary mb-0';
                submitBtn.disabled = false;
            } else {
                kembalianDisp.textContent = bayar ? 'Uang Kurang' : 'Rp 0';
                kembalianDisp.className = 'fw-bold text-danger mb-0';
                submitBtn.disabled = true;
            }
        } else {
            submitBtn.disabled = methodSelect.value === "";
        }
    }

    methodSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const tipe = opt.getAttribute('data-tipe');
        [cashSec, transferSec, qrisSec].forEach(el => el.classList.add('d-none'));

        if (tipe === 'cash') {
            cashSec.classList.remove('d-none');
            uangInput.focus();
        } else if (tipe === 'transfer') {
            document.getElementById('display_bank').textContent = opt.getAttribute('data-bank');
            document.getElementById('display_norek').textContent = opt.getAttribute('data-norek');
            document.getElementById('display_pemilik').textContent = opt.getAttribute('data-pemilik');
            transferSec.classList.remove('d-none');
        } else if (tipe === 'qris') {
            const qrUrl = opt.getAttribute('data-qr');
            document.getElementById('display_qr').src = qrUrl;
            document.getElementById('modal_qr_img').src = qrUrl;
            document.getElementById('modal_qr_name').textContent = opt.text;
            qrisSec.classList.remove('d-none');
        }
        hitungTotal();
    });

    [ikanKecil, ikanBabon, uangInput].forEach(el => el?.addEventListener('input', hitungTotal));
    hitungTotal();
});
</script>
@endpush
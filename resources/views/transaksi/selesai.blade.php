@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border border-secondary-subtle bg-body shadow-sm">
                
                <div class="card-body border-bottom border-secondary-subtle text-center py-3">
                    <h5 class="fw-bold mb-0 text-body">Selesai Sesi</h5>
                    <small class="text-body-secondary">{{ $transaksi->nama_pelanggan }} — {{ $transaksi->spot->nama_spot }}</small>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transaksi.selesai.proses', $transaksi->id) }}" method="POST">
                        @csrf @method('PUT')

                        @php
                            // Logika Harga & Member
                            $isMember = ($transaksi->member && $transaksi->member->status === 'active');
                            $persen = $isMember ? $transaksi->member->diskon_persen : 0;
                            
                            $hKecilNormal = 5000;
                            $hBabonNormal = 25000;
                            
                            $hKecilFinal = $hKecilNormal * ((100 - $persen) / 100);
                            $hBabonFinal = $hBabonNormal * ((100 - $persen) / 100);
                        @endphp

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small fw-bold text-body-secondary text-uppercase">Ikan Kecil</label>
                                <input type="number" name="jumlah_ikan_kecil" id="jumlah_ikan_kecil" class="form-control bg-body-tertiary border-0 text-body fw-bold" value="0">
                                <div class="mt-1" style="font-size: 11px;">
                                    @if($isMember)
                                        <span class="text-decoration-line-through text-muted">Rp 5.000</span>
                                        <span class="text-primary fw-bold">Rp {{ number_format($hKecilFinal, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-body-secondary">Rp 5.000 / ekor</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="small fw-bold text-body-secondary text-uppercase">Ikan Babon</label>
                                <input type="number" name="berat_ikan_babon" id="berat_ikan_babon" class="form-control bg-body-tertiary border-0 text-body fw-bold" value="0" step="0.1">
                                <div class="mt-1" style="font-size: 11px;">
                                    @if($isMember)
                                        <span class="text-decoration-line-through text-muted">Rp 25.000</span>
                                        <span class="text-primary fw-bold">Rp {{ number_format($hBabonFinal, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-body-secondary">Rp 25.000 / kg</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded bg-success-subtle border border-success-subtle text-center mb-4">
                            <span class="small fw-bold text-success text-uppercase">Total Pembayaran</span>
                            <h2 class="fw-bold text-success mb-0" id="total_preview">Rp 0</h2>
                            @if($isMember)
                                <small class="text-success small fw-bold" style="font-size: 10px;">DISKON MEMBER {{ $persen }}% AKTIF</small>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-body-secondary text-uppercase">Metode Pembayaran</label>
                            <select name="payment_method_id" id="payment_method" class="form-select border-0 bg-body-tertiary text-body fw-bold" required>
                                <option value="">-- Pilih --</option>
                                @foreach($paymentMethods as $pm)
                                    <option value="{{ $pm->id }}" 
                                            data-tipe="{{ $pm->tipe }}" 
                                            data-pemilik="{{ $pm->nama_pemilik }}" 
                                            data-bank="{{ $pm->nama_bank }}" 
                                            data-norek="{{ $pm->no_rekening }}" 
                                            data-qr="{{ asset('storage/' . $pm->qr_image) }}">
                                        {{ $pm->nama_metode }} - {{ $pm->nama_pemilik }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="paymentDetail" class="d-none mb-4">
                            <div id="cashArea" class="d-none">
                                <div class="row g-2 mb-2 align-items-center">
                                    <div class="col-6">
                                        <input type="number" id="uang_diterima" class="form-control bg-body-tertiary border-0 text-body" placeholder="Diterima (Rp)">
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="fw-bold text-primary" id="kembalian_display">Rp 0</span>
                                    </div>
                                </div>
                                <div class="small text-body-secondary border-top pt-2">
                                    Penerima: <span class="fw-bold text-body text-uppercase" id="disp_owner_cash">-</span>
                                </div>
                            </div>

                            <div id="transferArea" class="d-none alert alert-info bg-info-subtle border-0 py-2 small">
                                <div class="fw-bold text-uppercase text-info" id="disp_bank">BANK</div>
                                <div class="h6 fw-bold mb-0 text-body" id="disp_norek">00000</div>
                                <div class="mt-1 text-body-secondary small">A.N. <span id="disp_owner_tf" class="fw-bold text-body">-</span></div>
                            </div>

                            <div id="qrisArea" class="d-none text-center">
                                <img src="" id="disp_qr" class="img-fluid rounded border bg-white p-1 mb-1" style="max-width:120px;">
                                <div class="small text-body-secondary fw-bold text-uppercase" id="disp_owner_qr">-</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary flex-fill opacity-50">Batal</a>
                            <button type="submit" class="btn btn-primary flex-fill fw-bold" id="submitBtn">Bayar Sekarang</button>
                        </div>

                    </form>
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
    const cashArea = document.getElementById('cashArea');
    const transferArea = document.getElementById('transferArea');
    const qrisArea = document.getElementById('qrisArea');
    const totalPreview = document.getElementById('total_preview');
    const uangInput = document.getElementById('uang_diterima');
    const kembalianDisp = document.getElementById('kembalian_display');
    const submitBtn = document.getElementById('submitBtn');

    // Data Harga (Ambil dari Blade)
    const hargaPaket = {{ $transaksi->total_harga }};
    const diskon = {{ ($transaksi->member && $transaksi->member->status === 'active') ? $transaksi->member->diskon_persen : 0 }};
    const hKecil = Math.round(5000 * ((100 - diskon) / 100));
    const hBabon = Math.round(25000 * ((100 - diskon) / 100));

    function format(rp) {
        return 'Rp ' + Math.max(0, rp).toLocaleString('id-ID');
    }

    function hitung() {
        const kecil = parseInt(document.getElementById('jumlah_ikan_kecil').value) || 0;
        const babon = parseFloat(document.getElementById('berat_ikan_babon').value) || 0;
        const total = hargaPaket + (kecil * hKecil) + (babon * hBabon);
        
        totalPreview.textContent = format(total);

        const opt = methodSelect.options[methodSelect.selectedIndex];
        if (opt?.getAttribute('data-tipe') === 'cash') {
            const bayar = parseInt(uangInput.value) || 0;
            const sisa = bayar - total;
            kembalianDisp.textContent = format(sisa);
            kembalianDisp.className = sisa >= 0 ? 'fw-bold text-primary' : 'fw-bold text-danger';
            submitBtn.disabled = sisa < 0;
        } else {
            submitBtn.disabled = !methodSelect.value;
        }
    }

    methodSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('paymentDetail').classList.toggle('d-none', !this.value);
        [cashArea, transferArea, qrisArea].forEach(el => el.classList.add('d-none'));

        if (this.value) {
            const tipe = opt.getAttribute('data-tipe');
            const pemilik = opt.getAttribute('data-pemilik');

            if (tipe === 'cash') {
                cashArea.classList.remove('d-none');
                document.getElementById('disp_owner_cash').textContent = pemilik;
            } else if (tipe === 'transfer') {
                transferArea.classList.remove('d-none');
                document.getElementById('disp_bank').textContent = opt.getAttribute('data-bank');
                document.getElementById('disp_norek').textContent = opt.getAttribute('data-norek');
                document.getElementById('disp_owner_tf').textContent = pemilik;
            } else if (tipe === 'qris') {
                qrisArea.classList.remove('d-none');
                document.getElementById('disp_qr').src = opt.getAttribute('data-qr');
                document.getElementById('disp_owner_qr').textContent = pemilik;
            }
        }
        hitung();
    });

    document.querySelectorAll('input').forEach(el => el.addEventListener('input', hitung));
    hitung();
});
</script>
@endpush
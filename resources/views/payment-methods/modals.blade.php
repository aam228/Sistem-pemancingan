{{-- ================================================================= --}}
{{-- 1. MODAL CREATE --}}
{{-- ================================================================= --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title text-body fw-bold">Tambah Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment-methods.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 text-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Tipe Pembayaran</label>
                            <select name="tipe" id="create_tipe" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Tipe --</option>
                                <option value="cash">TUNAI (CASH)</option>
                                <option value="transfer">TRANSFER BANK</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Nama Metode</label>
                            <input type="text" name="nama_metode" class="form-control" placeholder="Contoh: BCA / Kasir" required>
                        </div>
                        
                        {{-- Field Nama Pemilik --}}
                        <div id="create_owner_field" class="col-12 mb-3 d-none">
                            <div class="border-start border-primary border-4 ps-3 py-1">
                                <label class="form-label small fw-bold text-uppercase">Nama Pemilik / Penerima</label>
                                <input type="text" id="create_input_owner" name="nama_pemilik" class="form-control" placeholder="Nama lengkap">
                            </div>
                        </div>

                        {{-- Field Transfer --}}
                        <div id="create_transfer_fields" class="col-12 d-none">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Nama Bank</label>
                                    <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: BCA">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">No. Rekening</label>
                                    <input type="text" name="no_rekening" class="form-control" placeholder="Nomor rekening">
                                </div>
                            </div>
                        </div>

                        {{-- Field QRIS --}}
                        <div id="create_qris_fields" class="col-12 d-none">
                            <div class="mb-3 p-3 rounded bg-body-tertiary border">
                                <label class="form-label small fw-bold text-uppercase">Upload QR Image</label>
                                <input type="file" name="qr_image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch bg-body-tertiary p-2 rounded border ps-5">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create_active" checked>
                                <label class="form-check-label fw-bold" for="create_active">Aktifkan Metode</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Metode</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{-- 2. MODAL EDIT --}}
{{-- ================================================================= --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title text-body fw-bold">Edit Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Tipe Pembayaran</label>
                            <select name="tipe" id="edit_tipe" class="form-select" required>
                                <option value="cash">TUNAI (CASH)</option>
                                <option value="transfer">TRANSFER BANK</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Nama Metode</label>
                            <input type="text" name="nama_metode" id="edit_nama_metode" class="form-control" required>
                        </div>
                        <div id="edit_owner_field" class="col-12 mb-3">
                            <div class="border-start border-primary border-4 ps-3 py-1">
                                <label class="form-label small fw-bold text-uppercase">Nama Pemilik / Penerima</label>
                                <input type="text" name="nama_pemilik" id="edit_nama_pemilik" class="form-control">
                            </div>
                        </div>
                        <div id="edit_transfer_fields" class="col-12 d-none">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Nama Bank</label>
                                    <input type="text" name="nama_bank" id="edit_nama_bank" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">No. Rekening</label>
                                    <input type="text" name="no_rekening" id="edit_no_rekening" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div id="edit_qris_fields" class="col-12 d-none">
                            <div class="mb-3 p-3 rounded bg-body-tertiary border">
                                <label class="form-label small fw-bold text-uppercase">Update QR Image</label>
                                <div id="edit_qr_container" class="mb-2 d-none">
                                    <img src="" id="edit_qr_preview" class="img-thumbnail" style="max-width: 100px;">
                                </div>
                                <input type="file" name="qr_image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch bg-body-tertiary p-2 rounded border ps-5">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                                <label class="form-check-label fw-bold" for="edit_is_active">Metode Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Update Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Preview & Delete --}}
<div class="modal fade" id="previewImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none text-center">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-white fw-bold" id="previewTitle"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="" id="imageFull" class="img-fluid rounded shadow-lg border border-3 border-white" style="max-height: 75vh;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-body border shadow text-center">
            <div class="modal-body p-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <h6 class="text-body">Hapus Metode?</h6>
                <p class="small text-muted mb-4"><strong id="modal-nama-metode" class="text-body"></strong> akan dihapus permanen.</p>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
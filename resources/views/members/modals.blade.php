{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-body text-uppercase">Registrasi Member Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-body-secondary text-uppercase">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control bg-body-tertiary border-secondary-subtle" placeholder="Nama Pelanggan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-body-secondary text-uppercase">Nomor Telepon/WA</label>
                        <input type="text" name="telepon" class="form-control bg-body-tertiary border-secondary-subtle" placeholder="0812xxx" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-primary text-uppercase">Diskon (%)</label>
                            <input type="number" name="diskon_persen" class="form-control border-primary bg-body-tertiary" value="10" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-body-secondary text-uppercase">Berlaku Hingga</label>
                            <input type="date" name="expired_at" class="form-control bg-body-tertiary border-secondary-subtle" value="{{ date('Y-12-31') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow">Simpan Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT (Dinamis) --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <form id="formEditMember" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-bottom py-3">
                    <h6 class="modal-title fw-bold text-body text-uppercase">Edit Member: <span id="edit_kode_display" class="text-primary"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-body-secondary text-uppercase">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control bg-body-tertiary border-secondary-subtle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-body-secondary text-uppercase">Nomor WA</label>
                        <input type="text" name="telepon" id="edit_telepon" class="form-control bg-body-tertiary border-secondary-subtle" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-primary text-uppercase">Diskon (%)</label>
                            <input type="number" name="diskon_persen" id="edit_diskon" class="form-control border-primary bg-body-tertiary" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-body-secondary text-uppercase">Status</label>
                            <select name="status" id="edit_status" class="form-select bg-body-tertiary border-secondary-subtle">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-body-secondary text-uppercase">Masa Berlaku</label>
                        <input type="date" name="expired_at" id="edit_expired_at" class="form-control bg-body-tertiary border-secondary-subtle" required>
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow">Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-body border shadow text-center">
            <div class="modal-body p-4">
                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                <h6 class="text-body fw-bold">Hapus Member?</h6>
                <p class="small text-muted mb-4">Member <strong id="hapus_nama_display" class="text-body"></strong> akan dihapus permanen.</p>
                <form id="formHapusMember" method="POST">
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
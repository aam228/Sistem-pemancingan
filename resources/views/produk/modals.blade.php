{{-- MODAL CREATE --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body fw-bold">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_nama_produk" class="form-label text-body small fw-bold">NAMA PRODUK</label>
                        <input type="text" name="nama_produk" id="create_nama_produk" class="form-control bg-body-tertiary" placeholder="Masukkan nama makanan/minuman" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_harga" class="form-label text-body small fw-bold">HARGA (Rp)</label>
                        <input type="text" name="harga" id="create_harga" class="form-control bg-body-tertiary format-rupiah" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_gambar" class="form-label text-body small fw-bold">GAMBAR PRODUK</label>
                        <input type="file" name="gambar" id="create_gambar" class="form-control bg-body-tertiary" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3 shadow">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-body fw-bold">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_produk" class="form-label text-body small fw-bold">NAMA PRODUK</label>
                        <input type="text" name="nama_produk" id="edit_nama_produk" class="form-control bg-body-tertiary" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga" class="form-label text-body small fw-bold">HARGA (Rp)</label>
                        <input type="text" name="harga" id="edit_harga" class="form-control bg-body-tertiary format-rupiah" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gambar" class="form-label text-body small fw-bold">GANTI GAMBAR (OPSIONAL)</label>
                        <input type="file" name="gambar" id="edit_gambar" class="form-control bg-body-tertiary" accept="image/*">
                        
                        <div id="edit_image_preview_container" class="mt-3 text-center border rounded p-2 bg-body-secondary d-none">
                            <small class="text-muted d-block mb-2 italic">Gambar saat ini:</small>
                            <img src="" id="edit_image_preview" class="rounded shadow-sm" style="max-height: 120px; max-width: 100%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-body-tertiary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3 shadow">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DELETE --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-body border shadow">
            <div class="modal-body text-center p-4">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <h6 class="mb-2 text-body fw-bold">Hapus Produk?</h6>
                <p class="small text-muted mb-4">Produk <strong id="delete_nama_produk" class="text-body"></strong> akan dihapus permanen.</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
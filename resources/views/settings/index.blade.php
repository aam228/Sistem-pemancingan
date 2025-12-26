@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan Akun</li>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <h5 class="mb-3 text-body">Pengaturan Akun</h5>

            {{-- Foto Profil --}}
            <div class="card mb-3 bg-body border shadow-sm">
                <div class="card-header bg-body border-bottom py-2 px-3">
                    <h6 class="mb-0 text-body">Foto Profil</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default_profile.png') }}"
                                 alt="Foto Profil" 
                                 class="rounded-circle object-fit-cover profile-image-preview border"
                                 width="80" 
                                 height="80">
                        </div>
                        <div class="col">
                            <form action="{{ route('settings.updateProfileImage') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="mb-2">
                                    <label for="profile_image" class="form-label mb-1 text-body">Unggah Foto Baru</label>
                                    <input type="file" id="profile_image" name="profile_image" accept="image/*" class="form-control form-control-sm bg-body-tertiary">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i> Unggah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Profil --}}
            <div class="card mb-3 bg-body border shadow-sm">
                <div class="card-header bg-body border-bottom py-2 px-3">
                    <h6 class="mb-0 text-body">Edit Profil</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('settings.updateProfile') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label for="name" class="form-label text-body">Nama</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control form-control-sm bg-body-tertiary" required>
                        </div>
                        <div class="mb-2">
                            <label for="email" class="form-label text-body">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control form-control-sm bg-body-tertiary" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Ubah Kata Sandi --}}
            <div class="card mb-3 bg-body border shadow-sm">
                <div class="card-header bg-body border-bottom py-2 px-3">
                    <h6 class="mb-0 text-body">Ubah Kata Sandi</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('settings.updatePassword') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-2">
                            <label for="current_password" class="form-label text-body">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="form-control form-control-sm bg-body-tertiary" required>
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label text-body">Kata Sandi Baru</label>
                            <input type="password" id="password" name="password" class="form-control form-control-sm bg-body-tertiary" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-body">Konfirmasi Kata Sandi</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-sm bg-body-tertiary" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Ubah Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Pengaturan Tema --}}
            <div class="card mb-5 bg-body border shadow-sm">
                <div class="card-header bg-body border-bottom py-2 px-3">
                    <h6 class="mb-0 text-body">Pengaturan Tampilan</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('settings.updateTheme') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <label for="theme" class="form-label">Pilih Tema</label>
                                <select id="theme_select" name="theme" class="form-select">
                                    <option value="light" {{ auth()->user()->theme == 'light' ? 'selected' : '' }}>Terang</option>
                                    <option value="dark" {{ auth()->user()->theme == 'dark' ? 'selected' : '' }}>Gelap</option>
                                    <option value="system" {{ auth()->user()->theme == 'system' ? 'selected' : '' }}>Ikuti Sistem</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="submit" class="btn btn-primary mt-3 mt-md-0">
                                    Simpan Tema
                                </button>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Preview Gambar
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.querySelector('.profile-image-preview');
    
    if (profileImageInput && profileImagePreview) {
        profileImageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { profileImagePreview.src = e.target.result; }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    // Instan Theme Change (Agar langsung berubah saat select diganti)
    const themeSelect = document.getElementById('theme_select');
    themeSelect.addEventListener('change', function() {
        const selectedTheme = this.value;
        let themeToApply = selectedTheme;
        
        if (selectedTheme === 'system') {
            themeToApply = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        
        document.documentElement.setAttribute('data-bs-theme', themeToApply);
    });
});
</script>
@endpush
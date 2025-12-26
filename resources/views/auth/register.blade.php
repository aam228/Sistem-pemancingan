<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary"><i class="fas fa-user-plus me-2"></i>Daftar Akun</h3>
                    <p class="text-muted small">Mulai kelola sistem pemancingan Anda</p>
                </div>

                <div class="card shadow-sm border bg-body">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label small fw-bold text-body">Nama Lengkap</label>
                                <input id="name" class="form-control bg-body-tertiary @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama Anda" />
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-bold text-body">Email</label>
                                <input id="email" class="form-control bg-body-tertiary @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com" />
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label small fw-bold text-body">Password</label>
                                    <input id="password" class="form-control bg-body-tertiary @error('password') is-invalid @enderror" type="password" name="password" required placeholder="••••••••" />
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label small fw-bold text-body">Konfirmasi</label>
                                    <input id="password_confirmation" class="form-control bg-body-tertiary" type="password" name="password_confirmation" required placeholder="••••••••" />
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm">
                                    Daftar Sekarang
                                </button>
                                <a href="{{ route('login') }}" class="text-center small text-muted text-decoration-none mt-2">
                                    Sudah punya akun? <span class="text-primary fw-bold">Login</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
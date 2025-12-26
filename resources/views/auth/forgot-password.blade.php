<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-body">Lupa Password?</h4>
                    <p class="text-muted small px-3">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
                </div>

                <div class="card shadow-sm border bg-body">
                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success border-0 small mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label small fw-bold text-body">Email Alamat</label>
                                <input id="email" class="form-control bg-body-tertiary @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" />
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm">
                                    Kirim Link Reset
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-link btn-sm text-decoration-none mt-2 text-muted">
                                    Kembali ke Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
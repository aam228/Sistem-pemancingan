<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                
                {{-- Logo atau Nama Sistem --}}
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary"><i class="fas fa-billiards me-2"></i>Billiard Pro</h3>
                    <p class="text-muted small">Silakan login untuk mengelola sistem</p>
                </div>

                <div class="card shadow-sm border bg-body">
                    <div class="card-body p-4">
                        
                        @if (session('status'))
                            <div class="alert alert-success border-0 small mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label small fw-bold text-body">{{ __('Email') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0 text-muted">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" 
                                           class="form-control bg-body-tertiary border-start-0 @error('email') is-invalid @enderror" 
                                           type="email" name="email" 
                                           value="{{ old('email') }}" 
                                           required autofocus 
                                           placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label for="password" class="form-label small fw-bold text-body">{{ __('Password') }}</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">
                                            Lupa Password?
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0 text-muted">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" 
                                           class="form-control bg-body-tertiary border-start-0 @error('password') is-invalid @enderror" 
                                           type="password" 
                                           name="password" 
                                           required 
                                           placeholder="••••••••">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-1" style="font-size: 0.75rem;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input shadow-none" name="remember">
                                <label for="remember_me" class="form-check-label small text-muted">{{ __('Ingat saya di perangkat ini') }}</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm">
                                    {{ __('Masuk Sekarang') }}
                                </button>
                                
                                @if (Route::has('register'))
                                    <div class="text-center mt-3">
                                        <p class="small text-muted mb-0">Belum punya akun? 
                                            <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar</a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    &copy; {{ date('Y') }} Billiard Management System
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

@push('styles')
<style>
    /* Menyeimbangkan tampilan input group dengan border adaptif */
    .input-group-text {
        border-color: var(--bs-border-color);
    }
    .form-control:focus {
        background-color: var(--bs-body-bg);
        border-color: var(--bs-primary);
        box-shadow: none;
    }
    .form-control:focus + .input-group-text, 
    .input-group:focus-within .input-group-text {
        border-color: var(--bs-primary);
        color: var(--bs-primary) !important;
    }
</style>
@endpush